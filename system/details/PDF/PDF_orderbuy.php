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
    'format' => [160, 190],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$svgqr = file_get_contents(__DIR__ . '/../../../db/QR-code.svg');

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = $_GET['order_id'];
$sql_query = $conn->query("SELECT * FROM order_box WHERE order_id=$order_id");
$order = $sql_query->fetch_assoc();
$sql_product = $conn->query("SELECT 
  stock_product.product_name,stock_product.product_count,stock_product.product_price,stock_product.shipping_cost,stock_product.expenses,stock_product.id_order,name_product.product_name AS new_productname
  FROM stock_product LEFT JOIN name_product 
  ON name_product.id_name = stock_product.product_name 
  WHERE id_order=$order_id
  ORDER BY name_product.product_name ASC
");
$sql_count = $conn->query("SELECT COUNT(*) AS total, SUM(product_count) AS product_count, SUM(expenses) AS count_expenses FROM stock_product WHERE id_order=$order_id");
$count_rows = $sql_count->fetch_assoc();

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

  .fontboldtfoot{
    font-weight: bold;
    color:blue;
    font-size:17px;
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
              <b class="label" style="font-size:17px;">รหัสคำสั่งซื้อ :</b>
              <small class="value">'.$order['order_name'].'</small>
          </div>
          <div class="doc">
              <b class="label" style="font-size:17px;">วันที่ออก :</b>
              <small class="value">'.$order['date_time_order'].'</small>
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
          <th class="price">ราคาต้นทุนต่อลัง</th>
          <th class="price">ราคาต้นทุนทั้งหมด</th>
          <th class="price">ราคาค่าส่งต่อลัง</th>
          <th class="price">ราคาค่าส่งทั่งหมด</th>
          <th class="total">รวมยอด</th>
        </tr>
      </thead>
      <tbody>
    ';
  $x = 1;
  $total_qty = 0;
  $total_cost_price = 0;
  $total_cost_all = 0;
  $total_shipping_per = 0;
  $total_shipping_all = 0;
  $total_expenses = 0;

  while($rows = $sql_product->fetch_assoc()){
    $qty = (int)$rows['product_count'];
    $price = (float)($rows['product_price'] ?? 0);
    $cost_all = $price * $qty;

    $shipping_all = (float)($rows['shipping_cost'] ?? 0);
    $shipping_per = $qty > 0 ? $shipping_all / $qty : 0;

    $expenses = (float)($rows['expenses'] ?? 0);

    // สะสมยอดรวม
    $total_qty += $qty;
    $total_cost_price += $price;
    $total_cost_all += $cost_all;
    $total_shipping_per += $shipping_per;
    $total_shipping_all += $shipping_all;
    $total_expenses += $expenses;
     $html .= "
          <tr>
              <td class=\"name\">{$rows['new_productname']}</td>
              <td class=\"qty\">{$qty}</td>
              <td class=\"price\">".number_format($price,2,'.',',')."</td>
              <td class=\"price\">".number_format($cost_all,2,'.',',')."</td>
              <td class=\"price\">".number_format($shipping_per,2,'.',',')."</td>
              <td class=\"price\">".number_format($shipping_all,2,'.',',')."</td>
              <td class=\"total\">".number_format($expenses,2,'.',',')."</td>
          </tr>";
  //   $html .= "
  //   <tr>
  //       <td class=\"name\">{$rows['new_productname']}</td>
  //       <td class=\"qty\">{$rows['product_count']}</td>
  //       <td class=\"price\">".number_format($rows['product_price'] ?? 0,2,'.',',')."</td>
  //       <td class=\"price\">".number_format($rows['product_price'] * $rows['product_count'] ?? 0,2,'.',',')."</td>
  //       <td class=\"price\">".number_format($rows['shipping_cost'] / $rows['product_count'] ?? 0,2,'.',',')."</td>
  //       <td class=\"price\">".number_format($rows['shipping_cost'] ?? 0,2,'.',',')."</td>
  //       <td class=\"total\">".number_format($rows['expenses'] ?? 0,2,'.',',')."</td>
  //     </tr>
  // ";
  }
$html .= '
      </tbody>
      <tfoot>
      ';
        $html .= "
          <tr style=\"font-weight:bold;background-color:#f2f2f2;\">
              <td class=\"fontboldtfoot name\"><b>รวมทั้งหมด</b></td>
              <td class=\"fontboldtfoot qty\">".number_format($total_qty)."</td>
              <td class=\"fontboldtfoot price\">".number_format($total_cost_price,2,'.',',')."</td>
              <td class=\"fontboldtfoot price\">".number_format($total_cost_all,2,'.',',')."</td>
              <td class=\"fontboldtfoot price\">".number_format($total_shipping_per,2,'.',',')."</td>
              <td class=\"fontboldtfoot price\">".number_format($total_shipping_all,2,'.',',')."</td>
              <td class=\"fontboldtfoot total\">".number_format($total_expenses,2,'.',',')."</td>
          </tr>"; 
$html .= '
      </tfoot>
    </table>
  </div>
  <b class="footer">รวม</b>
  <table style="width:100%;border:1px solid gray;">
      <tr>
        <td class="" style="width:50%;border:none;">
          <b>'.$count_rows['total'].' รายการ</b>
        </td>
        <td class="qty" style="width:25%;border:none;">
          <b>'.$count_rows['product_count'].' ลัง</b>
        </td>
        <td class="total" style="width:25%;border:none;">
          <b>'.$count_rows['count_expenses'].' บาท</b>
        </td>
      </tr>
    </table>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>