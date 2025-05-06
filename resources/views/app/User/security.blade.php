@extends('app.layout')
@section('content')
    <div class="col-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0">
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="{{ route('profile') }}"><i class="ri-group-line me-2"></i>Minha Conta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active waves-effect waves-light" href="javascript:void(0);"><i class="ri-lock-line me-2"></i>Segurança</a>
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
    
    <div class="col-12 col-sm-12 col-md-7 col-lg-7 mb-2">
        <div class="card mb-6">         
            <div class="card-body pt-0">
                <form action="{{ route('updated-user') }}" method="POST" class="p-5">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-6 col-12 mb-0">
                                <div class="alert alert-warning">
                                    <h6 class="alert-heading mb-1">Ao alterar sua senha de acesso, armazene-a em um local seguro!</h6>
                                    <p class="mb-0">Você pode utilizar apps, notas e arquivos seguros para consultar sua senha sempre que precisar.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="password" name="password_old" id="password_old" class="form-control" placeholder="Senha Atual"/>
                                <label for="password_old">Senha Atual</label>
                            </div>
                        </div>
                        <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="password" id="password" class="form-control" placeholder="Nova Senha:"/>
                                <label for="password">Nova Senha:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                            <div class="form-floating form-floating-outline">
                                <input type="password" name="password_confirmed" id="password_confirmed" class="form-control" placeholder="Confirme a Senha:"/>
                                <label for="password_confirmed">Confirme a Senha:</label>
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
@endsection