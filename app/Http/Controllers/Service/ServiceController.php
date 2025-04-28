<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller {
    
    public function index(Request $request) {

        $query = Service::where('user_id', Auth::user()->id)->orderBy('name', 'asc');

        if (!empty($request->input('name'))) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        return view('app.Service.list-services', [
            'services' => $query->paginate(30),
        ]);
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'name'          => 'required|max:255',
            'description'   => 'nullable|max:16000',
        ], [
            'name.required'     => 'O campo "Nome" é obrigatório.',
            'name.max'          => 'O campo "Nome" não pode exceder 255 caracteres.',
            'description.max'   => 'O campo "Descrição" não pode exceder 16.000 caracteres.',
        ]);

        $service                = new service();
        $service->user_id       = Auth::user()->id;
        $service->name          = $request->input('name');
        $service->description   = $request->input('description');
        $service->value         = $this->formatValue($request->value);
        $service->value_cost    = $this->formatValue($request->value_cost);
        if($service->save()) {
            return redirect()->back()->with('success', 'Serviço cadastrado com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível cadastrar o Serviço, verifique os dados e tente novamente!');
    }

    public function edit(Request $request) {

        $service = Service::where('user_id', Auth::user()->id)->where('id', $request->input('id'))->first();
        if (!$service) {
            return redirect()->back()->with('infor', 'Serviço não localizado!');
        }

        if ($request->filled('name')) {
            $service->name = $request->input('name');
        }
        if ($request->filled('description')) {
            $service->description = $request->input('description');
        }
        if ($request->filled('value')) {
            $service->value = $this->formatValue($request->value);
        }
        if ($request->filled('value_cost')) {
            $service->value_cost = $this->formatValue($request->value_cost);
        }
        if ($service->save()) {
            return redirect()->back()->with('success', 'Serviço atualizado com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível atualizar o Serviço, verifique os dados e tente novamente!');
    }

    public function delete(Request $request) {

        $service = Service::where('user_id', Auth::user()->id)->where('id', $request->input('id'))->first();
        if ($service && $service->delete()) {
            return redirect()->back()->with('success', 'Serviço excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir o Serviço, verifique os dados e tente novamente!');
    }

    private function formatValue($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);
    
        return number_format($valorFloat, 2, '.', '');
    }
}
