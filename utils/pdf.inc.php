<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;

class PDF{
    public static function create(){
        return new Dompdf();
    }
    public static function create_invoice($invoice_order, $billing, $lines){
        $save_path = SITE_ROOT . 'view/uploads/pdf/';
        $html = self::creator_html($billing, $lines);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');  
        $dompdf->render();
        $output = $dompdf->output();
        $name_pdf = 'living_mobility_invoice_' . $invoice_order . '.pdf';
        $file_path = $save_path . $name_pdf;
        file_put_contents($file_path, $output);
        return ['invoice' => 'view/uploads/pdf/' . $name_pdf];
    }
    public static function creator_html($billing, $lines){
        // error_log(print_r($billing, true), 3, 'debug.log');
        // error_log(print_r($lines, true), 3, 'debug.log');
        ob_start(); 
        ?>
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f5f5f5;
                }
                .container {
                    width: 80%;
                    margin: auto;
                    background-color: #fff;
                    padding: 20px;
                    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
                }
                .card {
                    border: 1px solid #ddd;
                    margin-bottom: 20px;
                }
                .card-header {
                    background-color: #f8f8f8;
                    padding: 10px;
                    border-bottom: 1px solid #ddd;
                }
                .card-header h3 {
                    margin: 0;
                    color: #333;
                }
                .card-body {
                    padding: 20px;
                }
                .card-body p {
                    margin: 0 0 10px 0;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                }
                .table th {
                    background-color: #f8f8f8;
                }
            </style>
        </head>
        <body>
        <div class="row justify-content-center">
                <div class="col-sm-10 col-lg-7 col-xl-6"><br><br>
                    <h2  align="center" class="wow-outer"><span class="wow slideInDown text-uppercase">Invoice Living Mobility</span><i class="fas fa-shopping-cart" style="font-size: 1.5em;"></i></h2>
                    <br>
                <div class="container">
                    <!-- Billing Information -->
                    <div class="card">
                        <div class="card-header"><h3 align="center" style="color: black;">Billing Information</h3></div>
                        <div align="center" class="card-body">
                            <p><strong>Name:</strong> <?php echo $billing[0]['name']; ?></p>
                            <p><strong>Surname:</strong> <?php echo $billing[0]['surname']; ?></p>
                            <p><strong>Address:</strong> <?php echo $billing[0]['address']; ?></p>
                            <p><strong>City:</strong> <?php echo $billing[0]['city']; ?></p>
                            <p><strong>Zip:</strong> <?php echo $billing[0]['zip']; ?></p>
                        </div>
                    </div>
                    <br>
                    <!-- Purchased Services -->
                    <div class="card">
                        <div class="card-header"><h3 align="center" style="color: black;">Purchased Services</h3></div>
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($lines as $line): ?>
                                    <tr>
                                        <td><?php echo $line['service']; ?></td>
                                        <td><?php echo $line['quantity']; ?></td>
                                        <td><?php echo $line['price']; ?> €</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <!-- Total Price -->
                    <div class="card">
                        <div class="card-header"><h3 align="center" style="color: black;">Total Price</h3></div>
                        <h3 align="center" style="color: black;"><?php echo $billing[0]['total']; ?> €</h3>
                    </div>
                </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }
}