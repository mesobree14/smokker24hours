<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../../vendor/autoload.php';
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

if (!class_exists(\Mpdf\Mpdf::class)) {
    die("mPDF ไม่เจอ ลองเช็ค path vendor/autoload.php");
}

$mpdf = new \Mpdf\Mpdf([
  'fontDir' => array_merge($fontDirs, [
        __DIR__ . '/../../../font',
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
    'tempDir' => __DIR__ . '/../../../tmp',
    'mode' => 'utf-8',
    'format' => [120, 190],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT SP.product_name,NP.product_name AS get_productname,SP.product_price,
 SUM(SP.product_count * SP.product_price) AS resutl_price, SUM(SP.product_count) AS total_count,
 COALESCE(PS.tatol_product, 0) AS total_product, COALESCE(PS.price_to_pay, 0) AS total_pay
 FROM stock_product SP LEFT JOIN name_product NP ON SP.product_name = NP.id_name LEFT JOIN (
 SELECT productname, SUM(tatol_product) AS tatol_product, SUM(price_to_pay) AS price_to_pay FROM list_productsell GROUP BY productname) PS 
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
    width: 40%;
    text-align: left;
  }

  table.slip-table th.price,
  table.slip-table td.price,
  table.slip-table th.qty,
  table.slip-table td.qty,
  table.slip-table th.total,
  table.slip-table td.total {
    width: 20%;
  }
  .footer {
    font-size:20px;
    font-weight: bold;
    margin-top:5px;
  }

</style>

<h2>รายการสินค้า</h2>
<table class="slip-table">
  <thead>
      <tr style="background-color:#ffb3ff;">
        <th class="name">สินค้า</th>
        <th class="price">จำนวนทั้งหมด</th>
        <th class="qty">จำนวนที่ขาย</th>
        <th class="total">จำนวนที่เหลือในสต๊อก</th>
        <th class="total">ต่อลัง</th>
        <th class="total">ค่าส่ง</th>
        <th class="total">ราคาทุน</th>
        <th class="total">ราคากลาง/ลัง</th> 
        <th class="total">ส่วนต่าง/ลัง</th> 
        <th class="total">ราคาสินค้าทั้งหมด</th> 
        <th class="total">จัดส่งทั้งหมด</th>
        <th class="total">ราคาสั่งซื้อ</th>
        <th class="total">ทุนที่กำลังใช้</th>
        <th class="total">รายรับ</th>
        <th class="total">คืนทุน</th>
        <th class="total">กำไร</th>
      </tr>
    </thead>
    <tbody>
  ';
  $i = 0;
  $sum_totalcount = 0;
  $sum_total_productsell = 0;
  $sum_totalremining = 0;
  while($rows = $selectStockProduct->fetch_assoc()){
    $remaining_amount = $rows['total_count'] - $rows['total_product'];
    $sum_totalcount += $rows['total_count'];
    $sum_total_productsell += $rows['total_product'];
    $sum_totalremining += $remaining_amount;
    $sum_priceone = $rows['resutl_price'] / $rows['total_count']; // เฉลี่ยต่อลัง
    $html .= "
      <tr>
        <td class=\"name\">{$rows['get_productname']}</td>
        <td class=\"price\">".number_format($rows['total_count'])."</td>
        <td class=\"qty\">".number_format($rows['total_product'])."</td>
        <td class=\"total\">". number_format($remaining_amount)."</td>
        <td class=\"total\">". number_format($rows['product_price'])."</td>
        <td class=\"total\">". number_format($rows['product_price'])."</td>
      </tr>
  ";
  $i++;
  }
  $html .= '
      <tr style="background-color:#a3a3c2;">
          <td class=\"name\">'.number_format($i).' รายการ</td>
            <td class=\"price\">'.number_format($sum_totalcount).'</td>
            <td class=\"qty\">'.number_format($sum_total_productsell).'</td>
            <td class=\"total\">'.number_format($sum_totalremining).'</td>
          </tr>
    </tbody>
    
</table>
';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>