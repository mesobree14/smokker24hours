<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
//$lot_number = $_GET['lot_number'];
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
    'format' => [170, 190],
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
    width: 50%;
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
  </style>';

$html .='
<div>
  <div class="" style="">
    
    <div style="float: left; width: 100%;">
      <h2 style="text-align: left;">รายการในสต็อกทั้งหมด</h2>
    </div>
        <div style="width:100%">
      <table class="slip-table">
        <thead>
          <tr style="background-color:#ff9933;">
            <th class="price">Lot No</th>
            <th class="price">สินค้า</th>
            <th class="qty">จำนวนซื้อ</th>
            <th class="total">จำนวนขาย</th>
            <th class="total">จำนวนคงเหลือ</th>
            <th class="total">วันที่สั่งซื้อ</th>
          </tr>
        </thead>
        <tbody>';
      $get_product = "SELECT COUNT(*) AS total_lot, NP.product_name AS in_productname, SP.product_id, SP.product_name,SP.create_at,
          SP.product_price,SP.price_center,SP.shipping_cost,SP.expenses, SUM(SP.product_count) AS sum_product, SP.lot_number FROM stock_product SP 
          LEFT JOIN name_product NP ON SP.product_name = NP.id_name GROUP BY SP.product_id, SP.lot_number";
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
          $lot_resutl[] = [
            'id' => $p_id,
            'p_name' =>$p_name,
            'id_pname'=> $p_idname,
            'lot_no'=> $lot_code,
            'count_inlot' => $lotQty, //จำนวนฝน lot
            'total_sell' => $soldQtry, // จำนวนขาย
            'remain_qty' => $remainQty, //คงเหลือ
            'product_price' => $stock['product_price'], //ราคาเริ่มต้นต่อลัง
            'product_priceAll' => $stock['product_price'] * $lotQty,
            'price_center' => $stock['price_center'], // ราคากลาง
            'price_centerAll' => $stock['price_center'] * $lotQty,
            'shipping_one' => $stock['shipping_cost'] / $lotQty,
            'shipping_cost' => $stock['shipping_cost'],
            'expenses' => $stock['expenses'],
            'sell' => 'sell',
            'total_sell_value' => $totalSellValue,
            'create_at' => $stock['create_at']
          ];
        }

        foreach($lot_resutl as $res){
    $html .= '
            <tr>
              <td class="fontbold name" >'.$res['lot_no'].'</td>
              <td class="fontbold name" >'.$res['p_name'].'</td>
              <td class="fontbold total">'.$res['count_inlot'].'</td>
              <td class="fontbold total">'.$res['total_sell'].'</td>
              <td class="fontbold total">'.$res['remain_qty'].'</td>
              <td class="fontbold total">'.$res['create_at'].'</td>
            </tr>';
      }
    $html .='
        </tbody>
      </table>
  </div>
  </div>
</div>';

$mpdf->WriteHTML($html);
$mpdf->Output();

?>