<?php

namespace App\Http\Controllers\People;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClientController extends Controller {
    
    public function index(Request $request) {

        $query = Client::where('user_id', Auth::user()->id)->orderBy('name', 'asc');

        if (!empty($request->input('name'))) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if (!empty($request->input('cpfcnpj'))) {
            $query->where('cpfcnpj', $request->input('cpfcnpj'));
        }
        if (!empty($request->input('email'))) {
            $query->where('email', $request->input('email'));
        }
        if (!empty($request->input('phone'))) {
            $query->where('phone', $request->input('phone'));
        }
        if (!empty($request->input('postal_code'))) {
            $query->where('postal_code', $request->input('postal_code'));
        }
        if (!empty($request->input('city'))) {
            $query->where('city', $request->input('city'));
        }
        if (!empty($request->input('province'))) {
            $query->where('province', $request->input('province'));
        }

        return view('app.People.list-clients', [
            'clients' => $query->paginate(30),
        ]);
    }

    public function store(Request $request) {

        $validated = $request->validate([
            'name'          => 'required|max:255',
            'cpfcnpj'       => 'required|max:255',
            'email'         => 'email|max:255',
            'phone'         => 'max:255',
            'notes'         => 'nullable|max:16000',
            'description'   => 'nullable|max:16000',
        ], [
            'name.required'     => 'O campo "Nome" é obrigatório.',
            'name.max'          => 'O campo "Nome" não pode exceder 255 caracteres.',
            'cpfcnpj.required'  => 'O campo "CPF/CNPJ" é obrigatório.',
            'cpfcnpj.max'       => 'O campo "CPF/CNPJ" não pode exceder 255 caracteres.',
            'email.email'       => 'O campo "E-mail" deve ser um endereço de e-mail válido.',
            'email.max'         => 'O campo "E-mail" não pode exceder 255 caracteres.',
            'phone.max'         => 'O campo "Telefone" não pode exceder 255 caracteres.',
            'notes.max'         => 'O campo "Notas" não pode exceder 16.000 caracteres.',
            'description.max'   => 'O campo "Descrição" não pode exceder 16.000 caracteres.',
        ]);

        $client              = new Client();
        $client->uuid        = Str::uuid();
        $client->user_id     = Auth::user()->id;
        $client->name        = $request->input('name');
        $client->email       = $request->input('email');
        $client->cpfcnpj     = preg_replace('/[^0-9]/', '', $request->cpfcnpj);
        $client->phone       = preg_replace('/[^0-9]/', '', $request->phone);
        $client->postal_code = $request->input('postal_code');
        $client->num         = $request->input('num');
        $client->address     = $request->input('address');
        $client->city        = $request->input('city');
        $client->province    = $request->input('province');
        $client->description = $request->input('description');
        $client->notes       = $request->input('notes');
        
        if($client->save()) {
            return redirect()->back()->with('success', 'Cliente cadastrado com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível cadastrar o cliente, verifique os dados e tente novamente!');
    }

    public function edit(Request $request) {

        $client = Client::where('user_id', Auth::user()->id)->where('uuid', $request->input('uuid'))->first();
        if (!$client) {
            return redirect()->back()->with('infor', 'Cliente não localizado!');
        }

        if ($request->filled('name')) {
            $client->name = $request->input('name');
        }
        if ($request->filled('email')) {
            $client->email = $request->input('email');
        }
        if ($request->filled('cpfcnpj')) {
            $client->cpfcnpj = preg_replace('/[^0-9]/', '', $request->cpfcnpj);
        }
        if ($request->filled('phone')) {
            $client->phone = preg_replace('/[^0-9]/', '', $request->phone);
        }
        if ($request->filled('postal_code')) {
            $client->postal_code = $request->input('postal_code');
        }
        if ($request->filled('num')) {
            $client->num = $request->input('num');
        }
        if ($request->filled('address')) {
            $client->address = $request->input('address');
        }
        if ($request->filled('city')) {
            $client->city = $request->input('city');
        }
        if ($request->filled('province')) {
            $client->province = $request->input('province');
        }
        if ($request->filled('description')) {
            $client->description = $request->input('description');
        }
        if ($request->filled('notes')) {
            $client->notes = $request->input('notes');
        }

        if ($client->save()) {
            return redirect()->back()->with('success', 'Cliente atualizado com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível atualizar o cliente, verifique os dados e tente novamente!');
    }

    public function delete(Request $request) {

        $client = Client::where('user_id', Auth::user()->id)->where('uuid', $request->input('uuid'))->first();
        if ($client && $client->delete()) {
            return redirect()->back()->with('success', 'Cliente excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Não foi possível excluir o cliente, verifique os dados e tente novamente!');
    }
}
