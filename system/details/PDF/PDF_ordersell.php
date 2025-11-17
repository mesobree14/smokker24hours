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
    'format' => [140, 200],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 0, 
    'margin_header' => 0,
    'margin_footer' => 0,
]);

$svgqr = file_get_contents(__DIR__ . '/../../../db/QR-code.svg');

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ordersell_id = $_GET['ordersell_id'];
$sql = "SELECT * FROM orders_sell WHERE id_ordersell='$ordersell_id'";
$query = $conn->query($sql);
$order = $query->fetch_assoc();

$typepay = "SELECT ordersell_id,list_typepay FROM sell_typepay WHERE ordersell_id='$ordersell_id'";
$querytypepay = $conn->query($typepay);

$sql_items = "SELECT 
  LP.list_sellid,LP.ordersell_id,LP.productname,LP.rate_customertype,LP.type_custom,LP.tatol_product,LP.price_to_pay,
  NP.product_name,NP.id_name
  FROM list_productsell LP LEFT JOIN name_product NP ON LP.productname = NP.id_name WHERE ordersell_id='$ordersell_id'";
$query_item = $conn->query($sql_items);

$sql_sun = "SELECT COUNT(*) AS total, SUM(tatol_product) AS totalproduct, SUM(price_to_pay) AS prices FROM list_productsell WHERE ordersell_id='$ordersell_id'";
$is_sum = $conn->query($sql_sun);
$count_rows = $is_sum->fetch_assoc();

$type_customer = [];

function setTypeCustom($value){
  switch($value){
    case "price_customer_dealer":
      return "ตัวแทนจำหน่าย";
      break;
    case "price_levels_one";
      return "ลูกค้า vip";
      break;
    case "price_customer_frontstore":
      return "ลูกค้าหน้าร้าน";
      break;
    case "price_customer_deliver":
      return "การจัดส่ง";
      break;
    default:
      return $value;
  }
}

function status_pays($totalprice,$custompay,$countstuck){
  if($totalprice == $custompay){
    return "<b style=\"color: #4CAF50;\">จ่ายครบถ้วน</b>";
  }elseif($totalprice == $countstuck){
    return "<b style=\"color: #ff1a1a;\">ติดค้าง $countstuck บาท</b>";
  }else{
    return "<b style=\"color: #ff1a1a;\">จ่ายแล้วแต่ยังติดค้าง $countstuck บาท</p>";
  }
}


$html = '
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
    width: 55%;
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
</style>
  <div class="" style="">
    <div style="float: left; width: 55%; margin-left:5px">
      
    </div>
    <div style="float: right; width: 40%;">
      <h3 style="text-align: right;">ใบเสร็จคำสั่งขาย</h3>
    </div>
  </div>
  <div style="width:100%">
    <div class="component">
        <div class="left">
          <div class="doc">
              <b class="label" style="font-size:17px;">ร้าน :</b>
              <small class="value">smokker24hours</small>
          </div>
          <div class="doc">
          </div>
        </div>
        <div class="right" style="background-color:#ffb3ff;">
          <div class="doc">
              <b class="label" style="font-size:17px;">รหัสการขาย :</b>
              <small class="value">'.$order['ordersell_name'].'</small>
          </div>
          <div class="doc">
              <b class="label" style="font-size:17px;">วันที่ออก :</b>
              <small class="value">'.$order['date_time_sell'].'</small>
          </div>
        </div>
    </div>
  </div>
  <div style="height: 220px;">
  <table class="slip-table">
    <thead>
      <tr style="background-color:#ffb3ff;">
        <th class="name">รายการสินค้า</th>
        <th class="price">ราคาต่อลัง</th>
        <th class="qty">จำนวน</th>
        <th class="total">ราคารวม</th>
      </tr>
    </thead>
    <tbody>
    ';
$i = 1;
while($rows = $query_item->fetch_assoc()){
  $type_customer[] = $rows['type_custom'];
  $html .= "
    <tr>
        <td class=\"name\">{$rows['product_name']}</td>
        <td class=\"price\">{$rows['rate_customertype']}</td>
        <td class=\"qty\">{$rows['tatol_product']}</td>
        <td class=\"total\">{$rows['price_to_pay']}</td>
      </tr>
  ";
  $i++;
}
$unique = array_unique($type_customer);
  $html .='
    </tbody>
  </table>
  </div>
  <b class="footer">รวม</b>
    <table style="width:100%;border:1px solid gray;">
      <tr>
        <td class="" style="width:50%;border:none;">
          <b>'.$count_rows['total'].' รายการ</b>
        </td>
        <td class="qty" style="width:25%;border:none;">
          <b>'.$count_rows['totalproduct'].' ลัง</b>
        </td>
        <td class="total" style="width:25%;border:none;">
          <b>'.$count_rows['prices'].' บาท</b>
        </td>
      </tr>
    </table>
    <div class="component" style="margin-top:2%;">
      <div class="left-custom">
        <div style="width:100%;text-align:right;">';
          $html .= status_pays($order['is_totalprice'],$order['count_totalpays'],$order['count_stuck']);
        $html .='
        </div>
      </div>
      <div style="float: right;width: 50%;box-sizing: border-box;">
        <div class="doc" style="border:1px solid gray;background-color:#ffb3ff;padding:2%;">
            <b class="label">&nbsp; จำนวนเงินที่จ่าย : </b>
            <b class="value">'.$order['count_totalpays'].' บาท</b>
        </div>
      </div>
    </div>
    <div class="component" style="border-bottom:1px solid gray">
      <div class="left-custom">
        <div class="doc">
            <b class="label" style="font-size:17px;">ผู้ซื้อ :</b>
            <small class="value">'.$order['custome_name'].'</small>
        </div>
        <div class="doc">
            <b class="label" style="font-size:17px;">ประเภทลูกค้า :</b>
            <small class="value">[ '; 
            foreach($unique as $val){
              $html .= setTypeCustom($val);
            }
            $html .=' ]</small>
        </div>
      </div>
      <div class="right-custom">
        <div class="doc">
            <b class="label" style="font-size:17px;">เบอร์โทร :</b>
            <small class="value"></small>
        </div>
         <div class="doc">
            <b class="label" style="font-size:17px;">สถานะการจ่าย :</b>
            <small class="value">';
            while($row = $querytypepay->fetch_assoc()){
              $html .= $row['list_typepay'] .', ';
            }
            
 $html .=  '</small>
        </div>
      </div>
    </div>
    <div class="component">
    </div>
  </div>
';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>