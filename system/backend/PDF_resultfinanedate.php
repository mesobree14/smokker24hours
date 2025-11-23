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
date_default_timezone_set("Asia/Bangkok");
$day_add = date('Y-m-d H:i:s');

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
    'format' => [300, 150],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

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
    color:black;
    font-weight: bold;
  }

  table.slip-table th.price,
  table.slip-table td.price,
  table.slip-table th.qty,
  table.slip-table td.qty,
  table.slip-table th.total,
  table.slip-table td.total {
    width: 10%;
    color:black;
  }
  table.slip-table th.total-blue,
  table.slip-table td.total-blue {
    width: 10%;
    color:blue;
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
  .fontboldtfoot{
    font-weight: bold;
    color:black;
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
  </style>';

$html .='
<div>
  <div class="" style="">
    <div style="float: left; width: 55%; margin-left:5px">
    </div>
    <div style="float: right; width: 40%;">
      <h3 style="text-align: right;">รายการในสต็อก(ล็อต)</h3>
    </div>
      <div style="display:flex;width:100%;">
        ข้อมูลระหว่างวันที่ '.$start_date.' ถึง '.$end_date.'
      </div>
      <div style="width:100%">
      <table class="slip-table">
        <thead>
          <tr style="background-color:#ff9933;">
            <th class="name">Lot No</th>
            <th class="price">รายการ</th>
            <th class="qty">จำนวนในสต็อก</th>
            <th class="total">จำนวนขาย</th>
            <th class="total">คงเหลือ</th>
            <th class="total">ต้นทุนทั้งหมด</th>
            <th class="total">ต้นทุนกำลังใช้</th>
            
            <th class="total">ราคากลางทั้งหมด</th>
            <th class="total-blue">คืนทุน</th>
            <th class="total">รายรับ</th>
            <th class="total-blue">กำไร</th>
            
          </tr>
        </thead>
        <tbody>';
      $get_product = "SELECT COUNT(*) AS total_lot, NP.product_name AS in_productname, SP.product_id, SP.product_name, 
          SP.product_price,SP.price_center,SP.shipping_cost,SP.expenses, SUM(SP.product_count) AS sum_product, SP.lot_number FROM stock_product SP 
          LEFT JOIN name_product NP ON SP.product_name = NP.id_name GROUP BY SP.product_id, SP.lot_number";
          $query = $conn->query($get_product);
          $data = [];
          while($row = $query->fetch_assoc()){
            $data[] = $row;
          }

          $get_productsell = "SELECT LP.list_sellid, LP.productname, NP.product_name,
            LP.level_selltype,LP.rate_customertype,LP.tatol_product,LP.price_to_pay
            FROM list_productsell LP LEFT JOIN name_product NP ON LP.productname = NP.id_name 
            LEFT JOIN orders_sell OS ON OS.id_ordersell = LP.ordersell_id
            WHERE OS.date_time_sell BETWEEN '$start_date' AND '$end_date'
            ORDER BY LP.list_sellid ASC";
            $query_sell = $conn->query($get_productsell);
            $data_sell = [];
            while($is_row = $query_sell->fetch_assoc()){
              $data_sell[] = $is_row;
            }
          
            $lot_resutl = [];

        foreach($data as $stock){
          $lotQty = $stock['sum_product'];
          $remainQty = $lotQty;
          $soldQtry = 0;
          $totalSellValue = 0;
          $rate_costometype = 0;
          $lot_code = $stock['lot_number'];
          $p_idname = $stock['product_name'];
          $p_name = $stock['in_productname'];
          $p_id = $stock['product_id'];
        
          foreach($data_sell as &$sales){
            if($sales['productname'] !== $p_idname) continue;
            if($remainQty <= 0) break;

            $saleQty = (int)$sales['tatol_product'];
            $rate_costometype = (float)$sales['rate_customertype'];
            if($saleQty > 0){
              if($remainQty >= $saleQty){
                $soldFromLot = $saleQty;
                $remainQty -= $saleQty;
                $sales['tatol_product'] = 0;
              }else{
                $soldFromLot = $remainQty;
                $sales['tatol_product'] -= $remainQty;
                $remainQty = 0;
              }
              $soldQtry += $soldFromLot;
              $totalSellValue += $soldFromLot * $rate_costometype;
            }
          
          }
          $product_shipping = $stock['product_price'] + ($stock['shipping_cost'] / $lotQty);
          $capital_all = $product_shipping * $lotQty; //ต้นทุนรวมทั้งหมด
          $capital_using = $product_shipping * $remainQty; //ทุนกำลังใช้
          $capitalall_return = $product_shipping * $soldQtry; //ทุนที่ได้คืนมา

          $price_center_all = $stock['price_center'] * $lotQty;
          $price_cnter_using = $stock['price_center'] * $remainQty;
          $price_center_return = $stock['price_center'] * $soldQtry;

          $pricecenter_delcaptialshiipin = $price_center_return - $capitalall_return; //ส่วนต่างราคากลางกับต้นทุนที่ได้คืนมา
          $seller_all = $rate_costometype * $soldQtry; // รายได้จากการขายทั้งหมด
          $profit_all = $seller_all - $price_center_return; //กำไรทั้งหมดหลังหักต้นทุนและค่าใช้จ่าย


          $lot_resutl[] = [
            'id' => $p_id,
            'p_name' =>$p_name,
            'id_pname'=> $p_idname,
            'lot_no'=> $lot_code,
            'count_inlot' => $lotQty, //จำนวนฝน lot
            'total_sell' => $soldQtry, // จำนวนขาย
            'remain_qty' => $remainQty, //คงเหลือ
            'product_price' => $stock['product_price'], //ราคาเริ่มต้นต่อลัง
            'product_shipping' => $product_shipping, //ราคาต้นทุนต่อลัง รวมค่าขนส่ง
            'product_priceAll' => $capital_all,//ต้นทุน + ค่าส่ง รวมทั้งหมด
            'capital_using' => $capital_using, //ทุนกำลังใช้
            'capitalall_return' => $capitalall_return, //ทุนที่ได้คืนมา

            'price_center' => $stock['price_center'], // ราคากลางต่อลัง
            'price_centerAll' => $stock['price_center'] * $lotQty, //ราคากลาง รวมทั้งหมด
            'price_cnter_using' => $price_cnter_using, //ราคากลาง กำลังใช้
            'price_center_return' => $price_center_return, //ราคากลาง ที่ได้คืนมา
            'pricecenter_delcaptialshiipin' => $pricecenter_delcaptialshiipin, //ส่วนต่างราคากลางกับต้นทุนที่ได้คืนมา
            'shipping_one' => $stock['shipping_cost'] / $lotQty, //ค่าขนส่งต่อลัง
            'shipping_cost' => $stock['shipping_cost'], //ค่าขนส่งรวม
            'rate_sell' => $rate_costometype,
            'expenses' => $seller_all, //รายได้จากการขายทั้งหมด
            'profit_all' => $profit_all, //กำไรทั้งหมดหลังหักต้นทุนและค่าใช้จ่าย
            'sell' => 'sell',
            'total_sell_value' => $totalSellValue
          ];
        }

        $grouped = [];
        $lot_itemcount = [];
        foreach($lot_resutl as $lot){
          $code = $lot['lot_no'];
          if(!isset($grouped[$code])){

            $grouped[$code] = [
              'lot_code' => $code,
              'count' => 0,
              'total_inlot' => 0,
              'total_sell' => 0,
              'remain' => 0,
              'priceAll' =>0, // ต้นทุนรวมทั้งหมด
              'capital_using' =>0, //ทุนกำลังใช้
              'capitalall_return' =>0, //ทุนที่ได้คืนมา
              'pricecenter_All' => 0,
              'price_cnter_using' =>0,
              'price_center_return' =>0,
              'pricecenter_delcaptialshiipin' =>0, //ส่วนต่างราคากลางกับต้นทุนที่ได้คืนมา
              'shipping_cost' =>0,
              'expenses' => 0,
              'profit_all' => 0,
              'price_seller' => 0,
            ];
            $lot_itemcount[$code] = 0;
          }
          $lot_itemcount[$code]++;

          $grouped[$code]['count'] = $lot_itemcount[$code];
          $grouped[$code]['total_inlot'] += $lot['count_inlot'];
          $grouped[$code]['total_sell'] += $lot['total_sell'];
          $grouped[$code]['remain'] += $lot['remain_qty'];
          $grouped[$code]['priceAll'] += $lot['product_priceAll'];
          $grouped[$code]['capital_using'] += $lot['capital_using'];
          $grouped[$code]['capitalall_return'] += $lot['capitalall_return'];
          $grouped[$code]['pricecenter_All'] += $lot['price_centerAll'];
          $grouped[$code]['price_cnter_using'] += $lot['price_cnter_using'];
          $grouped[$code]['price_center_return'] += $lot['price_center_return'];
          $grouped[$code]['pricecenter_delcaptialshiipin'] += $lot['pricecenter_delcaptialshiipin'];
          $grouped[$code]['shipping_cost'] += $lot['shipping_cost'];
          $grouped[$code]['expenses'] += $lot['expenses'];
          $grouped[$code]['profit_all'] += $lot['profit_all'];
          $grouped[$code]['price_seller'] += $lot['total_sell_value'];
        }

        $grouped = array_values($grouped);
       $issum_count = 0;
       $issum_inlot = 0;
       $issum_totalsell = 0;
       $issum_remain = 0;
       $iscapital_all = 0;
       $iscapital_using = 0;
       $iscapital_return = 0;
       $ispricecenter_all = 0;
       $ispricecenter_using = 0;
       $ispricecenter_return = 0;
       $ispricecenter_delcaptialshiipin = 0;
       $isseller_all = 0;
       $isprofit_all = 0;

        foreach($grouped as $res){
    $html .= '
            <tr>
              <td class="fontbold name" >'.$res['lot_code'].'</td>
              <td class="fontbold total">'.number_format($res['count']).'</td>
              <td class="fontbold total">'.number_format($res['total_inlot']).'</td>
              <td class="fontbold total">'.number_format($res['total_sell']).'</td>
              <td class="fontbold total">'.number_format($res['remain']).'</td>
              <td class="fontbold total">'.number_format($res['priceAll']).'</td>
              <td class="fontbold total">'.number_format($res['capital_using']).'</td>
              
              <td class="fontbold total">'.number_format($res['pricecenter_All']).'</td>
              <td class="fontbold total-blue">'.number_format($res['capitalall_return']).'</td>
              <td class="fontbold total">'.number_format($res['price_center_return']).'</td>
              <td class="fontbold total-blue">'.number_format($res['pricecenter_delcaptialshiipin']).'</td>
              
            </tr>';
            $issum_count        += $res['count'];
            $issum_inlot  += $res['total_inlot'];
            $issum_totalsell   += $res['total_sell'];
            $issum_remain       += $res['remain'];
            $iscapital_all      += $res['priceAll'];
            $iscapital_using    += $res['capital_using'];
            $iscapital_return   += $res['capitalall_return'];
            $ispricecenter_all  += $res['pricecenter_All'];
            $ispricecenter_using+= $res['price_cnter_using'];
            $ispricecenter_return+= $res['price_center_return'];
            $ispricecenter_delcaptialshiipin += $res['pricecenter_delcaptialshiipin'];
            $isseller_all       += $res['expenses'];
            $isprofit_all       += $res['profit_all'];
      }
    $html .='
      
        </tbody>
        <tfoot>
          <tr style="background-color:#F5DEB3;">
              <td class="fontboldtfoot name" >ทั้งหมด</td>
              <td class="fontboldtfoot total">'.number_format($issum_count).'</td>
              <td class="fontboldtfoot total">'.number_format($issum_inlot).'</td>
              <td class="fontboldtfoot total">'.number_format($issum_totalsell).'</td>
              <td class="fontboldtfoot total">'.number_format($issum_remain).'</td>
              <td class="fontboldtfoot total">'.number_format($iscapital_all).'</td>
              <td class="fontboldtfoot total">'.number_format($iscapital_using).'</td>
              
              <td class="fontboldtfoot total">'.number_format($ispricecenter_all).'</td>
              <td class="fontboldtfoot total-blue">'.number_format($iscapital_return).'</td>
              <td class="fontboldtfoot total">'.number_format($ispricecenter_return).'</td>
              <td class="fontboldtfoot total-blue">'.number_format($ispricecenter_delcaptialshiipin).'</td>
              
            </tr>
        </tfoot>
      </table>
  </div>
  <br/>
  <div style="width:100%;display:flex">
      <b>ปริ้นเมื่อ : '.$day_add.'</b>
  </div>
  </div>
</div>';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>