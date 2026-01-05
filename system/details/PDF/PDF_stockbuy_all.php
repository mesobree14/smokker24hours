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
    'format' => [140, 190],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$svgqr = file_get_contents(__DIR__ . '/../../../db/QR-code.svg');

date_default_timezone_set("Asia/Bangkok");
$day_add = date('Y-m-d H:i:s');

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stock_products = $conn->query("
SELECT 
    stock_product.product_name,
    name_product.product_name AS new_productname,

    SUM(stock_product.product_count) AS total_product_count,

    SUM(stock_product.product_price * stock_product.product_count) 
        AS total_product_price,

    -- ราคาเฉลี่ยต่อชิ้น
    (
        SUM(stock_product.product_price * stock_product.product_count)
        / NULLIF(SUM(stock_product.product_count), 0)
    ) AS avg_price_per_piece,

    SUM(stock_product.shipping_cost) AS total_shipping_cost,

    -- ค่าส่งเฉลี่ยต่อชิ้น
    (
        SUM(stock_product.shipping_cost)
        / NULLIF(SUM(stock_product.product_count), 0)
    ) AS avg_shipping_per_piece,

    SUM(stock_product.expenses) AS total_expenses

FROM stock_product
LEFT JOIN name_product 
    ON name_product.id_name = stock_product.product_name

GROUP BY stock_product.product_name, name_product.product_name
ORDER BY name_product.product_name ASC
");


$html =' 
<style>
  body { font-family: "THSarabunNew"; font-size: 14pt; }
.component {
    width: 100%;
    font-family: "THSarabunNew";
    font-size: 14pt;
    margin-bottom: 1px;
    overflow: hidden; /* เคลียร์ float */
}
.left {
    float: left;
    width: 57%;
    padding: 4px;
    box-sizing: border-box;
}
.right {
    float: right;
    width: 40%;
    box-sizing: border-box;
    
}

.left-qr {
    float: left;
    width: 30%;
    padding-top: 14px;
    box-sizing: border-box;
}
.right-qr {
    float: right;
    width: 70%;
    box-sizing: border-box;
    
}

.left-custom {
    float: left;
    width: 47%;
    padding: 4px;
    box-sizing: border-box;
}
.right-custom {
    float: right;
    width: 50%;
    box-sizing: border-box;
}

.doc {
    width: 100%;
    overflow: hidden;
    margin-left: 10px;
}
.doc span.label {
    float: left;
    font-weight: 900;
}
.doc span.value {
    float: right;
}
  .fontboldtfoot{
    font-weight: bold;
    color:blue;
    font-size:17px;
  }

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
    width: 35%;
    text-align: left;
  }

  table.slip-table th.price,
  table.slip-table td.price,
  table.slip-table th.qty,
  table.slip-table td.qty,
  table.slip-table th.total,
  table.slip-table td.total {
    width: 15%;
  }
  .footer {
    font-size:20px;
    font-weight: bold;
    margin-top:5px;
  }
</style>';

$html .='
<div>
  <div class="" style="">
    <div style="float: left; width: 55%; margin-left:5px">
      
    </div>
    <div style="float: right; width: 40%;">
      <h3 style="text-align: right;">ใบเสร็จคำสั่งซื้อ</h3>
    </div>
  </div>
  <div style="width:100%">
    <div class="component">
        <div class="left">
          <div class="doc">
              <b class="label" style="font-size:17px;">ผู้ออกใบเสร็จ :</b>
              <small class="value">SMOKKER 24 HOURS</small>
          </div>
          <div class="doc">
              <b class="label" style="font-size:17px;">เบอร์โทร :</b>
              <small class="value">-</small>
          </div>
        </div>
        <div class="right" style="background-color:#ff9933;">
          <div class="doc">
              <b class="label" style="font-size:17px;">รหัสใบเสร็จ :</b>
              <small class="value">-</small>
          </div>
          <div class="doc">
              <b class="label" style="font-size:17px;">ปริ้นเมื่อ :</b>
              <small class="value">'.$day_add.'</small>
          </div>
        </div>
    </div>
  </div>
  <div style="height: 150px;">
    <table class="slip-table">
      <thead>
        <tr style="background-color:#ff9933;">
          <th class="name">รายการสินค้า</th>
          <th class="qty">จำนวน</th>
          <th class="price">ราคาต้นทุนเฉลี่ย/ชิ้น</th>
          <th class="price">ราคาต้นทุนทั้งหมด</th>
          <th class="price">ราคาค่าส่งเฉลี่ย/ชิ้น</th>
          <th class="price">ราคาค่าส่งทั่งหมด</th>
          <th class="total">รวมยอด</th>
        </tr>
      </thead>
      <tbody>
    ';
  $x = 1;
  $total_shipping_qty = 0;
  $total_shipping_per = 0;
  $total_shipping_all = 0;
  $total_expenses = 0;
  while($rows = $stock_products->fetch_assoc()){
    $qty = (int)$rows['total_product_count'];
    $price_one = (float)($rows['avg_price_per_piece'] ?? 0);
    $price = (float)($rows['total_product_price'] ?? 0);
    $shipping_one = (float)($rows['avg_shipping_per_piece'] ?? 0);
    $shipping_all = (float)($rows['total_shipping_cost'] ?? 0);
    $expenses = (float)($rows['total_expenses'] ?? 0);

    $total_shipping_qty += $qty;
    $total_shipping_per += $price;
    $total_shipping_all += $shipping_all;
    $total_expenses += $expenses;
    $html .= "
    <tr>
        <td class=\"name\">{$rows['new_productname']}</td>
        <td class=\"qty\">{$qty}</td>
        <td class=\"price\">".number_format( $price_one,2,'.',',')."</td>
        <td class=\"price\">".number_format($price,2,'.',',')."</td>
        <td class=\"price\">".number_format($shipping_one ,2,'.',',')."</td>
        <td class=\"price\">".number_format($shipping_all,2,'.',',')."</td>
        <td class=\"total\">".number_format($expenses,2,'.',',')."</td>
      </tr>
  ";
  }

$html .= '
      </tbody>
      <tfoot>';
  $html .= "
          <tr style=\"font-weight:bold;background-color:#f2f2f2;\">
              <td class=\"fontboldtfoot name\"><b>รวมทั้งหมด</b></td>
              <td class=\"fontboldtfoot qty\">".number_format($total_shipping_qty)."</td>
              <td class=\"fontboldtfoot name\"></td>
              <td class=\"fontboldtfoot price\">".number_format($total_shipping_per,2,'.',',')."</td>
              <td class=\"fontboldtfoot name\"></td>
              <td class=\"fontboldtfoot price\">".number_format($total_shipping_all,2,'.',',')."</td>
              <td class=\"fontboldtfoot total\">".number_format($total_expenses,2,'.',',')."</td>
          </tr>"; 
$html .=' 
      </tfoot>
    </table>
  </div>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>