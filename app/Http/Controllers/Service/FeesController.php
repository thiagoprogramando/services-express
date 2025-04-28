<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Fees;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FeesController extends Controller {

    public function store(Request $request) {

        $validated = $request->validate([
            'name'          => 'required|max:255',
            'description'   => 'nullable|max:16000',
        ], [
            'name.required'     => 'O campo "Nome" é obrigatório.',
            'name.max'          => 'O campo "Nome" não pode exceder 255 caracteres.',
            'description.max'   => 'O campo "Descrição" não pode exceder 16.000 caracteres.',
        ]);

        $fee                = new Fees();
        $fee->user_id       = Auth::user()->id;
        $fee->service_id    = $request->service_id;
        $fee->name          = $request->name;
        $fee->description   = $request->description;
        if ($request->has('value')) {
            $fee->value = $this->formatValue($request->value);
        }
        if ($request->has('value_min')) {
            $fee->value_min = $this->formatValue($request->value_min);
        }
        if ($request->has('value_max')) {
            $fee->value_max = $this->formatValue($request->value_max);
        }
        if($fee->save()) {
            return redirect()->back()->with('success', 'Taxa cadastrada com sucesso!');
        }
        
        return redirect()->back()->with('infor', 'Não foi possível cadastrar a Taxa, verifique os dados e tente novamente!');
    }

    public function edit(Request $request) {
        
        $fee = Fees::where('user_id', Auth::user()->id)->where('id', $request->input('id'))->first();
        if (!$fee) {
            return redirect()->back()->with('infor', 'Taxa não localizada!');
        }

        if ($request->has('name')) {
            $fee->name = $request->name;
        }
        if ($request->has('description')) {
            $fee->description = $request->description;
        }
        if ($request->has('value')) {
            $fee->value = $this->formatValue($request->value);
        }
        if ($request->has('value_min')) {
            $fee->value_min = $this->formatValue($request->value_min);
        }
        if ($request->has('value_max')) {
            $fee->value_max = $this->formatValue($request->value_max);
        }

        if($fee->save()) {
            return redirect()->back()->with('success', 'Taxa alterada com sucesso!');
        }

        return redirect()->back()->with('infor', 'Não foi possível alterar a Taxa, verifique os dados e tente novamente!');
    }

    public function delete(Request $request) {
        
        $fee = Fees::where('user_id', Auth::user()->id)->where('id', $request->input('id'))->first();
        if ($fee && $fee->delete()) {
            return redirect()->back()->with('success', 'Taxa excluída com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir a Taxa, verifique os dados e tente novamente!');
    }

    private function formatValue($valor) {
        
        $valor = preg_replace('/[^0-9,]/', '', $valor);
        $valor = str_replace(',', '.', $valor);
        $valorFloat = floatval($valor);
    
        return number_format($valorFloat, 2, '.', '');
    }
}
