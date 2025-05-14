@extends('app.layout')
@section('content')

    <div class="col-12">
        <label class="kanban-add-board-btn" for="kanban-add-board-input" data-bs-toggle="modal" data-bs-target="#createdModal">
            <i class="ri-add-line"></i>
            <span class="align-middle">Adicionar Serviço</span>
        </label>

        <div class="modal fade" id="createdModal" tabindex="-1" aria-hidden="true">
            <form action="{{ route('created-service') }}" method="POST">
                @csrf
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLabel1">Dados do Serviço</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Produto"/>
                                        <label for="name">Produto</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="value" id="value" class="form-control money" placeholder="Valor" oninput="maskValue(this)"/>
                                        <label for="value">Valor</label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="value_cost" id="value_cost" class="form-control money" placeholder="Custo" oninput="maskValue(this)"/>
                                        <label for="value_cost">Custo</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a class="me-1" data-bs-toggle="collapse" href="#collapseNotes" role="button" aria-expanded="false" aria-controls="collapseNotes"> Extras </a>
                                </div>
                                <div class="col-12">
                                    <div class="collapse" id="collapseNotes">
                                        <div class="form-floating form-floating-outline mb-2">
                                            <textarea class="form-control h-px-100" name="description" id="description" placeholder="Notas"></textarea>
                                            <label for="description">Descrição</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer btn-group">
                            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"> Fechar </button>
                            <button type="submit" class="btn btn-success">Enviar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-8 col-lg-6">
        <div class="card h-100">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5 class="mb-1">Visão Geral</h5>
                </div>
                <div class="d-flex align-items-center card-subtitle">
                    <div class="me-2">Total {{ $services->count() }} serviços</div>
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
                        <h5 class="mb-0">{{ $services->count() }}</h5>
                        <p class="mb-0">Serviços</p>
                    </div>
                </div>
                @php
                    $doneTotal = 0;
                    $quotedTotal = 0;

                    foreach ($services as $service) {
                        foreach ($service->priceServices as $priceService) {
                            if ($priceService->price && $priceService->price->status == 1) {
                                $doneTotal += $priceService->value;
                            } elseif ($priceService->price && $priceService->price->status == 2) {
                                $quotedTotal += $priceService->value;
                            }
                        }
                    }
                @endphp
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="ri-shake-hands-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">R$ {{ number_format($doneTotal, 2, ',', '.') }}</h5>
                        <p class="mb-0">Serviços Feitos</p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-warning rounded">
                            <i class="ri-arrow-left-right-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">R$ {{ number_format($quotedTotal, 2, ',', '.') }}</h5>
                        <p class="mb-0">Serviços Orçados</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <h5 class="card-header">Serviços</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Detalhes</th>
                            <th>Valores</th>
                            <th class="text-center">T. Ordens</th>
                            <th class="text-center">T. Cotações</th>
                            <th class="text-center">Opções</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($services as $service)
                            <tr>
                                <td>
                                    <i class="ri-bookmark-line ri-22px text-success mr-4"></i>
                                    <span class="fw-medium">{{ $service->name }}</span>
                                    <br>
                                    <span class="badge bg-label-info m-1">{{ \Illuminate\Support\Str::limit($service->description, 100) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-label-info m-1">Valor: R$ {{ number_format($service->value, 2, ',', '.') }}</span> <br>
                                    <span class="badge bg-label-danger m-1">Custo: R$ {{ number_format($service->value_cost, 2, ',', '.') }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium">10</span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium">{{ $service->priceServices->count() }}</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('deleted-service') }}" method="POST" class="demo-inline-spacing delete">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $service->id }}">
                                        <button type="submit" class="btn btn-icon btn-outline-danger waves-effect" title="Deletar">
                                            <span class="tf-icons ri-delete-bin-line ri-22px"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-success waves-effect" data-bs-toggle="modal" data-bs-target="#updatedModal{{ $service->id }}" title="Editar">
                                            <span class="tf-icons ri-eye-line ri-22px"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-dark waves-effect" data-bs-toggle="modal" data-bs-target="#FeesModal{{ $service->id }}" title="Taxas">
                                            <span class="tf-icons ri-money-dollar-box-line ri-22px"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="updatedModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                                <form action="{{ route('updated-service') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $service->id }}">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="exampleModalLabel1">Dados do Serviço</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="name" id="name" class="form-control" placeholder="Produto" value="{{ $service->name }}"/>
                                                            <label for="name">Produto</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="value" id="value" class="form-control money" placeholder="Valor" value="{{ $service->value }}" oninput="maskValue(this)"/>
                                                            <label for="value">Valor</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="value_cost" id="value_cost" class="form-control money" placeholder="Custo" value="{{ $service->value_cost }}" oninput="maskValue(this)"/>
                                                            <label for="value_cost">Custo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <a class="me-1" data-bs-toggle="collapse" href="#collapseNotes" role="button" aria-expanded="true" aria-controls="collapseNotes"> Extras </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="collapse show" id="collapseNotes">
                                                            <div class="form-floating form-floating-outline mb-2">
                                                                <textarea class="form-control h-px-100" name="description" id="description" placeholder="Notas">{{ $service->description }}</textarea>
                                                                <label for="description">Descrição</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12 text-center">
                                                        <a class="me-1" data-bs-toggle="collapse" href="#collapseFees" role="button" aria-expanded="false" aria-controls="collapseFees"> Taxas </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="collapse" id="collapseFees">
                                                            <div class="tab-pane fade show active" id="navs-orders-id" role="tabpanel">
                                                                <label class="kanban-add-board-btn mb-3" for="kanban-add-board-input" data-bs-toggle="modal" data-bs-target="#createdFeesModal{{ $service->id }}">
                                                                    <i class="ri-add-line"></i>
                                                                    <span class="align-middle">Nova Taxa</span>
                                                                </label>

                                                                <ul class="p-0 m-0">
                                                                    @foreach ($service->fees as $fee)
                                                                        <li class="d-flex align-items-center mb-4">
                                                                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                                <div class="me-2">
                                                                                    <h6 class="mb-1">{{ $fee->name }}</h6>
                                                                                    <p class="mb-0">{{ \Illuminate\Support\Str::limit($fee->description, 100) }}</p>
                                                                                </div>
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="h6 mb-0">R$ {{ number_format($fee->value, 2, ',', '.') }}</span>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer btn-group">
                                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"> Fechar </button>
                                                <button type="submit" class="btn btn-success">Enviar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="modal fade" id="FeesModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="exampleModalLabel1">Dados da Taxa</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <label class="kanban-add-board-btn mb-3" for="kanban-add-board-input" data-bs-toggle="modal" data-bs-target="#createdFeesModal{{ $service->id }}">
                                                        <i class="ri-add-line"></i>
                                                        <span class="align-middle">Nova Taxa</span>
                                                    </label>

                                                    <ul class="p-0 m-0">
                                                        @foreach ($service->fees as $fee)
                                                            <li class="d-flex align-items-center mb-4">
                                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                                    <div class="me-2">
                                                                        <h6 class="mb-1">{{ $fee->name }}</h6>
                                                                        <p class="mb-0">{{ \Illuminate\Support\Str::limit($fee->description, 100) }}</p>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="h6 mb-0">R$ {{ number_format($fee->value, 2, ',', '.') }}</span>
                                                                    </div>
                                                                    <div class="d-flex align-items-center">
                                                                        <form action="{{ route('deleted-fee') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="id" value="{{ $fee->id }}">
                                                                            <div class="btn-group">
                                                                                <button type="submit" class="btn btn-icon btn-outline-dark waves-effect" title="Deletar">
                                                                                    <span class="tf-icons ri-delete-bin-line ri-22px"></span>
                                                                                </button>
                                                                                <button type="button" class="btn btn-icon btn-outline-dark waves-effect" data-bs-toggle="collapse" href="#collapseFees{{ $fee->id }}" role="button" aria-expanded="true" aria-controls="collapseFees{{ $fee->id }}" title="Editar">
                                                                                    <span class="tf-icons ri-eye-line ri-22px"></span>
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <div class="collapse" id="collapseFees{{ $fee->id }}">
                                                                <div class="card p-3 border border-1 border-dark">
                                                                    <form action="{{ route('updated-fee') }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="id" value="{{ $fee->id }}">
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                                                <div class="form-floating form-floating-outline">
                                                                                    <input type="text" name="name" id="name" class="form-control" placeholder="Título da Taxa:" value="{{ $fee->name }}"/>
                                                                                    <label for="name">Título da Taxa:</label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                                                <div class="form-floating form-floating-outline">
                                                                                    <input type="text" name="value" id="value" class="form-control money" placeholder="Valor:" value="{{ $fee->value }}" oninput="maskValue(this)"/>
                                                                                    <label for="value">Valor:</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12 text-center">
                                                                                <a class="me-1" data-bs-toggle="collapse" href="#collapseExtras{{ $fee->id }}" role="button" aria-expanded="false" aria-controls="collapseExtras{{ $fee->id }}"> Extras </a>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="collapse" id="collapseExtras{{ $fee->id }}">
                                                                                    <div class="row">
                                                                                        <div class="col mb-2">
                                                                                            <div class="form-floating form-floating-outline">
                                                                                                <input type="text" name="value_min" id="value_min" class="form-control money" placeholder="Valor Mín" value="{{ $fee->value_min }}" oninput="maskValue(this)"/>
                                                                                                <label for="value_min">Valor Mín</label>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col mb-2">
                                                                                            <div class="form-floating form-floating-outline">
                                                                                                <input type="text" name="value_max" id="value_max" class="form-control money" placeholder="Valor Máx" value="{{ $fee->value_max }}" oninput="maskValue(this)"/>
                                                                                                <label for="value_max">Valor Máx</label>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-floating form-floating-outline mb-2">
                                                                                        <textarea class="form-control h-px-100" name="description" id="description" placeholder="Notas">{{ $fee->description }}</textarea>
                                                                                        <label for="description">Descrição</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12 btn-group mt-3">
                                                                                <button type="button" class="btn btn-outline-dark" data-bs-toggle="collapse" href="#collapseFees{{ $fee->id }}" role="button" aria-expanded="true" aria-controls="collapseFees{{ $fee->id }}"> Fechar </button>
                                                                                <button type="submit" class="btn btn-success">Enviar</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="createdFeesModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                                <form action="{{ route('created-fee') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="exampleModalLabel1">Dados da Taxa</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="name" id="name" class="form-control" placeholder="Título da Taxa:"/>
                                                            <label for="name">Título da Taxa:</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="value" id="value" class="form-control money" placeholder="Valor:" oninput="maskValue(this)"/>
                                                            <label for="value">Valor:</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <a class="me-1" data-bs-toggle="collapse" href="#collapseNotes" role="button" aria-expanded="false" aria-controls="collapseNotes"> Extras </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="collapse" id="collapseNotes">
                                                            <div class="row">
                                                                <div class="col mb-2">
                                                                    <div class="form-floating form-floating-outline">
                                                                        <input type="text" name="value_min" id="value_min" class="form-control money" placeholder="Valor Mín" oninput="maskValue(this)"/>
                                                                        <label for="value_min">Valor Mín</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col mb-2">
                                                                    <div class="form-floating form-floating-outline">
                                                                        <input type="text" name="value_max" id="value_max" class="form-control money" placeholder="Valor Máx" oninput="maskValue(this)"/>
                                                                        <label for="value_max">Valor Máx</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-floating form-floating-outline mb-2">
                                                                <textarea class="form-control h-px-100" name="description" id="description" placeholder="Notas"></textarea>
                                                                <label for="description">Descrição</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer btn-group">
                                                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"> Fechar </button>
                                                <button type="submit" class="btn btn-success">Enviar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection