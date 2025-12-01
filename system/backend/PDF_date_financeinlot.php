<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
$lot_number = $_POST['lot_number'];
$start_date = date("Y-m-d H:i:s", strtotime($_POST['start_date']));//$_POST['start_date'] ?? date('Y-m-01');
$end_date =  date("Y-m-d H:i:s", strtotime($_POST['end_date']));//$_POST['end_date'] ?? date('Y-m-d');



require_once __DIR__ . '/../../vendor/autoload.php';
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
  table.slip-table td.name-black{
    width: 30%;
    text-align: left;
    color:black;
    font-weight: bold;
  }
  
  table.slip-table th.name-black
  table.slip-table th.total,
  table.slip-table th.qty,
  table.slip-table th.price{
  width: 10%;
    color:black;
  }

  
  table.slip-table td.price,
  table.slip-table td.qty,
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
      <div style="display:flex;width:100%;">
        ข้อมูลระหว่างวันที่ '.$start_date.' ถึง '.$end_date.'
      </div>
    </div>
        <div style="width:100%">
      <table class="slip-table">
        <thead>
          <tr style="background-color:#ff9933;">
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
            <th class="total-blue">รายรับ</th>
            <th class="total">คืนทุน</th>
            <th class="total-blue">กำไร</th>
          </tr>
        </thead>
        <tbody>';
    $get_products_in_lot_sql = "SELECT 
        NP.product_name AS in_productname,
        SP.product_id,
        SP.product_name,
        SP.create_at,
        SP.product_price,
        SP.price_center,
        SP.shipping_cost,
        SP.expenses,
        SP.product_count,
        SP.lot_number
      FROM stock_product SP
      LEFT JOIN name_product NP ON SP.product_name = NP.id_name
      WHERE SP.lot_number = ?
      ORDER BY SP.create_at ASC, SP.lot_number ASC, SP.product_id ASC
      ";
// GROUP BY SP.product_id, SP.product_name, SP.lot_number, SP.create_at, SP.product_price, SP.price_center, SP.shipping_cost, SP.expenses
    $stmt = $conn->prepare($get_products_in_lot_sql);
    $stmt->bind_param("s", $lot_number);
    $stmt->execute();
    $res = $stmt->get_result();
    $productsInLot = [];
    while ($r = $res->fetch_assoc()) {
        $productsInLot[] = $r;
    }
    $stmt->close();

// Prepare statement to get total sold for a product
    // $get_total_sold_sql = "SELECT 
    // COALESCE(SUM(tatol_product),0) AS total_sold
    // FROM list_productsell
    // WHERE productname = ?
    //   AND create_at BETWEEN ? AND ?
    // ";
    $get_total_sold_sql = "SELECT 
    COALESCE(SUM(tatol_product),0) AS total_sold,
    MIN(create_at) AS firstSellDate,
    MAX(create_at) AS lastSellDate
    FROM list_productsell
    WHERE productname = ?
    ";
    $stmtSold = $conn->prepare($get_total_sold_sql);

// Prepare statement to get prior lots total qty for a product (older than current lot)
    $get_prior_lots_sql = "SELECT 
    COALESCE(SUM(product_count),0) AS prior_qty
    FROM stock_product
    WHERE product_name = ?
      AND (
          create_at < ? 
          OR (create_at = ? AND product_id < ?)
      )
    ";
    $stmtPrior = $conn->prepare($get_prior_lots_sql);

// Optional: prepare statement to get sale rates (we'll compute average saleRate for product)
    $get_sale_rates_sql = "SELECT 
    rate_customertype, tatol_product
    FROM list_productsell
    WHERE productname = ?
    ORDER BY list_sellid ASC
    ";
    $stmtRates = $conn->prepare($get_sale_rates_sql);


    $get_sale_dateql = "SELECT OS.date_time_sell,LP.tatol_product FROM list_productsell LP 
          LEFT JOIN orders_sell OS ON LP.ordersell_id= OS.id_ordersell
          WHERE LP.productname = ? ORDER BY OS.date_time_sell ASC";
    $stmtDates = $conn->prepare($get_sale_dateql);

  $lot_resutl = [];

  foreach ($productsInLot as $stock) {
    $row_id = intval($stock['product_id']); 
    $p_idname = $stock['product_name'];        // key to match sales
    $p_name = $stock['in_productname'];
    $p_id = $stock['product_id'];
    $lotQty = intval($stock['product_count']);
    $lot_code = $stock['lot_number'];
    $create_at = $stock['create_at'];

    // 1) totalSold ของสินค้านี้ (จาก list_productsell)
    // $stmtSold->bind_param("sss", $p_idname, $start_date, $end_date);
    $stmtSold->bind_param("s", $p_idname);
    $stmtSold->execute();
    $rSold = $stmtSold->get_result()->fetch_assoc();
    $totalSold = intval($rSold['total_sold']);
    $lastSellDate = $rSold['lastSellDate'] ?? null;

    

    // 2) priorLotQty = ผลรวมจำนวนในล็อตที่เก่ากว่า (สำหรับสินค้านี้)
    //    เงื่อนไขใช้ create_at และถ้าเวลาเท่ากัน ให้ใช้ lot_number เปรียบเทียบเป็น tie-breaker
    $stmtPrior->bind_param("sssi", $p_idname, $create_at, $create_at, $row_id);
    $stmtPrior->execute();
    $rPrior = $stmtPrior->get_result()->fetch_assoc();
    $priorLotQty = intval($rPrior['prior_qty']);
    $isPriorLotSetQty = intval($rPrior['prior_qty']);
    

    // sold allocated to previous lots = min(totalSold, priorLotQty)
    $soldAllocatedToPrev = min($totalSold, $priorLotQty);

    // remaining to allocate to this and later lots
    $remainingToAllocate = max(0, $totalSold - $soldAllocatedToPrev);

    // sold in THIS lot = min(lotQty, remainingToAllocate)
    $soldInThisLot = min($lotQty, $remainingToAllocate);

    $stmtDates->bind_param("s", $p_idname);
      $stmtDates->execute();
      $resDates = $stmtDates->get_result();
      $isNums =0;
      $dateSellList = [];
      $res_num = 0;
      while($rd = $resDates->fetch_assoc()){
        //$dateSellList[] = ['date'=> $rd['date_time_sell'], 'qty'=>intval($rd['tatol_product'])];
        $qtys = intval($rd['tatol_product']);
        $isNums +=$qtys;
        if($isPriorLotSetQty == 0){
          if($isNums > $soldInThisLot){
            $res_num = $isNums - $soldInThisLot;
          }else{
            $res_num = 0;
          }
          $dateSellList[] = ['date'=> $rd['date_time_sell'], 'in_qty'=>$qtys,'isnum'=>$isNums,'m'=>$res_num,'sx'=>$qtys - $res_num];
        }else{
          if($isPriorLotSetQty > 0){
            if($qtys >= $isPriorLotSetQty){
              $isPriorLotSetQty = 0;
              $dateSellList[] = ['date'=> $rd['date_time_sell'], 'in_qty'=>$qtys,'isnum'=>$isNums,'m'=>$res_num,'sx'=>$qtys - $res_num];
            }else{
              $isPriorLotSetQty -= $qtys;
            }
          }
        }
        if($isNums >= $soldInThisLot){
          break;
        }
      }
      $firstSellDate = count($dateSellList) > 0 ? $dateSellList[0] : null;
      $lastSellDate = count($dateSellList) > 0 ? end($dateSellList) : null;

      $filteredDateSellList = [];
      $total_resfilter = 0;

      foreach($dateSellList as $isItem){
        $sellDate = $isItem['date'];
        if($sellDate >= $start_date && $sellDate <= $end_date){
          $filteredDateSellList[] = $isItem;
          $total_resfilter += $isItem['sx'];
        }
      }



    // if remainingToAllocate <= 0 => soldInThisLot becomes 0 automatically

    // 3) คำนวณราคาขายต่อลัง (ตัวอย่างใช้ average rate จาก list_productsell)
    $stmtRates->bind_param("s", $p_idname);
    $stmtRates->execute();
    $resRates = $stmtRates->get_result();
    
    $sumRateQty = 0.0;
    $sumRateWeight = 0; // weighted by tatol_product

    if($totalSold <= $totalSold){
      $startSellDate = null;
    }

    
    while ($rr = $resRates->fetch_assoc()) {
        
        $rate = floatval($rr['rate_customertype']);
        $qty = intval($rr['tatol_product']);
        if ($qty > 0) {
            $sumRateQty += $rate * $qty;
            $sumRateWeight += $qty;
        }
    }
    $saleRateAvg = ($sumRateWeight > 0) ? ($sumRateQty / $sumRateWeight) : 0;
    $totalSellValue = $saleRateAvg * $soldInThisLot;
    $totalSellValue_filter = $saleRateAvg * $total_resfilter;

    // 4) คำนวณต้นทุน / ค่าส่ง ต่อลัง
    $product_price = floatval($stock['product_price']);
    $shipping_cost_total = floatval($stock['shipping_cost']);
    $shipping_one = ($lotQty > 0) ? ($shipping_cost_total / $lotQty) : 0;
    $one_capital = $product_price + $shipping_one;
    $difference_one = floatval($stock['price_center']) - $one_capital;

    $capital_all = $one_capital * $lotQty;
    $capital_using = $one_capital * ($lotQty - $soldInThisLot); // คงเหลือหลังหักการขายในล็อตนี้
    $capital_using_filter = $one_capital * ($lotQty - $total_resfilter); // คงเหลือหลังหักการขายในล็อตนี้ date
    $capitalall_return = $one_capital * $soldInThisLot;
    $capitalall_return_filter = $one_capital * $total_resfilter;

    $price_center = floatval($stock['price_center']);
    $price_center_return = $price_center * $soldInThisLot;
    $price_center_return_filter = $price_center * $total_resfilter;
    $difference = ($price_center * $soldInThisLot) - ($one_capital * $soldInThisLot);
    $difference_filter = ($price_center * $total_resfilter) - ($one_capital * $total_resfilter);

    $lot_resutl[] = [
        'id' => $p_id,
        'p_name' => $p_name,
        'id_pname'=> $p_idname,
        'lot_no'=> $lot_code,
        'count_inlot' => $lotQty,
        'total_sell' => $soldInThisLot,
        'total_resfilter'=>$total_resfilter,
        'remain_qty' => $lotQty - $soldInThisLot,
        'remain_qty_filter' => $lotQty - $total_resfilter,
        'product_price' => $product_price,
        'product_priceAll' => $product_price * $lotQty,
        'one_capital' => $one_capital,
        'difference_one' => $difference_one,
        'capital_all' => $capital_all,
        'capital_using' => $capital_using,
        'capital_using_filter' => $capital_using_filter,
        'capitalall_return' => $capitalall_return,
        'capitalall_return_filter' => $capitalall_return_filter,
        'price_center' => $price_center,
        'price_centerAll' => $price_center * $lotQty,
        'price_center_return' => $price_center_return,
        'price_center_return_filter' => $price_center_return_filter,
        'difference' => $difference,
        'difference_filter' => $difference_filter,
        'one_sell' => $saleRateAvg,
        'expenses' => $stock['expenses'],
        'profit_all' => ($saleRateAvg * $soldInThisLot) - ($price_center * $soldInThisLot),
        'profit_all_filter' => ($saleRateAvg * $total_resfilter) - ($price_center * $total_resfilter),
        'shipping_one' => $shipping_one,
        'shipping_cost' => $shipping_cost_total,
        'total_sell_value' => $totalSellValue,
        'total_sell_value_filter' => $totalSellValue_filter,
        'create_at' => $create_at,
        'prior_lots_qty' => $priorLotQty,
        'totalSold' => $totalSold,
        'first_date'=>$firstSellDate,
        'last_date'=>$lastSellDate,
        'allDateSell'=>$dateSellList,
    ];
  }
  // free statements
  $stmtSold->close();
  $stmtPrior->close();
  $stmtRates->close();
  
        $iscount_inlot = 0;
        $istotal_sell = 0;
        $istotal_resfilter = 0;
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
              <td class="fontbold name-black" >'.$res['p_name'].'</td>
              <td class="fontbold total">'.$res['count_inlot'].'</td>
              
              <td class="fontbold total">'.$res['total_resfilter'].'</td>
              
              <td class="fontbold total">'.$res['remain_qty_filter'].'</td>
              <td class="fontbold total">'.number_format($res['product_price'],2).'</td>
              <td class="fontbold total">'.number_format($res['shipping_one'],2).'</td>
              <td class="fontbold total">'.number_format($res['one_capital'],2).'</td>
              <td class="fontbold total">'.number_format($res['price_center'],2).'</td>
              <td class="fontbold total">'.number_format($res['difference_one'],2).'</td>
              <td class="fontbold total">'.number_format($res['product_priceAll']).'</td>
              <td class="fontbold total">'.number_format($res['shipping_cost']).'</td>
              <td class="fontbold total">'.number_format($res['capital_all']).'</td>
              <td class="fontbold total">'.number_format($res['capital_using_filter']).'</td>
              <td class="fontbold total-blue">'.number_format($res['price_center_return_filter']).'</td>
              <td class="fontbold total">'.number_format($res['capitalall_return_filter']).'</td>
              <td class="fontbold total-blue">'.number_format($res['difference_filter']).'  </td>
            </tr>';
            //$issum_count        += $res['count'];
            $iscount_inlot  += $res['count_inlot'];
            $istotal_sell   += $res['total_sell'];
            $istotal_resfilter += $res['total_resfilter'];
            $isremain_qty       += $res['remain_qty'];
            $isremain_qty_filter       += $res['remain_qty_filter'];
            $is_difference_one += $res['difference_one'];
            $is_priceAll        += $res['product_priceAll'];
            $is_shippingcost    += $res['shipping_cost'];
            $is_capitalall      += $res['capital_all'];
            $is_capitalusing   += $res['capital_using_filter'];
            $is_capital_return  += $res['capitalall_return_filter'];
            $is_pricecenter_return += $res['price_center_return_filter'];
            $is_profit_all += $res['difference_filter']; //difference
      }
    $html .='
        </tbody>
        <tfoot>
          <tr style="background-color:#F5DEB3;">
              <td class="fontboldtfoot name-black" >ทั้งหมด</td>
              <td class="fontboldtfoot total">'.number_format($iscount_inlot).'</td>
              
              <td class="fontboldtfoot total">'.number_format($istotal_resfilter).'</td>
              <td class="fontboldtfoot total">'.number_format($isremain_qty_filter).'</td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total"></td>
              <td class="fontboldtfoot total">'.number_format($is_difference_one,2).'</td>
              <td class="fontboldtfoot total">'.number_format($is_priceAll).'</td>
              <td class="fontboldtfoot total">'.number_format($is_shippingcost).'</td>
              <td class="fontboldtfoot total">'.number_format($is_capitalall).'</td>
              <td class="fontboldtfoot total">'.number_format($is_capitalusing).'</td>
              <td class="fontboldtfoot total-blue">'.number_format($is_pricecenter_return).'</td>
              <td class="fontboldtfoot total">'.number_format($is_capital_return).'</td>
              
              <td class="fontboldtfoot total-blue">'.number_format($is_profit_all).'</td> 
              
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