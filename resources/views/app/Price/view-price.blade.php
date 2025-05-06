@extends('app.layout')
@section('content')
    
    <div class="col-12 col-sm-12 col-md-5 col-lg-5 mt-5 align-self-start row">
        <div class="col-sm-12 col-lg-4 mb-2">
            <div class="card card-border-shadow-info h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-info"><i class="ri-barcode-box-line ri-24px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $price->services->count() }}</h4>
                    </div>
                    <h6 class="mb-0 fw-normal">Serviços</h6>
                </div>
            </div>
        </div>

        @php
            $totalFeesCount = $price->services->reduce(function ($carry, $service) {
                return $carry + $service->fees->count();
            }, 0);
        @endphp

        <div class="col-sm-12 col-lg-4 mb-2">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-warning"><i class="ri-alert-line ri-24px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $totalFeesCount }}</h4>
                    </div>
                    <h6 class="mb-0 fw-normal">Taxas/Custos</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-4 mb-2">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded-3 bg-label-success"><i class="ri-refund-2-line ri-24px"></i></span>
                        </div>
                        <h4 class="mb-0">0</h4>
                    </div>
                    <h6 class="mb-0 fw-normal">Cobranças</h6>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-12 mb-2">
            <div class="card card-border-shadow-dark h-100">
                <div class="card-body">
                    <form action="{{ route('updated-price') }}" method="POST" class="row">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $price->uuid }}">
                        <div class="col-8 col-sm-12 col-md-8 col-lg-8 mb-2">
                            <div class="form-floating form-floating-outline">
                                <div class="select2-primary">
                                    <select name="client_id" id="client_id" class="select2 form-select">
                                        <option value="Escolha um cliente:" selected>Escolha um cliente:</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" @selected($price->client_id == $client->id)>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label for="client_id">Cliente</label>
                            </div>
                        </div>
                        <div class="col-4 col-sm-12 col-md-4 col-lg-4 mb-2">
                            <div class="form-floating form-floating-outline">
                                <div class="select2-primary">
                                    <select name="status" id="status" class="select2 form-select">
                                        <option value="Escolha um Status:" selected>Escolha um Status:</option>
                                        <option value="1" @selected($price->status == 1)>Aprovado</option>
                                        <option value="0" @selected($price->status != 1)>Pendente</option>
                                    </select>
                                </div>
                                <label for="status">Status</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating form-floating-outline mb-2">
                                <textarea class="form-control h-px-100" name="notes" id="notes" placeholder="Notas">{{ $price->notes }}</textarea>
                                <label for="notes">Notas</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 offset-md-6 col-md-6 offset-lg-6 col-lg-6 d-grid mb-2">
                            <div class="btn-group">
                                <a href="{{ route('list-prices') }}" class="btn btn-outline-dark"> Cancelar </a>
                                <button type="submit" class="btn btn-success">Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-7 col-lg-7 mt-5 row">
        <div class="card card-border-shadow-dark h-100">
            <div class="card-header">
                <h5 class="mb-0">LISTA DE SERVIÇOS</h5>
                <hr>
            </div>
            <div class="card-body">
                <form action="{{ route('add-price-service') }}" method="POST" class="row">
                    @csrf
                    <input type="hidden" name="price_id" value="{{ $price->id }}">
                    <div class="col-10 col-sm-10 col-md-10 col-lg-10 mb-2">
                        <div class="form-floating form-floating-outline">
                            <select name="services[]" id="services" class="select2 form-select" multiple>
                                <optgroup label="Escolha os serviços para cotação">
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                            <label for="services">Escolha os serviços para cotação</label>
                        </div>
                    </div>
                    <div class="col-2 col-sm-2 col-md-2 col-lg-2 mb-2">
                        <button type="submit" class="btn btn-success h-100"><i class="ri-add-circle-line"></i></button>
                    </div>
                </form>

                <div class="mt-2">
                    <div class="accordion accordion-popout mt-4" id="accordionPopout">
                        @foreach ($price->services as $key => $service_a_price)
                            <div class="accordion-item  @if($key == 0) active @endif">
                                <h2 class="accordion-header" id="headingPopoutOne">
                                    <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordion{{ $service_a_price->id }}" aria-expanded="true" aria-controls="accordion{{ $service_a_price->id }}"> 
                                        <div class="d-flex justify-content-between align-items-center w-100">
                                            <div class="text-start">
                                                {{ $service_a_price->service->name }}
                                            </div>
                                            
                                            <div class="text-end">
                                                <span class="badge bg-label-dark">{{ $service_a_price->quantity }}</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
        
                                <div id="accordion{{ $service_a_price->id }}" class="accordion-collapse collapse @if($key == 0) show @endif" aria-labelledby="headingPopoutOne" data-bs-parent="#accordionPopout">
                                    <div class="accordion-body">

                                        <div class="divider">
                                            <div class="divider-text">Dados do Serviço</div>
                                        </div>

                                        <form action="{{ route('updated-price-service') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="price_service_id" value="{{ $service_a_price->id }}">
                                            <div class="row">
                                                <div class="col-6 col-sm-6 col-md-4 col-lg-4 mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="value" id="value" class="form-control money" placeholder="Valor" value="{{ $service_a_price->value }}" oninput="maskValue(this)" disabled/>
                                                        <label for="value">Valor</label>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-sm-6 col-md-4 col-lg-4 mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="text" name="discount" id="discount" class="form-control money" placeholder="Desconto" value="{{ $service_a_price->discount }}" oninput="maskValue(this)"/>
                                                        <label for="discount">Desconto</label>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-sm-6 col-md-2 col-lg-2 mb-2">
                                                    <div class="form-floating form-floating-outline">
                                                        <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Qtd" value="{{ $service_a_price->quantity ?? 1 }}" disabled/>
                                                        <label for="quantity">Qtd</label>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-sm-6 col-md-2 col-lg-2 mb-2">
                                                    <button type="submit" class="btn btn-success h-100"><i class="ri-check-line"></i></button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="divider">
                                            <div class="divider-text">Taxas & Custos</div>
                                        </div>

                                        <form action="{{ route('action-price-service') }}" method="POST" class="fees-form">
                                            @csrf
                                            <input type="hidden" name="price_id" value="{{ $price->id }}">
                                            <input type="hidden" name="price_service_id" value="{{ $service_a_price->id }}">
                                        
                                            <div class="row">
                                                @foreach ($service_a_price->service->fees as $i => $fee)
                                                    @php
                                                        $savedFee = $service_a_price->fees->firstWhere('fee_id', $fee->id);
                                                        $initialValue = $savedFee->value ?? $fee->value;
                                                        $isFixed = $fee->value !== null && $fee->value > 0;
                                                    @endphp
                                        
                                                    <div class="col-12 mb-2 p-2">
                                                        <input type="hidden" name="fees[{{ $i }}][fee_id]" value="{{ $fee->id }}">
                                        
                                                        <div class="row align-items-center">
                                                            <div class="col-md-4">
                                                                <strong>{{ $fee->name }}</strong>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" name="fees[{{ $i }}][value]" id="value" class="form-control money fee-value" placeholder="Valor" value="{{ $savedFee->value ?? $fee->value }}" oninput="maskValue(this)" {{ $isFixed ? 'readonly' : '' }} data-min="{{ $fee->value_min }}" data-max="{{ $fee->value_max }}" data-label="{{ $fee->name }}"/>
                                                                    <label for="value">Valor</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" name="fees[{{ $i }}][discount]" id="discount" class="form-control money" placeholder="Desconto" value="{{ $savedFee->discount ?? 0 }}" oninput="maskValue(this)"/>
                                                                    <label for="discount">Desconto</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="text-end">
                                                <div class="btn-group mt-3">
                                                    <button type="button" name="action" value="delete" class="btn btn-outline-danger" onclick="confirmRemoveService(this)"><span class="tf-icons ri-delete-bin-line ri-22px"></span></button>
                                                    <button type="submit" name="action" value="save" class="btn btn-success">Salvar</button>
                                                </div>
                                            </div>
                                        </form>                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="container d-flex justify-content-between mt-3 w-100">
                        <div class="text-start">
                            Resumo:
                        </div>
                        <div class="text-end">
                            @php
                                $totalServices      = ($price->services->sum('value') - $price->services->sum('discount'));
                                $totalFees          = 0;
                                $totalFeeDiscounts  = 0;

                                foreach ($price->services as $service) {
                                    foreach ($service->fees as $fee) {
                                        $totalFees += $fee->value;
                                        $totalFeeDiscounts += $fee->discount;
                                    }
                                }

                                $totalFinal = $totalServices + ($totalFees - $totalFeeDiscounts);
                            @endphp
                            <table class="table table-borderless table-sm mb-0">
                                <tbody>
                                    <tr>
                                        <td>T. Serviços:</td>
                                        <td>
                                            <span class="badge bg-label-dark">
                                                R$ {{ number_format($totalServices, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>T. Taxas:</td>
                                        <td>
                                            <span class="badge bg-label-dark">
                                                R$ {{ number_format($totalFees, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>T. Desc. Taxas:</td>
                                        <td>
                                            <span class="badge bg-label-warning">
                                                R$ {{ number_format($totalFeeDiscounts, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>T. Final:</strong></td>
                                        <td>
                                            <span class="badge bg-label-success">
                                                R$ {{ number_format($totalFinal, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="container d-flex justify-content-between mt-3 w-100">
                        <div class="text-start mt-3">

                        </div>
                        <div class="text-end">
                            <form action="{{ route('action-price-service') }}" method="post">
                                @csrf
                                <input type="hidden" name="price_id" value="{{ $price->id }}">
                                <button type="button" name="action" value="clean" onclick="confirm(this)" class="btn btn-outline-danger">Limpar Cotação</button>
                                <button type="submit" name="action" value="order" class="btn btn-outline-dark">Gerar Pedido</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/price.js') }}"></script>         
@endsection