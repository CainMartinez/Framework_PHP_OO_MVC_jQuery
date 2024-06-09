<?php
require __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class QR{
    public static function create(){
        return new Builder();
    }

    public static function QR_invoice($id_order){
        $url = SITE_ROOT . 'view/uploads/pdf/living_mobility_invoice_' . $id_order . '.pdf';
        $qr_url = SITE_ROOT . 'view/uploads/qr/living_mobility_QR_' . $id_order . '.png';
    
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->build();
    
        file_put_contents($qr_url, $result->getString());
        $url2 = 'http://localhost/living_mobility/view/uploads/qr/living_mobility_qr_'.$id_order.'.png';
        return $url2;
    }
}
