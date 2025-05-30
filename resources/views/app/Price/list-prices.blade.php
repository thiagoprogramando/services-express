@extends('app.layout')
@section('content')

    <div class="col-12">
        <label class="kanban-add-board-btn" for="kanban-add-board-input" data-bs-toggle="modal" data-bs-target="#createdModal">
            <i class="ri-add-line"></i>
            <span class="align-middle">Gerar Cotação</span>
        </label>

        <div class="modal fade" id="createdModal" tabindex="-1" aria-hidden="true">
            <form action="{{ route('created-price') }}" method="POST">
                @csrf
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLabel1">Dados da Cotação</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <div class="select2-primary">
                                            <select name="client_id" id="client_id" class="select2 form-select">
                                                <option value="Escolha um cliente:" selected>Escolha um cliente:</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label for="client_id">Cliente</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a class="me-1" data-bs-toggle="collapse" href="#collapseNotes" role="button" aria-expanded="false" aria-controls="collapseNotes"> Notas </a>
                                </div>
                                <div class="col-12">
                                    <div class="collapse" id="collapseNotes">
                                        <div class="form-floating form-floating-outline mb-2">
                                            <textarea class="form-control h-px-100" name="notes" id="notes" placeholder="Notas"></textarea>
                                            <label for="notes">Notas</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer btn-group">
                            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"> Fechar </button>
                            <button type="submit" class="btn btn-success">Avançar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-1">Visão Geral</h5>
                </div>
                <div class="d-flex align-items-center card-subtitle">
                    <div class="me-2">Total {{ $prices->count() }} Cotações</div>
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
                        <h5 class="mb-0">{{ $prices->count() }}</h5>
                        <p class="mb-0">Cotações</p>
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
            <h5 class="card-header">Cotações</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Detalhes</th>
                            <th class="text-center">Valor</th>
                            <th class="text-center">T. Serviços</th>
                            <th class="text-center">T. Taxas</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($prices as $price)
                            <tr>
                                <td>
                                    <i class="ri-account-pin-circle-line ri-22px text-primary mr-4"></i>
                                    <span class="fw-medium">{{ $price->client->name }}</span><br>
                                    <span class="badge bg-label-info m-1" title="{{ $price->notes }}">
                                        {{ \Illuminate\Support\Str::limit($price->notes, 30) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-label-success m-1">
                                        R$ {{ number_format($price->total_value, 2, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium">{{ $price->total_services }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium">{{ $price->total_fees }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('deleted-price') }}" method="POST" class="demo-inline-spacing delete">
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ $price->uuid }}">
                                        <button type="submit" class="btn btn-icon btn-outline-danger waves-effect" title="Deletar">
                                            <span class="tf-icons ri-delete-bin-line ri-22px"></span>
                                        </button>
                                        <a href="{{ route('view-price', ['uuid' => $price->uuid]) }}" class="btn btn-icon btn-outline-success waves-effect" title="Acessar">
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