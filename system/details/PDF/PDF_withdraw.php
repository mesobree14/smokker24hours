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
    'format' => [70, 130],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id_withdraw = $_GET['withdraw_id'];
$query = $conn->query("SELECT * FROM withdraw WHERE withdraw_id=$id_withdraw");
$withdraw_price = $query->fetch_assoc();

$html = '
<style>
</style>';

$html .='
<div>
  <div class="" style="">
    <div style="float: left; width: 40%; margin-left:5px;">
      <img src="../../../assets/img/Jbox-logo.jpg" width="40" height="40" />
    </div>
    <div style="float: right; width: 50%;display: table;">
      <div style="display: table-cell; vertical-align: middle; text-align: center;">
        <h2 style="text-align: left;">ใบเสร็จเบิกถอน</h2>
      </div>
    </div>
  </div>
  <hr/>
  <div style="width:100%;display:flex;font-size:20px;">
    <div style="width:100%;display:flex;font-size:20px;">
      <p style="font-size:20px;font-weight: bold;float: left;width:48%;padding:0%; margin:0%"> จำนวนเงินที่มี : </p>
      <p style="font-size:20px;font-weight: bold;float: right%;width:50%;text-align:right;padding:0%; margin:0%"> '.$withdraw_price['withdraw_balance'].' บาท</p>
    </div>
    <div style="width:100%;display:flex;font-size:20px;">
      <p style="font-size:20px;font-weight: bold;float: left;width:50%;padding:0%; margin:0%"> จำนวนเงินที่เบิก : </p>
      <p style="font-size:20px;font-weight: bold;float: right%;width:48%;text-align:right;padding:0%; margin:0%"> '.$withdraw_price['count_withdraw'].' บาท</p>
    </div>
    <div style="width:100%;display:flex;font-size:20px;">
      <p style="font-size:20px;font-weight: bold;float: left;width:48%;padding:0%; margin:0%"> จำนวนเงินที่เหลือ : </p>
      <p style="font-size:20px;font-weight: bold;float: right%;width:50%;text-align:right;padding:0%; margin:0%"> '.$withdraw_price['withdraw_balance'] - $withdraw_price['count_withdraw'].' บาท</p>
    </div>
    <hr/>
    <div style="width:100%;display:flex;font-size:20px;">
      <p style="font-size:20px;font-weight: bold;float: left;width:29%;padding:0%; margin:0%;"> วันที่เบิก : </p>
      <p style="font-size:20px;font-weight: bold;float: right;width:70%;text-align:right;padding:0%; margin:0%">  '.$withdraw_price['date_withdrow'].'</p>
    </div>
    <hr/>
    <div style="width:100%;display:flex;font-size:20px;">
      <p style="padding:0%; margin:0%;font-weight: bold;color:green;">เหตุผล :</p>
      <p style="padding:0%; margin:0%;">'.$withdraw_price['reason'].'</p>
    </div>
  </div>
</div>';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>