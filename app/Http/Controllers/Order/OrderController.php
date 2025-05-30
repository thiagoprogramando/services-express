<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Price;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller {

    public function index(Request $request) {

        $baseQuery = Order::where('user_id', Auth::id());
        if ($request->filled('price_id')) {
            $baseQuery->where('price_id', $request->input('price_id'));
        }

        if ($request->filled('template_id')) {
            $baseQuery->where('template_id', $request->input('template_id'));
        }

        $approvedQuery = clone $baseQuery;
        $pendingQuery  = clone $baseQuery;

        return view('app.Order.list-orders', [
            'orders'        => $baseQuery->orderBy('created_at', 'asc')->paginate(30),
            'approvedTotal' => $approvedQuery->where('status', 1)->sum('value'),
            'pendingTotal'  => $pendingQuery->where('status', '!=', 1)->sum('value'),
        ]);
    }
    
    public function store(Request $request) {

        $validated = $request->validate([
            'price_id' => 'required|exists:prices,id',
        ], [
            'price_id.required' => 'Cotação indisponível ou não existe!',
            'price_id.exists'   => 'Cotação indisponível ou não existe!',
        ]);

        $price = Price::where('id', $request->price_id)->where('user_id', Auth::user()->id)->first();
        if (!$price) {
            return redirect()->back()->with('infor', 'Não foi possível localizar o Orçamento, verifique os dados e tente novamente!');
        }

        $order = Order::where('price_id', $price->id)->where('user_id', Auth::user()->id)->first();
        if ($order) {
            return redirect()->back()->with('infor', 'Uma Ordem já foi gerada para o orçamento atual, verifique os dados e tente novamente!');
        }

        $order              = new Order();
        $order->uuid        = Str::uuid();
        $order->user_id     = Auth::user()->id;
        $order->price_id    = $price->id;
        $order->status      = $request->status;

        $value = $this->formatValue($request->value) - $this->formatValue($request->discount);
        $order->value       = $value;
        $order->discount    = $this->formatValue($request->discount);
    
        $template = Template::where('id', $request->template_id)->where('user_id', Auth::user()->id)->first();
        if ($template) {
            $order->template_id = $template->id;
            $order->header      = $template->header;
            $order->footer      = $template->footer;
        }

        if ($order->save()) {
            if ($request->status == 1 ) {
                $this->approvedPrice($order->price_id);
            }
            return redirect()->back()->with('success', 'Orçamento gerado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não foi possível gerar o Orçamento, verifique os dados e tente novamente!');
        }
    }

    public function updated(Request $request) {

        $order = Order::where('uuid', $request->uuid)->first();
        if ($order) {

            $template = Template::where('id', $request->template_id)->where('user_id', Auth::user()->id)->first();
            if ($template && ($template->id !== $order->template_id)) {
                $order->template_id = $template->id;
                $order->header      = $template->header;
                $order->footer      = $template->footer;
            } else {
                $order->header = $request->header;
                $order->footer = $request->footer;
            }
            
            $order->status   = $request->status;
            $order->value    = $this->formatValue($request->value);
            $order->discount = $this->formatValue($request->discount);
            if ($order->save()) {
                if ($request->status == 1 ) {
                    $this->approvedPrice($order->price_id);
                }
                return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
            }

            return redirect()->back()->with('infor', 'Não foi possivel atualizar os dados da Ordem!');
        }

        return redirect()->back()->with('error', 'Ordem não localizada na base, verifique os dados e tente novamente!');
    }

    public function deleted(Request $request) {

        $order = Order::where('uuid', $request->uuid)->first();
        if ($order && $order->delete()) {
            return redirect()->back()->with('success', 'Ordem excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao excluir a Ordem, verifique os dados e tente novamente.');
    }

    public function action(Request $request) {
        
        switch ($request->action) {
            case 'updated':
                return $this->updated($request);
                break;
            case 'deleted':
                return $this->deleted($request);
                break;
            default:
                return back()->with('infor', 'Nenhuma função foi necessária!');
                break;
        }

        return back()->with('infor', 'Nenhuma função foi necessária!');
    }

    private function approvedPrice($price) {

        $price = Price::find($price);
        if (!$price) {
            return false;
        }

        $price->status = 1;
        return $price->save();
    }

    private function formatValue($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);
    
        return number_format($valorFloat, 2, '.', '');
    }

}
