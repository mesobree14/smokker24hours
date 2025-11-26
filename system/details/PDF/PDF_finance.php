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
    'format' => [160, 210],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
    $capitals = mysqli_query($conn,"SELECT SUM(count_capital) as countcapital FROM capital");
    $acc_capintal = mysqli_fetch_assoc($capitals);
    
    $used_capital = mysqli_query($conn,"SELECT SUM(totalcost_order) as costordercount FROM order_box");
    $acc_usecapital = mysqli_fetch_assoc($used_capital);
    $quall = "SELECT SUM(is_totalprice) as is_prices, SUM(count_totalpays) as custom_pay, SUM(count_stuck) as countstuck FROM orders_sell";
    $is_quall = mysqli_query($conn,$quall);
    $is_accquall = mysqli_fetch_assoc($is_quall);
    $costordercount = $acc_usecapital['costordercount'] ?? 0;
    
    $countcapital = $acc_capintal['countcapital'] ?? 0;
    $countstuck = $is_accquall['countstuck'] ?? 0;
    $sql_useprofit = mysqli_query($conn,"SELECT SUM(count_withdraw) as use_prefit FROM withdraw");
    $acc_useprofit = mysqli_fetch_assoc($sql_useprofit);
    
    $sql_capital = mysqli_query($conn,"SELECT COUNT(*) AS total_capital,product_name, SUM(expenses) / SUM(product_count) AS avg_rate_price FROM stock_product GROUP BY product_name");
    $sql_profit = mysqli_query($conn,
      "SELECT COUNT(*) AS total_profit,list_productsell.productname,name_product.product_name,
        SUM(list_productsell.tatol_product) AS total_product, SUM(list_productsell.price_to_pay) AS price_sell 
        FROM list_productsell LEFT JOIN name_product ON name_product.id_name = list_productsell.productname GROUP BY productname");
    $capitalData = [];
    $sum_totalsell = 0;
    $sum_pricesell = 0;
    $sun_pricebuy = 0;
    $average_pay = 0;
    $resutl_profit = 0;
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
  table.slip-table th.num,
  table.slip-table td.num {
    width: 8%;
  }

  table.slip-table th.name,
  table.slip-table td.name {
    width: 20%;
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
  table.slip-table td.result-name {
    width: 25%;
    text-align: left;
    border:none;
  }
  table.slip-table td.resutl-qty{
    width: 15%;
    border:none;
  }
  table.slip-table td.resutl-qtys{
    width: 15%;
  }
  .fontbold{
    font-weight: bold;
    color:blue;
    font-size:18px;
  }
  table.price-table{
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }
  table.price-table th,
  table.price-table td {
    border: 1px solid #000;
    padding: 6px;
    text-align: center;
  }
  </style>
  <div>
    <div style="width:100%;display:flex">
      <h2 style="text-align:center;padding:0%; margin:0%;">สรุปการเงิน</h2>
      <hr/>
    </div>
    <div style="width:100%">
      <table class="slip-table">
        <thead>
          <tr style="background-color:#ff9933;">
            <th class="num">ลำดับ</th>
            <th class="name">สินค้า</th>
            <th class="price">ทุนเฉลี่ยต่อลัง <span style="color:red">*</span></th>
            <th class="qty">จำนวนลังที่ขาย</th>
            <th class="total">รายรับ</th>
            <th class="total">ต้นทุน</th>
            <th class="total">กำไร</th>
          </tr>
        </thead>
        <tbody>
        ';
          while($row = mysqli_fetch_assoc($sql_capital)){
            $capitalData[$row['product_name']] = [
              'avg_rate_price' => $row['avg_rate_price'],
              'total_capital' => $row['total_capital']
            ];
          }
          $in = 1;
          while($row = mysqli_fetch_assoc($sql_profit)){
            $product = $row['productname'];
            $product_names = $row['product_name'];
            $totalProduct = $row['total_product'];
            $totalSell = $row['total_profit'];
            $priceSell = $row['price_sell'];
            $avgRate = isset($capitalData[$product]) ? $capitalData[$product]['avg_rate_price'] : 0;
            $totalPay = isset($capitalData[$product]) ? $capitalData[$product]['total_capital'] : 0;
            $totalCost = $avgRate * $totalProduct;
          
            $sum_totalsell += $totalProduct;
            $sum_pricesell += $priceSell;
            $sun_pricebuy += $totalCost; //ต้นทุนที่ได้กลับมา
            $resutl_profit += ($priceSell - $totalCost);
            $kumrai = $priceSell - $totalCost;
            $html .= '
              <tr>
                  <td class="num">'.$in.'</td>
                  <td class="name">'.$product_names.'</td>
                  <td class="price">'.number_format($avgRate ?? 0,2,'.',',').'</td>
                  <td class="qty">'.$totalProduct.'</td>
                  <td class="total">'.number_format($priceSell ?? 0,2,'.',',').'</td>
                  <td class="total">'.number_format($totalCost ?? 0,2,'.',',').'</td>
                  <td class="total">'.number_format($kumrai ?? 0,2,'.',',').'</td>
                </tr>
            ';
            $in++;
            //echo "สินค้า: $product จำนวนครั้งซื้อ $totalPay |จำนวนครั้งขาย $totalSell | จำนวนขาย: $totalProduct | รายรับ: $priceSell | ทุนเฉลี่ย: $avgRate | ต้นทุนรวมทั้งหมด: $totalCost | กำไร:$kumrai <br><div class='border border-success col-12'></div><br/>";
          }
          $sql_debt = mysqli_query($conn,"SELECT SUM(count_debtpaid) AS count_debtpaid FROM custom_debtpaid");
          $acc_debt = mysqli_fetch_assoc($sql_debt);
          $pay_debt = $acc_debt['count_debtpaid'] ?? 0;
      
          $res_pricecapital = ($costordercount - $sun_pricebuy);
          $res_pricedebt = ($countstuck - $pay_debt);
          $res_circulating =$countcapital - ($res_pricecapital + $res_pricedebt);
$html .= '
            <tr>
              <td class="num;" style="border-right: none;"></td>
              <td class="fontbold name" style="border-bottom:1px solid black;border-left: none;border-right: none;">ผลรวม</td>
              <td class="price" style="border-bottom:1px solid black;border-left: none;border-right: none;"></td>
              <td class="fontbold qty" >'.number_format($sum_totalsell).'</td>
              <td class="fontbold total">'.number_format($sum_pricesell ?? 0,2,'.',',').'</td>
              <td class="fontbold total">'.number_format($sun_pricebuy ?? 0,2,'.',',').'</td>
              <td class="fontbold total">'.number_format($resutl_profit ?? 0,2,'.',',').'</td>
            </tr>
        </tbody>
      </table>
        <div style="width:100%;margin-top:5px;">
          <span style="color:red;font-size:20px;">&#42;</span> การคำนวนค่าเฉลี่ยต้นทุนแต่ละลัง คือ เอาจำนวนสินค้าที่ซื้อมาทั้งหมดมาหารด้วยราคารวมทั้งทั้งหมด เช่นจำนวนสิ้นค้า J10 ทั้งหมด 10 ลัง รวมราคาทั้งหมด 10000 บาท 10000 / 10 = (1000) ราคาต้นทุนคือ1000 บ.
        </div>
        <div style="width:100%;display:flex;">
        <div style="width: 49%;float:left;">
          <table class="price-table" style="margin-top:5%;">
              <thead>
                <tr style="background-color:#ff9933;">
                  <th style="font-weight: bold;">รายการ</th>
                  <th style="font-weight: bold;">จำนวน</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="color:blue;font-weight:bold">จำนวนทุน</td>
                  <td>'.number_format($countcapital,2,'.',',').'</td>
                </tr>
                <tr>
                  <td style="color:blue;font-weight:bold">กำลังใช้</td>
                  <td>'.number_format($res_pricecapital ?? 0,2,'.',',').'</td>
                </tr>
                <tr>
                  <td style="color:blue;font-weight:bold">ทุนที่ยังใช้ได้</td>
                  <td>'.number_format($res_circulating ?? 0,2,'.',',').'</td>
                </tr>
              </tbody>
          </table>
          </div>
          <div style="width: 49%;float:right;">
          <table class="price-table" style="margin-top:5%;">
              <thead>
                <tr style="background-color:#ff9933;">
                  <th style="font-weight: bold;">รายการ</th>
                  <th style="font-weight: bold;">จำนวน</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="color:blue;font-weight:bold">จำนวนค้างชำระ</td>
                  <td>'.number_format($res_pricedebt ?? 0,2,'.',',').'</td>
                </tr>
                <tr>
                    <td style="color:blue;font-weight:bold">เบิกถอนไปแล้ว</td>
                    <td>'.number_format($acc_useprofit['use_prefit'] ?? 0,2,'.',',').'</td>
                </tr>
                <tr>
                    <td style="color:blue;font-weight:bold">สามารถใช้ได้</td>
                    <td>'.number_format($resutl_profit - $acc_useprofit['use_prefit'] ?? 0,2,'.',',').'</td>
                </tr>
              </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  ';
$mpdf->WriteHTML($html);
$mpdf->Output();
?>