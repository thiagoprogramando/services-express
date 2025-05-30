@extends('app.layout')
@section('content')
    <div class="col-md-12 offset-xxl-2 col-xxl-8">
        <div class="row align-items-start">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-5">
                <div class="card bg-light">
                    <div class="d-flex align-items-end row">
                        <div class="col-md-6 order-2 order-md-1">
                            <div class="card-body">
                                <h4 class="card-title mb-4">Bem-Vindo(a) <span class="fw-bold">{{ Auth::user()->maskedName() }}!</span> 🎉</h4>
                                <p class="mb-0">"Somos o que repetidamente fazemos. A excelência, portanto, não é um feito, mas um hábito." <br><small>– Aristóteles</small></p>
                                {{-- <a href="javascript:;" class="btn btn-dark mt-3">Minha Agenda</a> --}}
                            </div>
                        </div>
                        <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                            <div class="card-body pb-0 px-0 pt-2">
                                <img src="{{ Auth::user()->photo ? Storage::url(Auth::user()->photo) : asset('assets/img/avatars/man.png') }}" height="156" class="scaleX-n1-rtl m-2" alt="Perfil">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-2">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">Visão Geral</h5>
                        </div>
                        <a href="{{ route('list-orders') }}" class="mb-0 card-subtitle">ORDENS</a>
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="height: 300px;">
                        <canvas id="ordersChart" data-approved="{{ $approvedCount }}" data-pending="{{ $pendingCount }}" ></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-4 col-lg-4 mb-2">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Últimas cotações</h5>
                        <div class="dropdown">
                            <button class="btn btn-text-secondary rounded-pill text-muted border-0 p-1 waves-effect waves-light" type="button" id="socialNetworkList" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-more-2-line ri-20px"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="socialNetworkList">
                            <a class="dropdown-item waves-effect" href="javascript:void(0);">Last 28 Days</a>
                            <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Month</a>
                            <a class="dropdown-item waves-effect" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-7">
                            <div class="d-flex align-items-center">
                                <h4 class="mb-0">R$ {{ number_format($prices->sum('total_value'), 2, ',', '.') }}</h4>
                            </div>
                            <p class="mb-0">TOTAL EM COTAÇÕES</p>
                        </div>
                        <ul class="p-0 m-0">
                            @foreach ($prices as $price)
                                <li class="d-flex align-items-center mb-4">
                                    <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                        <a href="{{ route('view-price', ['uuid' => $price->uuid]) }}">
                                            <div class="me-2">
                                                <h6 class="mb-1">{{ $price->client->name }}</h6>
                                                <p class="mb-0 text-success">{{ $price->notes ?? 'R$ '.number_format($price->getTotalValueAttribute(), 2, ',', '.') }}</p>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

@endsection