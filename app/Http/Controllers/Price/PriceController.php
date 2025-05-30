<?php

namespace App\Http\Controllers\Price;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Order;
use App\Models\Price;
use App\Models\PriceService;
use App\Models\PriceServiceFee;
use App\Models\Service;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PriceController extends Controller {
    
    public function index(Request $request) {
        
        $query = Price::where('user_id', Auth::user()->id)->orderBy('created_at', 'asc');
        if (!empty($request->input('cliente_id'))) {
            $query->where('cliente_id', $request->input('cliente_id'));
        }

        if (!empty($request->input('service_id'))) {
            $query->where('service_id', $request->input('service_id'));
        }

        $clients = Client::where('user_id', Auth::user()->id)->orderBy('name', 'asc')->get();
        $prices = $query->paginate(30);

        $approvedTotal = Price::where('user_id', Auth::user()->id)
            ->where('status', 1)
            ->with('services.fees')
            ->get()
            ->sum(function ($price) {
                return $price->services->sum(function ($s) {
                    $servicesValue = $s->value - $s->discount;
                    $feesValue = $s->fees->sum(fn($f) => $f->value - $f->discount);
                    return $servicesValue + $feesValue;
                });
            });

        $pendingTotal = Price::where('user_id', Auth::user()->id)
            ->where('status', '!==', 1)
            ->with('services.fees')
            ->get()
            ->sum(function ($price) {
                return $price->services->sum(function ($s) {
                    $servicesValue = $s->value - $s->discount;
                    $feesValue = $s->fees->sum(fn($f) => $f->value - $f->discount);
                    return $servicesValue + $feesValue;
                });
            });

        return view('app.Price.list-prices', [
            'prices'  => $prices,
            'clients' => $clients,
            'approvedTotal'  => $approvedTotal,
            'pendingTotal'   => $pendingTotal,
        ]);
    }

    public function view($uuid) {
        
        $price = Price::where('uuid', $uuid)->first();
        if (!$price) {
            return redirect()->back()->with('infor', 'Orçamento não encontrado!');
        }

        $services   = Service::where('user_id', Auth::user()->id)->orderBy('name', 'asc')->get();
        $clients    = Client::where('user_id', Auth::user()->id)->orderBy('name', 'asc')->get();
        $templates  = Template::where('user_id', Auth::user()->id)->orderBy('name', 'asc')->get();
        $order      = Order::where('price_id', $price->id)->where('user_id', Auth::user()->id)->first();
        
        return view('app.Price.view-price', [
            'price'     => $price,
            'clients'   => $clients,
            'services'  => $services,
            'templates' => $templates,
            'order'     => $order
        ]);
    }

    public function store(Request $request) {
        
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'notes'     => 'nullable|max:16000',
        ], [
            'client_id.required' => 'Escolha um cliente para continuar.',
            'client_id.exists'   => 'Cliente indisponível ou não existe.',
            'notes.max'          => 'O campo "Notas" não pode exceder 16.000 caracteres.',
        ]);

        $price = new Price();
        $price->uuid        = Str::uuid();
        $price->user_id     = Auth::user()->id;
        $price->client_id   = $request->input('client_id');
        $price->notes       = $request->input('notes');

        if ($price->save()) {
            return redirect()->route('view-price', ['uuid' => $price->uuid])->with('success', 'Price created successfully.');
        } 

        return redirect()->back()->with('infor', 'Não foi possível gerar o Orçamento, verifique os dados e tente novamente!');
    }

    public function edit(Request $request) {
        
        $validated = $request->validate([
            'uuid'       => 'required|exists:prices,uuid',
            'client_id'  => 'required|exists:clients,id',
            'notes'      => 'nullable|max:16000',
        ], [
            'uuid.required'    => 'Orçamento não encontrado.',
            'client_id.required' => 'Escolha um cliente para continuar.',
            'client_id.exists'   => 'Cliente indisponível ou não existe.',
            'notes.max'          => 'O campo "Notas" não pode exceder 16.000 caracteres.',
        ]);

        $price = Price::where('uuid', $request->input('uuid'))->first();
        if (!$price) {
            return redirect()->back()->with('infor', 'Orçamento não disponível!');
        }

        $price->client_id   = $request->input('client_id');
        $price->notes       = $request->input('notes');
        $price->status      = $request->input('status');

        if ($price->save()) {
            return redirect()->route('view-price', ['uuid' => $price->uuid])->with('success', 'Orçamento atualizado com sucesso!');
        } 

        return redirect()->back()->with('infor', 'Não foi possível atualizar o Orçamento, verifique os dados e tente novamente!');
    }

    public function delete(Request $request) {
        
        $price = Price::where('uuid', $request->uuid)->first();
        if ($price && $price->delete()) {
            return redirect()->back()->with('success', 'Cotação excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao excluir a Cotação, verifique os dados e tente novamente.');
    }

    public function addPriceService(Request $request) {
        
        $validated = $request->validate([
            'services'   => 'required|array',
            'services.*' => 'exists:services,id',
            'price_id'   => 'required|exists:prices,id',
        ], [
            'services.required' => 'Selecione ao menos um serviço.',
            'services.*.exists' => 'Um dos serviços selecionados é inválido.',
            'price_id.required' => 'Cotação indisponível ou não existe.',
            'price_id.exists'   => 'Cotação indisponível ou não existe!',
        ]);

        $order = Order::where('price_id', $request->price_id)->where('user_id', Auth::user()->id)->first();
        if ($order) {
            return redirect()->back()->with('infor', 'Uma Ordem já foi gerada para o orçamento atual, verifique os dados e tente novamente!');
        }

        $services = Service::whereIn('id', $validated['services'])->get();
        foreach ($services as $service) {
            PriceService::create([
                'price_id'   => $validated['price_id'],
                'service_id' => $service->id,
                'value'      => $service->value,
            ]);
        }
    
        return redirect()->back()->with('success', 'Serviços incluídos!');
    }

    public function updatedPriceService(Request $request) {
        
        $service =PriceService::where('id', $request->input('price_service_id'))->first();
        if (!$service) {
            return redirect()->back()->with('infor', 'Serviço não encontrado!');
        }

        $order = Order::where('price_id', $service->price_id)->where('user_id', Auth::user()->id)->first();
        if ($order) {
            return redirect()->back()->with('infor', 'Uma Ordem já foi gerada para o orçamento atual, verifique os dados e tente novamente!');
        }
       
        $service->discount = $this->formatValue($request->input('discount'));
        if ($service->save()) {
            return redirect()->back()->with('success', 'Serviço atualizado com sucesso!');
        }

        return redirect()->back()->with('infor', 'Não foi possível atualizar o serviço, verifique os dados e tente novamente!');
    }

    public function actionPriceServices(Request $request) {

        $order = Order::where('price_id', $request->price_id)->where('user_id', Auth::user()->id)->first();
        if ($order) {
            return redirect()->back()->with('infor', 'Uma Ordem já foi gerada para o orçamento atual, verifique os dados e tente novamente!');
        }
        
        $action = $request->input('action');
        if ($action === 'save') {
            return $this->addFeeService($request);
        } elseif ($action === 'delete') {
            return $this->removeServicePrice($request);
        } elseif ($action === 'clean') {
            return $this->cleanPriceService($request);
        }
    
        return back()->with('infor', 'Nenhuma função foi necessária!');
    }

    public function addFeeService(Request $request) {

        $request->validate([
            'price_id'          => 'required|exists:prices,id',
            'price_service_id'  => 'required|exists:price_services,id',
            'fees.*.fee_id'     => 'required|exists:service_fees,id',
        ]);
        
    
        foreach ($request->fees as $feeData) {

            $value      = $this->formatValue($feeData['value'] ?? 0);
            $discount   = $this->formatValue($feeData['discount'] ?? 0);

            PriceServiceFee::updateOrCreate(
                [
                    'price_id'          => $request->price_id,
                    'price_service_id'  => $request->price_service_id,
                    'fee_id'            => $feeData['fee_id'],
                ],
                [
                    'value'     => $value,
                    'discount'  => $discount,
                ]
            );
        }
    
        return back()->with('success', 'Todas as taxas foram salvas com sucesso!');
    }

    public function removeServicePrice(Request $request) {
        
        $validated = $request->validate([
            'price_service_id' => 'required|exists:price_services,id',
        ], [
            'price_service_id.required' => 'Serviço não encontrado.',
            'price_service_id.exists'   => 'Serviço não encontrado.',
        ]);

        $priceService = PriceService::where('id', $validated['price_service_id'])->first();
        if ($priceService) {
            $priceService->delete();
            return redirect()->back()->with('success', 'Serviço removido com sucesso!');
        }

        return redirect()->back()->with('infor', 'Serviço não encontrado!');
    }

    public function cleanPriceService(Request $request) {
        
        $validated = $request->validate([
            'price_id' => 'required|exists:prices,id',
        ], [
            'price_id.required' => 'Cotação indisponível ou não existe!',
            'price_id.exists'   => 'Cotação indisponível ou não existe!',
        ]);

        $services = PriceService::where('price_id', $validated['price_id'])->get();
        if ($services->isEmpty()) {
            return redirect()->back()->with('infor', 'Não há serviços associados!');
        }

        if (PriceService::where('price_id', $validated['price_id'])->delete() === $services->count()) {
            return redirect()->back()->with('success', 'Serviços removidos com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir todos os serviços, verifque os dados e tente novamente!');
    }

    private function formatValue($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);
    
        return number_format($valorFloat, 2, '.', '');
    }
}
