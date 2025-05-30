<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class PdfController extends Controller {
    
    public function pdfOrder($order, $pdf = null) {
        
        $order = Order::find($order);
        if (!$order) {
            return redirect()->back()->with('infor', 'Ordem indisponível ou não existe!');
        }

        if ($pdf) {

            $header = $this->convertImagesToBase64($order->header);
            $footer = $this->convertImagesToBase64($order->footer);
            
            $html = View::make('app.Report.pdfOrder', [
                'order'    => $order,
                'services' => $order->price->services,
                'header'   => $header,
                'footer'   => $footer
            ])->render();

            $mpdf = new Mpdf([
                'mode'          => 'utf-8',
                'format'        => 'A4-L',
                'margin_top'    => 40,
                'margin_bottom' => 20,
            ]);

            $mpdf->showImageErrors = false;
            $mpdf->WriteHTML($html);

            return response($mpdf->Output('', 'S'), 200)->header('Content-Type', 'application/pdf');

        }

        return view('app.Report.pdfOrder', [
            'order'     => $order,
            'services'  => $order->price->services
        ]);
    }

    private function convertImagesToBase64($html) {
        return preg_replace_callback('/<img[^>]+src="([^">]+)"/i', function ($matches) {
            $url = $matches[1];

            try {
                $imageData = @file_get_contents($url);
                if (!$imageData) return $matches[0]; // mantém original se falhar

                $type = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);

                return str_replace($url, $base64, $matches[0]);
            } catch (\Exception $e) {
                return $matches[0]; // mantém original se erro
            }
        }, $html);
    }


}
