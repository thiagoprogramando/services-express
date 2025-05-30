@extends('app.layout')
@section('content')

    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-1">Visão Geral</h5>
                </div>
                <div class="d-flex align-items-center card-subtitle">
                    <div class="me-2">Total {{ $orders->count() }} Ordens</div>
                </div>
            </div>
            <div class="card-body d-flex justify-content-between flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-info rounded">
                        <i class="ri-pie-chart-2-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">{{ $orders->count() }}</h5>
                        <p class="mb-0">Ordens</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="ri-shake-hands-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">R$ {{ number_format($approvedTotal, 2, ',', '.') }}</h5>
                        <p class="mb-0">Aprovadas</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="ri-arrow-left-right-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">R$ {{ number_format($pendingTotal, 2, ',', '.') }}</h5>
                        <p class="mb-0">Pendentes</p>
                    </div>
                </div>                
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <h5 class="card-header">Ordens</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Detalhes</th>
                            <th class="text-center">Valores</th>
                            <th>Serviços</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <i class="ri-price-tag-line ri-22px text-dark mr-4"></i>
                                    <span class="fw-medium">{{ $order->price->client->name }}</span><br>
                                    <a class="badge bg-label-info m-1" href="{{ route('view-price', ['uuid' => $order->price->uuid]) }}">
                                        Cotação Ref
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-label-success m-1">
                                        R$ {{ number_format($order->value, 2, ',', '.') }}
                                    </span>
                                    <span class="badge bg-label-danger m-1">
                                        R$ {{ number_format($order->discount, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    @foreach ($order->price->services as $key => $service)
                                        <span class="badge bg-label-info m-1">{{ $service->service->name }}</span>
                                        @if (($key + 1) % 2 == 0)
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium">{{ $order->labelStatus() }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('deleted-order') }}" method="POST" class="demo-inline-spacing delete">
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ $order->uuid }}">
                                        <button type="submit" class="btn btn-icon btn-outline-danger waves-effect" title="Deletar">
                                            <span class="tf-icons ri-delete-bin-line ri-22px"></span>
                                        </button>
                                        <a href="{{ route('view-price', ['uuid' => $order->price->uuid]) }}" class="btn btn-icon btn-outline-success waves-effect" title="Acessar">
                                            <span class="tf-icons ri-eye-line ri-22px"></span>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection