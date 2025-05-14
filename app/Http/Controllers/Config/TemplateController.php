<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;

use App\Models\Template;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TemplateController extends Controller {
    
    public function index(Request $request) {

        $query = Template::where('user_id', Auth::user()->id)->orderBy('name', 'asc');

        if (!empty($request->input('name'))) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        return view('app.Config.list-templates', [
            'templates' => $query->paginate(10),
        ]);
    }

    public function view($uuid) {

        $template = Template::where('uuid', $uuid)->first();
        if (!$template) {
            return redirect()->back()->with('error', 'Template não encontrado!');
        }

        return view('app.Config.view-template', [
            'template' => $template,
        ]);
    }

    public function createTemplate() {
        return view('app.Config.create-template');
    }

    public function store(Request $request) {
        
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'header'   => 'nullable|max:16000',
            'footer'   => 'nullable|max:16000',
        ], [
            'name.required' => 'Informe um Título para o Template.',
            'name.max'      => 'O Título não pode exceder 255 caracteres.',
            'header.max'    => 'O Cabeçalho não pode exceder 16.000 caracteres.',
            'footer.max'    => 'O Rodapé não pode exceder 16.000 caracteres.',
        ]);

        $template           = new Template();
        $template->uuid     = Str::uuid();
        $template->user_id  = Auth::user()->id;
        $template->name     = $request->name;
        $template->header   = $request->header;
        $template->footer   = $request->footer;
        $template->status   = $request->status;
        if ($template->save()) {
            return redirect()->route('view-template', ['uuid' => $template->uuid])->with('success', 'Template criado com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao criar o Template, verifique os dados e tente novamente.');
    }

    public function edit(Request $request) {
        
        $validated = $request->validate([
            'name'     => 'required|max:255',
            'header'   => 'nullable|max:16000',
            'footer'   => 'nullable|max:16000',
        ], [
            'name.required' => 'Informe um Título para o Template.',
            'name.max'      => 'O Título não pode exceder 255 caracteres.',
            'header.max'    => 'O Cabeçalho não pode exceder 16.000 caracteres.',
            'footer.max'    => 'O Rodapé não pode exceder 16.000 caracteres.',
        ]);

        $template = Template::where('uuid', $request->uuid)->first();
        if (!$template) {
            return redirect()->back()->with('error', 'Template não encontrado!');
        }

        if (!empty($request->name)) {
            $template->name     = $request->name;
        }
        if (!empty($request->header)) {
            $template->header   = $request->header;
        }
        if (!empty($request->footer)) {
            $template->footer   = $request->footer;
        }
        if (!empty($request->status)) {
            $template->status = $request->status;
        }
        
        if ($template->save()) {
            return redirect()->route('view-template', ['uuid' => $template->uuid])->with('success', 'Template atualizado com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao atualizar o Template, verifique os dados e tente novamente.');
    }

    public function delete(Request $request) {
        
        $template = Template::where('uuid', $request->uuid)->first();
        if ($template && $template->delete()) {
            return redirect()->back()->with('success', 'Template excluído com sucesso!');
        }

        return redirect()->back()->with('error', 'Erro ao excluir o Template, verifique os dados e tente novamente.');
    }
}
