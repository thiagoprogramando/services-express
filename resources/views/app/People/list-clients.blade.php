@extends('app.layout')
@section('content')

    <div class="col-12">
        <label class="kanban-add-board-btn" for="kanban-add-board-input" data-bs-toggle="modal" data-bs-target="#createdModal">
            <i class="ri-add-line"></i>
            <span class="align-middle">Adicionar Cliente</span>
        </label>

        <div class="modal fade" id="createdModal" tabindex="-1" aria-hidden="true">
            <form action="{{ route('created-client') }}" method="POST">
                @csrf
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLabel1">Dados do Cliente</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nome / Nome Fantasia" required/>
                                        <label for="name">Nome / Nome Fantasia</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="E-mail"/>
                                        <label for="email">E-mail</label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="phone" id="phone" class="form-control" placeholder="Telefone" oninput="maskPhone(this)"/>
                                        <label for="phone">Telefone</label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="cpfcnpj" id="cpfcnpj" class="form-control" placeholder="CPF/CNPJ" oninput="maskCpfCnpj(this)"/>
                                        <label for="cpfcnpj">CPF/CNPJ</label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-4 col-lg-4 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="number" name="postal_code" id="postal_code" class="form-control" placeholder="CEP" onblur="consultAddress()"/>
                                        <label for="postal_code">CEP</label>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-2 col-lg-2 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="num" id="num" class="form-control" placeholder="N"/>
                                        <label for="num">N</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="address" id="address" class="form-control" placeholder="Endereço"/>
                                        <label for="address">Endereço</label>
                                    </div>
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="city" id="city" class="form-control" placeholder="Cidade"/>
                                        <label for="city">Cidade</label>
                                    </div>
                                </div>
                                <div class="col mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="province" id="province" class="form-control" placeholder="Estado"/>
                                        <label for="province">Estado</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <a class="me-1" data-bs-toggle="collapse" href="#collapseNotes" role="button" aria-expanded="false" aria-controls="collapseNotes"> Anotações </a>
                                </div>
                                <div class="col-12">
                                    <div class="collapse" id="collapseNotes">
                                        <div class="form-floating form-floating-outline mb-2">
                                            <textarea class="form-control h-px-50" name="description" id="description" placeholder="Descrição"></textarea>
                                            <label for="description">Descrição</label>
                                        </div>
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
            </div>
            <div class="card-body d-flex justify-content-between flex-wrap gap-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-info rounded">
                            <i class="ri-pie-chart-2-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">{{ $clients->count() }}</h5>
                        <p class="mb-0">Clientes</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-success rounded">
                            <i class="ri-user-2-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">{{ $clients->filter(fn($c) => strlen(preg_replace('/\D/', '', $c->cpfcnpj)) === 11)->count() }}</h5>
                        <p class="mb-0">PF</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar">
                        <div class="avatar-initial bg-label-primary rounded">
                            <i class="ri-building-2-line ri-24px"></i>
                        </div>
                    </div>
                    <div class="card-info">
                        <h5 class="mb-0">{{ $clients->filter(fn($c) => strlen(preg_replace('/\D/', '', $c->cpfcnpj)) === 14)->count() }}</h5>
                        <p class="mb-0">PJ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <h5 class="card-header">Clientes</h5>

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Detalhes</th>
                            <th>Serviços</th>
                            <th>Opções</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($clients as $client)
                            <tr>
                                <td>
                                    <i class="ri-user-follow-fill ri-22px text-success mr-4"></i>
                                    <span class="fw-medium">{{ $client->name }}</span>
                                    <br>
                                    <span class="badge bg-label-info m-1">{{ $client->labelCpfCnpj() }}</span>
                                    <span class="badge bg-label-dark m-1">{{ $client->labelPhone() }}</span>
                                    <span class="badge bg-label-dark m-1">{{ $client->email }}</span>
                                    <br>
                                    <span class="badge bg-label-dark m-1">{{ $client->address() }}</span>
                                </td>
                                <td>
                                    @foreach ($client->services as $key => $service)
                                        <span class="badge bg-label-info m-1">{{ $service->name }}</span> 
                                        @if ($key == 1)
                                            <br>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    <form action="{{ route('deleted-client') }}" method="POST" class="demo-inline-spacing delete">
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ $client->uuid }}">
                                        <button type="submit" class="btn btn-icon btn-outline-danger waves-effect">
                                            <span class="tf-icons ri-delete-bin-line ri-22px"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-success waves-effect" data-bs-toggle="modal" data-bs-target="#updatedModal{{ $client->uuid }}">
                                            <span class="tf-icons ri-eye-line ri-22px"></span>
                                        </button>
                                        <button type="button" class="btn btn-icon btn-outline-dark waves-effect">
                                            <span class="tf-icons ri-bank-card-line ri-22px"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="updatedModal{{ $client->uuid }}" tabindex="-1" aria-hidden="true">
                                <form action="{{ route('updated-client') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="uuid" value="{{ $client->uuid }}">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="exampleModalLabel1">Dados do Cliente</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-12 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="name" id="name" class="form-control" placeholder="Nome / Nome Fantasia" value="{{ $client->name }}"/>
                                                            <label for="name">Nome / Nome Fantasia</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail" value="{{ $client->email }}"/>
                                                            <label for="email">E-mail</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="phone" id="phone" class="form-control phone" placeholder="Telefone" value="{{ $client->phone }}" oninput="maskPhone(this)"/>
                                                            <label for="phone">Telefone</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="cpfcnpj" id="cpfcnpj" class="form-control cpfcnpj" placeholder="CPF/CNPJ" value="{{ $client->cpfcnpj }}" oninput="maskCpfCnpj(this)"/>
                                                            <label for="cpfcnpj">CPF/CNPJ</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-4 col-lg-4 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="number" name="postal_code" id="postal_code" class="form-control" placeholder="CEP" value="{{ $client->postal_code }}" onblur="consultAddress()"/>
                                                            <label for="postal_code">CEP</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-6 col-md-2 col-lg-2 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="num" id="num" class="form-control" placeholder="N" value="{{ $client->num }}"/>
                                                            <label for="num">N</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="address" id="address" class="form-control" placeholder="Endereço" value="{{ $client->address }}"/>
                                                            <label for="address">Endereço</label>
                                                        </div>
                                                    </div>
                                                    <div class="col mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="city" id="city" class="form-control" placeholder="Cidade" value="{{ $client->city }}"/>
                                                            <label for="city">Cidade</label>
                                                        </div>
                                                    </div>
                                                    <div class="col mb-2">
                                                        <div class="form-floating form-floating-outline">
                                                            <input type="text" name="province" id="province" class="form-control" placeholder="Estado" value="{{ $client->province }}"/>
                                                            <label for="province">Estado</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <a class="me-1" data-bs-toggle="collapse" href="#collapseNotes" role="button" aria-expanded="false" aria-controls="collapseNotes"> Anotações </a>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="collapse" id="collapseNotes">
                                                            <div class="form-floating form-floating-outline mb-2">
                                                                <textarea class="form-control h-px-50" name="description" id="description" placeholder="Descrição">{{ $client->description }}</textarea>
                                                                <label for="description">Descrição</label>
                                                            </div>
                                                            <div class="form-floating form-floating-outline mb-2">
                                                                <textarea class="form-control h-px-100" name="notes" id="notes" placeholder="Notas">{{ $client->notes }}</textarea>
                                                                <label for="notes">Notas</label>
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