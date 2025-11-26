<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../vendor/autoload.php';
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (!class_exists(\Mpdf\Mpdf::class)) {
    die("mPDF ไม่เจอ ลองเช็ค path vendor/autoload.php");
}

$mpdf = new \Mpdf\Mpdf([
  'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../../font',
    ]),
    'fontdata' => $fontData + [
        'thsarabunnew' => [
            'R' => 'THSarabunNew.ttf',
            'B' => 'THSarabunNew-Bold.ttf',
            'I' => 'THSarabunNew-Italic.ttf',
            'BI' => 'THSarabunNew-BoldItalic.ttf',
        ]
    ],
    'default_font' => 'thsarabunnew',
    'tempDir' => __DIR__ . '/../../tmp',
    'mode' => 'utf-8',
    'format' => [240, 190],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

date_default_timezone_set("Asia/Bangkok");
$day_add = date('Y-m-d H:i:s');

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$sql = "SELECT SP.product_name,NP.product_name AS get_productname,
  SUM(SP.price_center * SP.product_count) / SUM(SP.product_count) AS avg_price_center,
  SUM(SP.product_price * SP.product_count) / SUM(SP.product_count) AS avg_product_price,
  SUM(SP.shipping_cost) / SUM(SP.product_count) AS avg_shipping,
  SP.price_center,SP.product_price,SP.shipping_cost,SUM(SP.product_count ) AS total_count,
 SUM(SP.product_count * SP.product_price) AS resutl_price,SUM(SP.shipping_cost) AS sum_cost,
 COALESCE(PS.tatol_product, 0) AS total_product, COALESCE(PS.price_to_pay, 0) AS total_pay 
 FROM stock_product SP LEFT JOIN name_product NP ON SP.product_name = NP.id_name LEFT JOIN (
 SELECT productname, SUM(tatol_product) AS tatol_product, SUM(price_to_pay) AS price_to_pay FROM list_productsell 
 LEFT JOIN orders_sell ON orders_sell.id_ordersell = list_productsell.ordersell_id
 WHERE orders_sell.date_time_sell BETWEEN '$start_date' AND '$end_date' GROUP BY productname) PS 
 ON SP.product_name = PS.productname GROUP BY SP.product_name";
 $selectStockProduct = $conn->query($sql);

$html = '
<style>
  body { font-family: "THSarabunNew"; font-size: 14pt; }
  h1 { text-align: center; font-size: 18pt; }
   table.slip-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }
  table.slip-table th,
  table.slip-table td {
    border: 1px solid #000;
    padding: 6px;
    text-align: center;
  }

  table.slip-table th.name,
  table.slip-table td.name {
    width: 30%;
    text-align: left;
    font-weight: bold;
  }

  table.slip-table th.total-blue,
  table.slip-table td.total-blue {
    width: 10%;
    color:blue;
  }

  table.slip-table th.price,
  table.slip-table td.price,
  table.slip-table th.qty,
  table.slip-table td.qty,
  table.slip-table th.total,
  table.slip-table td.total {
    width: 12%;
  }

  .fontboldtfoot{
    font-weight: bold;
    color:black;
    font-size:18px;
  }

</style>

<h2>รายการสินค้า</h2>
<div style="display:flex;width:100%;">
  ข้อมูลระหว่างวันที่ '.$start_date.' ถึง '.$end_date.'
</div>
<div style="width:100%;">
  <table class="slip-table">
    <thead>
      <tr style="background-color:#ff9933;">
        <th class="name">สินค้า</th>
        
        <th class="qty">จำนวนที่ขาย</th>
        
        <th class="total">ราคาต่อลัง</th>
        
        <th class="total">ค่าส่ง/ลัง</th>
        <th class="total">ราคา+ค่าส่ง</th>
        <th class="total">ราคากลางต่อลัง</th>
        <th class="total-blue">คืนทุน</th>
        <th class="total">รายรับ</th>
        <th class="total-blue">กำไร</th>
        
      </tr>
    </thead>
    <tbody>
  ';
  $i = 0;
  $sum_totalcount = 0;
  $sum_total_productsell = 0;
  $sum_totalremining = 0;
  $sumresutl_price = 0;
  $sum_payback = 0;
  $sum_payback_center = 0;
  $sum_difference = 0;
  $sum_shipping = 0;
  while($rows = $selectStockProduct->fetch_assoc()){
    $avg_price = $rows['avg_product_price'];
    $avg_center = $rows['avg_price_center'];
    $shipping_one = $rows['avg_shipping'];
    //$shipping_one = $rows['sum_cost'] / $rows['total_count'];
    $payback = $rows['total_product'] * ($avg_price + $shipping_one);
    $payback_center = $rows['total_product'] * $avg_center;
    $difference = $payback_center - $payback;
    $remaining_amount = $rows['total_count'] - $rows['total_product'];
    $sum_totalcount += $rows['total_count'];
    $sum_total_productsell += $rows['total_product'];
    $sum_totalremining += $remaining_amount;
    $sumresutl_price += $rows['resutl_price'];
    $sum_payback += $payback;
    $sum_payback_center +=$payback_center;
    $sum_difference += $difference;
    $sum_shipping += $shipping_one;
    $html .= "
      <tr>
        <td class=\"name\">{$rows['get_productname']}</td>
        
        <td class=\"qty\">".number_format($rows['total_product'])."</td>
        
        <td class=\"total\">".number_format($rows['avg_product_price'])."</td>
        
        <td class=\"total\">".number_format($shipping_one?? 0,2)."</td>
        <td class=\"total\">".number_format($avg_price+$shipping_one)."</td>
        <td class=\"total\">".number_format($avg_center)."</td>
        <td class=\"total-blue\">".number_format($payback)."</td>
        <td class=\"total\">".number_format($payback_center)."</td>
        <td class=\"total-blue\">".number_format($difference)."</td>
        
      </tr>
    
  ";
  $i++;
  }
  $html .= '
    </tbody>
        <tfoot>
          <tr style="background-color:#F5DEB3;">
            <td class=\"fontboldtfoot name\">'.number_format($i).' รายการ</td>
            
            <td class=\"fontboldtfoot qty\">'.number_format($sum_total_productsell).'</td>
            
            <td class=\"fontboldtfoot qty\"></td>
            <td class=\"fontboldtfoot total\"></td>
            <td class=\"fontboldtfoot total\"></td>
            <td class=\"fontboldtfoot total\"></td>
            <td class=\"fontboldtfoot total-blue\">'.number_format($sum_payback).'</td>
            <td class=\"fontboldtfoot total\">'.number_format($sum_payback_center).'</td>
            <td class=\"fontboldtfoot total-blue\">'.number_format($sum_difference).'</td>
          </tr>
        </tfoot>
  </table>
</div>
<br/>
  <div style="width:100%;display:flex">
      <b>ปริ้นเมื่อ : '.$day_add.'</b>
  </div>
';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>