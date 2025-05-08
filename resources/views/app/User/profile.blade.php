@extends('app.layout')
@section('content')
    <div class="col-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link active waves-effect waves-light" href="javascript:void(0);"><i class="ri-group-line me-2"></i>Minha Conta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="{{ route('security') }}"><i class="ri-lock-line me-2"></i>Segurança</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="pages-account-settings-billing.html"><i class="ri-bookmark-line me-2"></i>Pagamentos &amp; Planos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="pages-account-settings-connections.html"><i class="ri-link-m me-2"></i>Integrações</a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="col-12 col-sm-12 col-md-12 col-lg-7 mb-2">
        <div class="card">         
            <div class="card-body pt-0">
                <form id="avatarForm" action="{{ route('updated-user') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4 d-flex justify-content-center align-items-center flex-column text-center">
                            @csrf
                            <img src="{{ Auth::user()->photo ? Storage::url(Auth::user()->photo) : asset('assets/img/avatars/1.png') }}" alt="Perfil de {{ Auth::user()->name }}" class="d-block w-px-100 h-px-100 rounded-4" id="uploadedAvatar" style="cursor: pointer;"/>
                            <input type="file" id="avatarInput" name="photo" accept="image/*" class="d-none">
                        </div>
                        <div class="col-12 col-sm-12 col-md-8 col-lg-8 g-4">
                            <div class="form-floating form-floating-outline mb-2">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Nome / Nome Fantasia" value="{{ Auth::user()->name }}"/>
                                <label for="name">Nome / Nome Fantasia</label>
                            </div>

                            <div class="form-floating form-floating-outline">
                                <textarea class="form-control h-px-100" name="bio" id="bio" placeholder="Bio">{{ Auth::user()->bio }}</textarea>
                                <label for="bio">BIO</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="{{ Auth::user()->email }}"/>
                                <label for="email">E-mail</label>
                            </div>
                        </div>
                        <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="phone" id="phone" class="form-control phone" placeholder="Telefone" oninput="maskPhone(this)" value="{{ Auth::user()->phone }}"/>
                                <label for="phone">Telefone</label>
                            </div>
                        </div>
                        <div class="col-6 col-sm-12 col-md-12 col-lg-12 mb-2">
                            <div class="form-floating form-floating-outline mb-2">
                                <textarea class="form-control h-px-50" name="address" id="address" placeholder="Endereço">{{ Auth::user()->address }}</textarea>
                                <label for="address">Endereço</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-12 offset-md-6 col-md-6 offset-lg-6 col-lg-6 mb-2 d-grid">
                            <button type="submit" class="btn btn-outline-success">Atualizar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-5 mb-2">
        <div class="card">
            <h5 class="card-header mb-1">Excluir Conta</h5>
            <div class="card-body">
                <div class="mb-6 col-12 mb-0">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading mb-1">Tem certeza de que deseja excluir sua conta?</h6>
                        <p class="mb-0">Ao excluir sua conta, os dados e informações serão permanentemente excluidos após 30 dias de inatividade!</p>
                    </div>
                </div>
                <form id="formAccountDeactivation" action="{{ route('deleted-user') }}" method="POST" class="confirm">
                    @csrf
                    <div class="form-check mb-6">
                        <input class="form-check-input" type="checkbox" name="confirm" id="confirm">
                        <label class="form-check-label" for="confirm">Confirmo a desativação da minha conta</label>
                    </div>
                    <button type="submit" class="btn btn-danger deactivate-account waves-effect waves-light"> Desativar minha conta </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('uploadedAvatar').addEventListener('click', function () {
            document.getElementById('avatarInput').click();
        });
    
        document.getElementById('avatarInput').addEventListener('change', function () {
            if (this.files.length > 0) {
                document.getElementById('avatarForm').submit();
            }
        });
    </script>
@endsection