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
    'format' => [110, 180],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hist_debt_id = $_GET['id_paydebt'];
$sql = $conn->query("SELECT * FROM custom_debtpaid WHERE id_debtpaid=$hist_debt_id");
$his_debt = $sql->fetch_assoc();
$custom = $conn->query("SELECT custome_name FROM orders_sell WHERE custome_name='{$his_debt["name_customer"]}' ORDER BY create_at DESC LIMIT 1");
$res_custom = $custom->fetch_assoc();
$type_debt = $conn->query("SELECT * FROM type_paydebt WHERE debtpay_id=$hist_debt_id");

$order_waspaid = $conn->query("SELECT ordersell_names,priceto_pay,amount_paid,status_pay FROM order_was_paid WHERE debtpaid_id=$hist_debt_id");

function formatThaiDateTime($datetime) {
    // แปลงเป็น timestamp
    $timestamp = strtotime($datetime);

    // Array ของวันและเดือนภาษาไทย
    $thaiDays = [
        "อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส."
    ];
    $thaiMonths = [
        "", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
        "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
    ];

    // ดึงข้อมูลจาก timestamp
    $dayOfWeek = date("w", $timestamp); // 0 (อาทิตย์) - 6 (เสาร์)
    $day = date("j", $timestamp);
    $month = date("n", $timestamp);
    $year = date("Y", $timestamp);
    $time = date("H:i", $timestamp);

    // format ที่ต้องการ
    return $thaiDays[$dayOfWeek] . " ที่ " . $day . " " . $thaiMonths[$month] . " " . $year . " เวลา " . $time . " น.";
}

$html = '
<style>
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
    width: 37%;
    text-align: left;
  }

  table.slip-table th.price,
  table.slip-table td.price,
  table.slip-table th.qty,
  table.slip-table td.qty,
  table.slip-table th.total,
  table.slip-table td.total {
    width: 17%;
  }
</style>
<div>
    <div style="width:100%;display:flex">
      <div style="float: left; width: 15%">
        <img src="../../../assets/img/Jbox-logo.jpg" width="50" height="50" />
      </div>
      <div style="float: right;">
        <h2 style="float: left;padding:0%; margin:0%;width:50%;">หลักฐานจ่ายค้างชำระ</h2>
        
        <p style="padding:0%; margin:0%;width:100%;"><b>( คลังสินค้า JBok)</b> จำหน่ายกล่องพัศดุราคาโรงงาน</p>
      </div>
      <hr/>
    </div>
    <div style="width:100%;display:flex;font-size:20px;">
        <b style="float:left;width:20%;padding:0%; margin:0%;">รหัสการจ่าย :</b> &nbsp; [ '.$his_debt['serial_number'].' ]
        <div style="width:100%;display:flex;">
          <div style="float:left; width:50%;">
            <b>ชื่อผู้ชำระ : </b>'.$his_debt['name_customer'].'
          </div>
          
        </div>
        
        <div style="width:100%;display:flex;font-size:20px;"> 
          <p style="font-size:20px;font-weight: bold;float: left;width:57%;padding:0%; margin:0%;"> ประเภทการจ่าย : [ ';
            while($rows = $type_debt->fetch_assoc()){
                $html .= $rows['type_pay'] .', ';
            }
          $html.=' ]</p>
        </div>
        <div style="width:100%;display:flex;font-size:20px;height:20px">
            <p style="font-size:20px;font-weight: bold;float: left;width:100%;padding:0%; margin:0%">หมายเหตุ : ['.$his_debt['text_reason'].']</p>
        </div>
        <hr/>
            <table class="slip-table">
              <thead>
                <tr style="background-color:#ff751a;">
                  <th class="name">รหัสคำสังขาย</th>
                  <th class="price">ราคาเดิมที่ค้าง</th>
                  <th class="qty">จำนวนที่จ่าย</th>
                  <th class="total">คงเหลือ</th>
                  <th class="total">สถานะ</th>
                </tr>
              </thead>
              <tbody>
';
  $is = 1;
  while($rows = $order_waspaid->fetch_assoc()){
    $debt_balane = $rows['priceto_pay'] - $rows['amount_paid'];
    $html .="
      <tr>
        <td class=\"name\"><b>{$rows['ordersell_names']}</b></td>
        <td class=\"price\">{$rows['priceto_pay']}</td>
        <td class=\"qty\">{$rows['amount_paid']}</td>
        <td class=\"total\">{$debt_balane}</td>
        <td class=\"total\">{$rows['status_pay']}</td>
      </tr>
    ";
    $is++;
  }
$html .='
              </tbody>
            </table>
        <hr/>
    </div>
    
    <div style="width:100%;display:flex;font-size:20px;">
      <div style="width:100%;display:flex;font-size:20px;">
        <p style="font-size:20px;font-weight: bold;float: left;width:40%;padding:0%; margin:0%"> ยอดค้างชำระ : </p>
        <p style="font-size:20px;font-weight: bold;float: right%;width:50%;text-align:right;padding:0%; margin:0%"> '.number_format($his_debt['debtpaid_balance'] + $his_debt['count_debtpaid'] ?? 0,2,'.',',').' บาท</p>
      </div>
      <div style="width:100%;display:flex;font-size:20px;">
        <p style="font-size:20px;font-weight: bold;float: left;width:40%;padding:0%; margin:0%"> ยอดที่ชำระ : </p>
        <p style="font-size:20px;font-weight: bold;float: right%;width:50%;text-align:right;padding:0%; margin:0%"> '.number_format($his_debt['count_debtpaid'] ?? 0,2,'.',',').' บาท</p>
      </div>
      <div style="width:100%;display:flex;font-size:20px;">
        <p style="font-size:20px;font-weight: bold;float: left;width:40%;padding:0%; margin:0%"> ยอดคงเหลือ : </p>
        <p style="font-size:20px;font-weight: bold;float: right%;width:50%;text-align:right;padding:0%; margin:0%"> '.number_format($his_debt['debtpaid_balance'] ?? 0,2,'.',',').' บาท</p>
      </div>
      <hr/>
      <br/>
      <div style="width:100%;display:flex;font-size:20px;">
        <p style="font-size:20px;font-weight: bold;float: left;width:27%;padding:0%; margin:0%;"> วันที่จ่าย : </p>
        <p style="font-size:20px;font-weight: bold;float: right;width:70%;padding:0%; margin:0%;text-align:right;"> '.formatThaiDateTime($his_debt['datetime_pays']).'</p>
      </div>
      
    </div>
</div>
';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>