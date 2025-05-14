@extends('app.layout')
@section('content')

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card mb-6">
            <div class="card-header p-0">
                <div class="nav-align-top">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">
                                Cabeçalho
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false" tabindex="-1">
                                Rodapé
                            </button>
                        </li>
                        <span class="tab-slider" style="left: 0px; width: 90.375px; bottom: 0px;"></span>
                    </ul>
                </div>
            </div>
            <div class="card-body pt-5">
                <form action="{{ route('updated-template') }}" method="POST" class="tab-content p-0">
                    @csrf
                    <input type="hidden" name="uuid" value="{{ $template->uuid }}">
                    <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                        <div class="alert alert-outline-primary" role="alert">
                            Os cabeçalhos aparecem na parte superior do documento.
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                <div class="form-floating form-floating-outline mb-2">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="ex: Template financeiro" value="{{ $template->name }}"/>
                                    <label for="name">Título</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating form-floating-outline">
                                    <div class="select2-primary">
                                        <select name="status" id="status" class="select2 form-select">
                                            <option value="1" @selected($template->status === 1)>Disponível</option>
                                            <option value="0" @selected($template->status !== 1)>Indisponível</option>
                                        </select>
                                    </div>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating form-floating-outline mb-2">
                            <textarea class="form-control" name="header">{{ $template->header }}</textarea>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                        <div class="alert alert-outline-primary" role="alert">
                            Os rodapés aparecem na parte inferior do documento.
                        </div>
                        <div class="form-floating form-floating-outline mb-2">
                            <textarea class="form-control" name="footer">{{ $template->footer }}</textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">
                                Salvar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.tiny.cloud/1/tgezwiu6jalnw1mma8qnoanlxhumuabgmtavb8vap7357t22/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
        });
    </script>
@endsection