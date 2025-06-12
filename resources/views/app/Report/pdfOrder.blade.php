<!doctype html>

<html lang="pt-br" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-template="horizontal-menu-template-no-customizer" data-style="light">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
        <title>{{ env('APP_NAME') }} | {{ env('APP_DESCRIPTION') }}</title>
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}"/>

        <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}"/>
        <link rel="preconnect" href="https://fonts.googleapis.com"/>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

        <style>
            body {
                background-color: #fff;
                color: #010000;
            }
            .content {
                margin-top: 50px;
                margin-bottom: 50px;
            }

            .header, .footer {
                width: 100%;
                text-align: center;
            }

            table th, table td {
                border: 1px solid #ddd;
                padding: 6px;
            }
        </style>
    </head>
    <body class="p-3">

        <htmlpageheader name="header">
            <div class="header">
                {!! $header ?? $order->header !!}
            </div>
        </htmlpageheader>
        <sethtmlpageheader name="header" value="on" show-this-page="1"/>

        <div class="content">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Prestador / Serviço</th>
                        <th>Valor</th>
                        <th>Desconto</th>
                        <th class="text-center">Taxas / Custos / Comissão</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotal = 0;
                    @endphp

                    @foreach($services as $service)
                        @php
                            $feesTotal = $service->fees->sum('value');
                            $serviceTotal = ($service->value - $service->discount) + $feesTotal;
                            $grandTotal += $serviceTotal;
                        @endphp
                        <tr>
                            <td>{{ $service->service->name }}</td>
                            <td>R$ {{ number_format($service->value, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format($service->discount, 2, ',', '.') }}</td>
                            <td class="text-center">
                                <table class="table mb-0">
                                    <tr>
                                        @foreach($service->fees as $fee)
                                            <td style="border: 1px solid #ccc; padding: 2px; font-weight: bold; text-align: center;">
                                                {{ $fee->fee->name }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        @foreach($service->fees as $fee)
                                            <td style="border: 1px solid #ccc; padding: 2px; text-align: center;">
                                                R$ {{ number_format($fee->value, 2, ',', '.') }}
                                            </td>
                                        @endforeach
                                    </tr>
                                </table>
                            </td>
                            <td>R$ {{ number_format($serviceTotal, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                        <td class="text-end fw-bold">TOTAL:</td>
                        <td class="fw-bold">R$ {{ number_format(($order->value), 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            {!! $footer ?? $order->footer !!}
        </div>
    </body>
</html>