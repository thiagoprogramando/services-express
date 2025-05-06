<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller {
    
    public function index() {
        return view('app.User.profile');
    }

    public function security() {
        return view('app.User.security');
    }

    public function store(Request $request) {
       
    }

    public function update(Request $request) {
        
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => 'string|max:255',
            'email'   => 'email|max:255|unique:users,email,' . $user->id,
            'phone'   => 'nullable|string|max:20',
            'bio'     => 'nullable|string|max:500',
            'address' => 'nullable|string|max:500',
            'photo'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'name.max'         => 'O nome não pode ter mais que 255 caracteres.',
            'email.email'      => 'Informe um e-mail válido.',
            'email.unique'     => 'Este e-mail já está em uso.',
            'phone.max'        => 'O telefone não pode ter mais que 20 caracteres.',
            'bio.max'          => 'A bio deve ter no máximo 500 caracteres.',
            'address.max'      => 'O endereço deve ter no máximo 500 caracteres.',
            'photo.image'      => 'O arquivo enviado deve ser uma imagem.',
            'photo.mimes'      => 'A imagem deve estar nos formatos: jpeg, png, jpg, gif ou svg.',
            'photo.max'        => 'A imagem não pode ter mais que 2MB.',
        ]);

        if ($request->has('password_old')) {

            if (!Hash::check($request->password_old, $user->password)) {
                return redirect()->back()->with('infor', 'Senha atual inválida!');
            }

            if ($request->password != $request->password_confirmed) {
                return redirect()->back()->with('infor', 'As senhas não conferem!');
            }

            $validated['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {

            if ($user->photo && Storage::exists($user->photo)) {
                Storage::delete($user->photo);
            }

            $validated['photo'] = $request->file('photo')->storeAs(
                'users/photo',
                Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension(),
                'public'
            );
        }

        if ($user->update($validated)) {
            return redirect()->back()->with('success', 'Dados atualizado com sucesso!');
        }

        return redirect()->back()->with('infor', 'Não foi possível atualizar os Dados, verifique os dados e tente novamente!');
    }

    public function delete(Request $request) {
        
        $request->validate([
            'password' => 'required|string',
            'confirm'  => 'accepted',
        ], [
            'password.required' => 'A senha é obrigatória!',
            'confirm.accepted'  => 'Você deve confirmar a desativação da conta!',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Senha inválida!']);
        }

        if ($user->delete()) {
            Auth::logout();
            return redirect()->route('login')->with('success', 'Conta excluídas com sucesso!');
        } else {
            return back()->withErrors(['error' => 'Não foi possível excluir a conta, verifique pendências e tente novamente!']);
        }

        return back()->withErrors(['error' => 'Não foi possível excluir a conta, verifique pendências e tente novamente!']);
    }
}
