@extends('layout')
    @section('content')
        <div class="position-relative">
            <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
                <div class="authentication-inner py-6">
                    <div class="card p-md-7 p-1">
                        <div class="app-brand justify-content-center mt-5 text-center">
                            <a href="" class="app-brand-link d-flex flex-column align-items-center gap-2">
                                <span>
                                    <img class="w-25" src="{{ asset('assets/img/favicon/favicon.ico') }}">
                                </span>
                                <span class="app-brand-text demo fw-semibold text-white mt-2">
                                    {{ env('APP_NAME') }}
                                </span>
                            </a>
                        </div>                        
        
                        <div class="card-body mt-1">
                            <h4 class="mb-1 text-white">Bem-vindo(a)! 👋</h4>
                            <p class="mb-5 text-white">Faça login na sua conta para ter acesso aos benefícios.</p>
            
                            <form id="formAuthentication" class="mb-5" action="{{ route('logon') }}" method="POST">
                                @csrf
                                <div class="form-floating form-floating-outline mb-5">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="E-mail:"/>
                                    <label for="email">E-mail</label>
                                </div>
                                <div class="mb-5">
                                    <div class="form-password-toggle">
                                        <div class="input-group input-group-merge">
                                            <div class="form-floating form-floating-outline">
                                                <input type="password" id="password" class="form-control" name="password" placeholder="Senha:" aria-describedby="password"/>
                                                <label for="password">Senha</label>
                                            </div>
                                            <span class="input-group-text cursor-pointer">
                                                <i class="ri-eye-off-line"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-5 d-flex justify-content-between mt-5">
                                    <div class="form-check mt-2">
                                        {{-- <input class="form-check-input" type="checkbox" id="remember-me" />
                                        <label class="form-check-label" for="remember-me"> Remember Me </label> --}}
                                    </div>
                                    <a href="{{ route('forgout') }}" class="float-end mb-1 mt-2 text-white">
                                        <span>Esqueceu a senha?</span>
                                    </a>
                                </div>
                                <div class="mb-5">
                                    <button class="btn btn-primary d-grid w-100" type="submit">Acessar</button>
                                </div>
                            </form>
            
                            <p class="text-center text-white">
                                <i>V <a href="#" target="_blank" class="text-white">0.0.1</a></i> 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

        