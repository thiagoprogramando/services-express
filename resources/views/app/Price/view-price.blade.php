@extends('app.layout')
@section('content')
    
    <div class="col-12 col-sm-12 col-md-4 col-lg-5 mt-5 align-self-start row">
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

        <div class="col-sm-12 col-lg-12 mb-2 align-items-start">
            <div class="card card-border-shadow-dark">
                <div class="card-body">
                    <form action="{{ route('updated-price') }}" method="POST" class="row">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $price->uuid }}">
                        <div class="col-8 col-sm-12 col-md-12 col-lg-8 mb-2">
                            <div class="form-floating form-floating-outline">
                                <div class="select2-primary">
                                    <select name="client_id" id="client_id" class="select2 form-select" @disabled($order)>
                                        <option value="Escolha um cliente:" selected>Escolha um cliente:</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" @selected($price->client_id == $client->id)>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label for="client_id">Cliente</label>
                            </div>
                        </div>
                        <div class="col-4 col-sm-12 col-md-12 col-lg-4 mb-2">
                            <div class="form-floating form-floating-outline">
                                <div class="select2-primary">
                                    <select name="status" id="status" class="select2 form-select" @disabled($order)>
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
                                <textarea class="form-control h-px-100" name="notes" id="notes" placeholder="Notas" @disabled($order)>{{ $price->notes }}</textarea>
                                <label for="notes">Notas</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 offset-lg-6 col-lg-6 d-grid mb-2">
                            <div class="btn-group">
                                <a href="{{ route('list-prices') }}" class="btn btn-outline-dark" @disabled($order)> Cancelar </a>
                                <button type="submit" class="btn btn-success" @disabled($order)>Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-8 col-lg-7 mt-5 row align-items-start">
        <div class="card card-border-shadow-dark">
            <div class="card-header">
                <h5 class="mb-0">LISTA DE SERVIÇOS</h5>
                <hr>
            </div>
            <div class="card-body">
                @if (!$order)
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
                @endif

                <div class="mt-2">
                    <div class="accordion accordion-popout mt-4" id="accordionPopout">
                        @foreach ($price->services as $key => $service_a_price)
                            <div class="accordion-item  @if($key == 0 && $price->services->count() <= 1) active @endif">
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
        
                                <div id="accordion{{ $service_a_price->id }}" class="accordion-collapse collapse @if($key == 0 && $price->services->count() <= 1) show @endif" aria-labelledby="headingPopoutOne" data-bs-parent="#accordionPopout">
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

                    @if (!$order)
                        <div class="container d-flex justify-content-between mt-3 w-100">
                            <div class="text-start mt-3"></div>
                            <div class="text-end">
                                <form action="{{ route('action-price-service') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="price_id" value="{{ $price->id }}">
                                    <button type="button" name="action" value="clean" onclick="confirm(this)" class="btn btn-outline-danger">Limpar Cotação</button>
                                    <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#createdOrderModal">Gerar Pedido</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($order)
            <div class="card card-border-shadow-dark mt-3">
                <div class="card-body">
                    <div class="nav-align-top mb-3">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                                    <span class="d-none d-sm-block">
                                        <i class="tf-icons ri-database-line me-2"></i> Dados
                                    </span>
                                    <i class="ri-database-line ri-20px d-sm-none"></i>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-header" aria-controls="navs-justified-header" aria-selected="false">
                                    <span class="d-none d-sm-block">
                                        Cabeçalho
                                    </span>
                                    <i title="Cabeçalho" class="ri-file-text-line ri-20px d-sm-none"></i>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-footer" aria-controls="navs-justified-footer" aria-selected="false">
                                    <span class="d-none d-sm-block">
                                        Rodapé
                                    </span>
                                    <i title="Rodapé" class="ri-file-list-2-line ri-20px d-sm-none"></i>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <form action="{{ route('action-order') }}" method="POST">
                        @csrf
                        <input type="hidden" name="uuid" value="{{ $order->uuid }}">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <div class="select2-primary">
                                                <select name="template_id" id="template_id" class="select2 form-select">
                                                    <option value="Escolha um cliente:" selected>Escolha um Template:</option>
                                                    @foreach ($templates as $template)
                                                        <option value="{{ $template->id }}" @selected($order->template_id == $template->id)>{{ $template->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label for="template_id">Template</label>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <div class="select2-primary">
                                                <select name="status" id="status" class="select2 form-select">
                                                    <option value="0" selected>Escolha um Status:</option>
                                                    <option value="1" @selected($order->status == 1)>Aprovado</option>
                                                    <option value="0" @selected($order->status == 0)>Pendente</option>
                                                </select>
                                            </div>
                                            <label for="status">Status</label>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" name="value" id="value" class="form-control money" placeholder="Valor Total" value="{{ $order->value }}" oninput="maskValue(this)"/>
                                            <label for="value">Valor Total</label>
                                        </div>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <div class="form-floating form-floating-outline">
                                            <input type="text" name="discount" id="discount" class="form-control money" placeholder="Desconto (Ordem)" value="{{ $order->discount }}" oninput="maskValue(this)"/>
                                            <label for="discount">Desconto (Ordem)</label>
                                        </div>
                                    </div>     
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-grid mb-3">
                                        <div class="btn-group">
                                            <a href="{{ route('list-orders') }}" title="Cancelar" class="btn btn-outline-dark"> Cancelar </a>
                                            <a target="_blank" href="{{ route('report-order-pdf', ['order' => $order->id, 'pdf' => true]) }}" title="PDF" class="btn btn-outline-dark"> <i class="ri-file-pdf-2-line"></i> </a>
                                            <a onclick="onClip('{{ route('report-order-pdf', ['order' => $order->id]) }}')" title="Excel" class="btn btn-outline-dark"> <i class="ri-file-copy-line"></i> </a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-grid mb-3">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-outline-danger" name="action" value="deleted">Excluir</button>
                                            <button type="submit" class="btn btn-outline-success" name="action" value="updated">Salvar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-justified-header" role="tabpanel">
                                <div class="form-floating form-floating-outline mb-2">
                                    <textarea class="form-control h-px-100 editor" name="header" id="header">{{ $order->header }}</textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-outline-success" name="action" value="updated">Salvar</button>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="navs-justified-footer" role="tabpanel">
                                <div class="form-floating form-floating-outline mb-2">
                                    <textarea class="form-control h-px-100 editor" name="footer" id="footer">{{ $order->footer }}</textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-outline-success" name="action" value="updated">Salvar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade" id="createdOrderModal" tabindex="-1" aria-hidden="true">
        <form action="{{ route('created-order') }}" method="POST">
            @csrf
            <input type="hidden" name="price_id" value="{{ $price->id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Dados da Ordem</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <div class="select2-primary">
                                        <select name="template_id" id="template_id" class="select2 form-select">
                                            <option value="Escolha um cliente:" selected>Escolha um Template:</option>
                                            @foreach ($templates as $template)
                                                <option value="{{ $template->id }}">{{ $template->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label for="template_id">Template</label>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <div class="select2-primary">
                                        <select name="status" id="status" class="select2 form-select">
                                            <option value="0" selected>Escolha um Status:</option>
                                            <option value="1">Aprovado</option>
                                            <option value="0">Pendente</option>
                                        </select>
                                    </div>
                                    <label for="status">Status</label>
                                </div>
                            </div>
                            <div class="col-6 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="value" id="value" class="form-control money" placeholder="Valor Total" value="{{ number_format($totalFinal, 2, ',', '.') }}" oninput="maskValue(this)" readonly/>
                                    <label for="value">Valor Total</label>
                                </div>
                            </div>

                            <div class="col-6 mb-2">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" name="discount" id="discount" class="form-control money" placeholder="Desconto (Ordem)" oninput="maskValue(this)"/>
                                    <label for="discount">Desconto (Ordem)</label>
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

    <script src="{{ asset('assets/js/price.js') }}"></script> 
    <script src="https://cdn.tiny.cloud/1/tgezwiu6jalnw1mma8qnoanlxhumuabgmtavb8vap7357t22/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea.editor',
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
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