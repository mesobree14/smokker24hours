<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
$lot_number = $_GET['lot_number'];
require_once __DIR__ . '/../../../vendor/autoload.php';
$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

date_default_timezone_set("Asia/Bangkok");
$day_add = date('Y-m-d H:i:s');

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
    'format' => [300, 120],
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
]);

$conn = new mysqli("localhost", "root", "", "smokker_stock");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
  table.slip-table th.num,
  table.slip-table td.num {
    width: 8%;
  }

  table.slip-table th.name,
  table.slip-table td.name {
    width: 30%;
    text-align: left;
    color:purple;
    font-weight: bold;
  }

  table.slip-table th.price,
  table.slip-table td.price,
  table.slip-table th.qty,
  table.slip-table td.qty,
  table.slip-table th.total,
  table.slip-table td.total {
    width: 10%;
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

  .fontboldtfoot{
    font-weight: bold;
    color:black;
    font-size:18px;
  }
  </style>';

$html .='
<div>
  <div class="" style="">
    
    <div style="float: left; width: 100%;">
      <h2 style="text-align: left;">รายการในสต็อก('.$lot_number.')</h2>
    </div>
        <div style="width:100%">
      <table class="slip-table">
        <thead>
          <tr style="background-color:#ffb3ff;">
            <th class="price">สินค้า</th>
            <th class="qty">ซื้อ</th>
            <th class="total">ขาย</th>
            <th class="total">คงเหลือ</th>
            <th class="total">ราคา/ลัง</th>
            <th class="total">ค่าส่ง/ลัง</th>
            <th class="total">ต้นทุน/ลัง</th>
            <th class="total">ราคากลาง/ลัง</th>
            <th class="total">ส่วนต่าง/ลัง</th>
            <th class="total">ราคาสินค้าทั้งหมด</th>
            <th class="total">ค่าจัดส่งทั้งหมด</th>
            <th class="total">ราคาสั่งซื้อ</th>
            <th class="total">ทุนที่กำลังใช้</th>
            <th class="total">รายรับ</th>
            <th class="total">คืนทุน</th>
            <th class="total">กำไร</th>
          </tr>
        </thead>
        <tbody>';
      $get_product = "SELECT COUNT(*) AS total_lot, NP.product_name AS in_productname, SP.product_id, SP.product_name,SP.create_at,
          SP.product_price,SP.price_center,SP.shipping_cost,SP.expenses, SUM(SP.product_count) AS sum_product, SP.lot_number FROM stock_product SP 
          LEFT JOIN name_product NP ON SP.product_name = NP.id_name WHERE SP.lot_number='$lot_number' GROUP BY SP.product_id, SP.lot_number";
          $query = $conn->query($get_product);
          $data = [];
          while($row = $query->fetch_assoc()){
            $data[] = $row;
          }

          $get_productsell = "SELECT LP.list_sellid, LP.productname, NP.product_name,
            LP.level_selltype,LP.rate_customertype,LP.tatol_product,LP.price_to_pay
            FROM list_productsell LP LEFT JOIN name_product NP ON LP.productname = NP.id_name ORDER BY LP.list_sellid ASC";
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
          $lot_code = $stock['lot_number'];
          $p_idname = $stock['product_name'];
          $p_name = $stock['in_productname'];
          $p_id = $stock['product_id'];
        
          foreach($data_sell as &$sales){
            if($sales['productname'] !== $p_idname) continue;
            if($remainQty <= 0) break;

            $saleQty = (int)$sales['tatol_product'];
            $saleRate = (float)$sales['rate_customertype'];
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
              $totalSellValue += $soldFromLot * $saleRate;
            }
          
          }
          $one_capital = $stock['product_price'] + ($stock['shipping_cost'] / $lotQty);
          $lot_resutl[] = [
            'id' => $p_id,
            'p_name' =>$p_name,
            'id_pname'=> $p_idname,
            'lot_no'=> $lot_code,
            'count_inlot' => $lotQty, //จำนวนฝน lot
            'total_sell' => $soldQtry, // จำนวนขาย
            'remain_qty' => $remainQty, //คงเหลือ
            'product_price' => $stock['product_price'], //ราคาเริ่มต้นต่อลัง
            'product_priceAll' => $stock['product_price'] * $lotQty, // ราคาเริ่มต้นทั้งหมด
            'one_capital' => $one_capital, // ต้นทุนต่อลัง ราคาเริ่มต้น + ค่าส่งต่อลัง
            'difference_one' => $stock['price_center'] - $one_capital, // ส่วนต่างต่อลัง ราคากลาง - ต้นทุนต่อลัง

            'capital_all' => $one_capital * $lotQty, // ต้นทุนทั้งหมด
            'capital_using' =>  $one_capital * $remainQty, // ต้นทุนที่กำลังใช้
            'capitalall_return' =>$one_capital * $soldQtry, // ต้นทุนที่ได้คืน

            'price_center' => $stock['price_center'], // ราคากลาง
            'price_centerAll' => $stock['price_center'] * $lotQty, // ราคากลางทั้งหมด
            //'' => $stock['price_center'] * $remainQty, // ราคากลางที่กำลังใช้
            'price_center_return' => $stock['price_center'] * $soldQtry, // ราคากลางที่ได้คืน
            'difference' => ($stock['price_center'] * $soldQtry) - ($one_capital * $soldQtry), // คืนทุน ราคากลาง - ต้นทุนที่ได้คืน

            'one_sell' => $saleRate, // ราคาขายต่อลัง
            'expenses' => $stock['expenses'], // ราคาขายทั้งหมด
            'profit_all' => ($saleRate * $soldQtry) - ($stock['price_center'] * $soldQtry), // กำไรทั้งหมด

            'shipping_one' => $stock['shipping_cost'] / $lotQty, // ค่าส่งต่อลัง
            'shipping_cost' => $stock['shipping_cost'], // ค่าส่งทั้งหมด
            
            'sell' => 'sell',
            'total_sell_value' => $totalSellValue,
            'create_at' => $stock['create_at']
          ];
        }
        $iscount_inlot = 0;
        $istotal_sell = 0;
        $isremain_qty = 0;
        $is_priceAll = 0;
        $is_shippingcost = 0;
        $is_capitalall = 0;
        $is_capitalusing = 0;
        $is_capital_return = 0; 
        $is_difference_one = 0;
        $is_pricecenter_return = 0;
        $is_profit_all = 0;
        foreach($lot_resutl as $res){
    $html .= '
            <tr>
              <td class="fontbold name" >'.$res['p_name'].'</td>
              <td class="fontbold total">'.$res['count_inlot'].'</td>
              <td class="fontbold total">'.$res['total_sell'].'</td>
              <td class="fontbold total">'.$res['remain_qty'].'</td>
              <td class="fontbold total">'.number_format($res['product_price'],2).'</td>
              <td class="fontbold total">'.number_format($res['shipping_one'],2).'</td>
              <td class="fontbold total">'.number_format($res['one_capital'],2).'</td>
              <td class="fontbold total">'.number_format($res['price_center'],2).'</td>
              <td class="fontbold total">'.number_format($res['difference_one'],2).'</td>
              <td class="fontbold total">'.number_format($res['product_priceAll'],2).'</td>
              <td class="fontbold total">'.number_format($res['shipping_cost'],2).'</td>
              <td class="fontbold total">'.number_format($res['capital_all'],2).'</td>
              <td class="fontbold total">'.number_format($res['capital_using'],2).'</td>
              <td class="fontbold total">'.number_format($res['capitalall_return'],2).'</td>
              <td class="fontbold total">'.number_format($res['price_center_return'],2).'</td>
              <td class="fontbold total">'.number_format($res['profit_all'],2).'  
            </tr>';
            //$issum_count        += $res['count'];
            $iscount_inlot  += $res['count_inlot'];
            $istotal_sell   += $res['total_sell'];
            $isremain_qty       += $res['remain_qty'];
            $is_difference_one += $res['difference_one'];
            $is_priceAll        += $res['product_priceAll'];
            $is_shippingcost    += $res['shipping_cost'];
            $is_capitalall      += $res['capital_all'];
            $is_capitalusing   += $res['capital_using'];
            $is_capital_return  += $res['capitalall_return'];
            $is_pricecenter_return += $res['price_center_return'];
            $is_profit_all += $res['profit_all'];
      }
    $html .='
        </tbody>
        <tfoot>
          <tr style="background-color:#F5DEB3;">
              <td class="fontboldtfoot name" >ทั้งหมด</td>
              <td class="fontboldtfoot total">'.number_format($iscount_inlot).'</td>
              <td class="fontboldtfoot total">'.number_format($istotal_sell).'</td>
              <td class="fontboldtfoot total">'.number_format($isremain_qty).'</td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total">'.number_format($is_difference_one,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_priceAll,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_shippingcost,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_capitalall,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_capitalusing,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_capital_return,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_pricecenter_return,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_profit_all,2).'</td> 
              
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