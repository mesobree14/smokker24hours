<?php
session_name("session_smokker");
  session_start();
include_once("../../backend/config.php");
include_once("../../link/link-2.php");
include_once("../../components/component.php");
$lot_number = $_GET['lot_number'];
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
if(!isset($_SESSION['users_data'])){
  echo "
          <script>
              alert('pless your login');
              window.location = '../index.php';
          </script>
      ";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.0.0-beta.20/css/uikit.css">
    <link rel="stylesheet" href="../../assets/scripts/module/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <link rel="stylesheet" href="../../assets/scss/navigationTrue-a-j.scss">
    <link rel="stylesheet" href="../../assets/scss/revenue.scss">
    <link rel="stylesheet" href="../../assets/scss/index.u.scss">
    <script src="../../assets/scripts/script-bash.js"></script>
    <link rel="stylesheet" href="../../assets/scripts/module/select-picker/select.scss">
  <title>Document</title>
</head>
<body>
  <div class="page-wrapper chiller-theme toggled">
      <?php  navigationOfiicer("../"); ?>
       <main class="page-content mt-0">
      <?php navbar("รายละเอียด / ".$lot_number."", "../"); ?>
      <div class="container-fluid row">
          <div class="col-md-12">
              <div id="tabC01" class="tab-contents">
                <?php
// $inputStart = "2025-11-01T00:00"; //2025-10-30 09:20:00
// $inputEnd   = "2025-11-30T00:00";

// $startDateFilter = str_replace("T", " ", $inputStart) . ":00";
// $endDateFilter   = str_replace("T", " ", $inputEnd) . ":00";
// echo " start date : ".$startDateFilter."   |    end date : ".$endDateFilter;
// echo "<br/><hr/><br/>";
//                 $get_products_in_lot_sql = "SELECT 
//                       NP.product_name AS in_productname,
//                       SP.product_id,
//                       SP.product_name,
//                       SP.create_at,
//                       SP.product_price,
//                       SP.price_center,
//                       SP.shipping_cost,
//                       SP.expenses,
//                       SP.product_count,
//                       SP.lot_number
//                     FROM stock_product SP
//                     LEFT JOIN name_product NP ON SP.product_name = NP.id_name
//                     ORDER BY SP.create_at ASC, SP.lot_number ASC, SP.product_id ASC
//                     ";
//                   $stmt = $conn->prepare($get_products_in_lot_sql);
//                   //$stmt->bind_param("s", $lot_number);
//                   $stmt->execute();
//                   $res = $stmt->get_result();
//                   $productsInLot = [];
//                   while ($r = $res->fetch_assoc()) {
//                       $productsInLot[] = $r;
//                   }
//                   $stmt->close();
//                   // echo "<pre>".print_r($productsInLot)."</pre>";
//                   // echo "<br/><br/><hr/>";

//                   $get_total_sold_sql = "SELECT 
//                   COALESCE(SUM(LP.tatol_product),0) AS total_sold,
//                   MIN(OS.date_time_sell) AS firstSellDate,
//                   MAX(OS.date_time_sell) AS lastSellDate
//                   FROM list_productsell LP LEFT JOIN orders_sell OS ON LP.ordersell_id= OS.id_ordersell
//                   WHERE productname = ?
//                   ";
//                   $stmtSold = $conn->prepare($get_total_sold_sql);

//                   $get_prior_lots_sql = "
//                   SELECT COALESCE(SUM(product_count),0) AS prior_qty
//                   FROM stock_product
//                   WHERE product_name = ?
//                     AND (
//                         create_at < ? 
//                         OR (create_at = ? AND product_id < ?)
//                     )
//                   ";
//                   $stmtPrior = $conn->prepare($get_prior_lots_sql);

//                   $get_sale_rates_sql = "
//                   SELECT rate_customertype, tatol_product
//                   FROM list_productsell
//                   WHERE productname = ?
//                   ORDER BY list_sellid ASC
//                   ";
//                   $stmtRates = $conn->prepare($get_sale_rates_sql);

//                   $get_sale_dateql = "SELECT OS.date_time_sell,LP.tatol_product FROM list_productsell LP 
//                   LEFT JOIN orders_sell OS ON LP.ordersell_id= OS.id_ordersell
//                   WHERE LP.productname = ? ORDER BY OS.date_time_sell ASC";
//                   $stmtDates = $conn->prepare($get_sale_dateql);

//                   $lot_resutl = [];

//                   foreach ($productsInLot as $key => $stock) {
//                   $row_id = intval($stock['product_id']); 
//                   $p_idname = $stock['product_name'];        // key to match sales
//                   // echo "isD: ";
//                   // echo $p_idname;
//                   // echo "<br/>";
//                   $p_name = $stock['in_productname'];
//                   $p_id = $stock['product_id'];
//                   $lotQty = intval($stock['product_count']);
//                   $lot_code = $stock['lot_number'];
//                   $create_at = $stock['create_at'];
                
//                   // 1) totalSold ของสินค้านี้ (จาก list_productsell)
//                   $stmtSold->bind_param("s", $p_idname);
//                   $stmtSold->execute();
//                   $rSold = $stmtSold->get_result()->fetch_assoc();
                  
//                   $totalSold = intval($rSold['total_sold']);
                
//                   // 2) priorLotQty = ผลรวมจำนวนในล็อตที่เก่ากว่า (สำหรับสินค้านี้)
//                   //    เงื่อนไขใช้ create_at และถ้าเวลาเท่ากัน ให้ใช้ lot_number เปรียบเทียบเป็น tie-breaker
//                   $stmtPrior->bind_param("sssi", $p_idname, $create_at, $create_at, $row_id);
//                   $stmtPrior->execute();
//                   $rPrior = $stmtPrior->get_result()->fetch_assoc();
//                   $priorLotQty = intval($rPrior['prior_qty']);
//                   $isPriorLotSetQty = intval($rPrior['prior_qty']);
          
//                   // sold allocated to previous lots = min(totalSold, priorLotQty)
//                   $soldAllocatedToPrev = min($totalSold, $priorLotQty);
                
//                   // remaining to allocate to this and later lots
//                   $remainingToAllocate = max(0, $totalSold - $soldAllocatedToPrev);
                
//                   // sold in THIS lot = min(lotQty, remainingToAllocate)
//                   $soldInThisLot = min($lotQty, $remainingToAllocate);
                
//                   // if remainingToAllocate <= 0 => soldInThisLot becomes 0 automatically

//                   $stmtDates->bind_param("s", $p_idname);
//                   $stmtDates->execute();
//                   $resDates = $stmtDates->get_result();

//                   $iisNum = 0;
//                   $isDateTestList = [];
                  
//                   $listSellTest = [];
//                   $countInlotQty = $lotQty;
//                   while($sd = $resDates->fetch_assoc()){
//                     $listSellTest[] = ['date'=> $sd['date_time_sell'],'total'=>$sd['tatol_product']];
//                   }
//                   $currentLotSales = [];
//                   //echo "<pre>".print_r($listSellTest)."</pre>";

//                   foreach($listSellTest as $item){
//                     $qtys = $item['total'];
                    
//                     if($priorLotQty > 0){
//                       if($qtys <= $priorLotQty){
//                         $priorLotQty -= $qtys;
//                          continue;
//                       }
//                       $remians = $qtys - $priorLotQty;
//                       $priorLotQty = 0;
//                       $qtys = $remians;
//                     }
//                     if($countInlotQty <= 0){
//                       break;
//                     }
//                     if($qtys > $countInlotQty){
//                       $qtys = $countInlotQty;
//                     }
//                     $currentLotSales[] = [
//                       'date' => $item['date'],
//                       'qty'  => $qtys
//                     ];

//                     $countInlotQty -= $qtys;
//                     if($countInlotQty <= 0){
//                       break;
//                     }

//                   }


// $filteredSales = [];
// $total_sell_succ = 0;

// foreach ($currentLotSales as $sale) {

//     $saleDate = $sale['date']; // format Y-m-d H:i:s อยู่แล้ว

//     if ($saleDate >= $startDateFilter && $saleDate <= $endDateFilter) {
//         $total_sell_succ += $sale['qty'];
//         $filteredSales[] = $sale;
//     }
// }

//                   $isNums =0;
//                   $dateSellList = [];
//                   $test = [];
//                   $res_num = 0;

//                 while($rd = $resDates->fetch_assoc()){
//                     $test[] = ['date'=> $rd['date_time_sell'], 'qty'=>intval($rd['tatol_product'])];
//                     $qtys = intval($rd['tatol_product']);
//                     $isNums +=$qtys;
//                     if($isPriorLotSetQty == 0){
//                       if($isNums > $soldInThisLot){
//                         $res_num = $isNums - $soldInThisLot;
//                       }else{
//                         $res_num = 0;
//                       }
//                       $dateSellList[] = ['date'=> $rd['date_time_sell'], 'qty'=>$qtys,'isnum'=>$isNums,'x'=>'if','m'=>$res_num,'sx'=>$qtys - $res_num];
//                     }else{
//                       if($isPriorLotSetQty > 0){
//                         if($qtys >= $isPriorLotSetQty){
//                           $isPriorLotSetQty = 0;
//                           $dateSellList[] = ['date'=> $rd['date_time_sell'], 'qty'=>$qtys,'isnum'=>$isNums,'x'=>'out','m'=>$res_num,'sx'=>$qtys - $res_num];
//                         }else{
//                           $isPriorLotSetQty -= $qtys;
//                         }
//                       }
//                     }
//                     if($isNums >= $soldInThisLot){
//                       break;
//                     }
//                   }

//                   $stmtRates->bind_param("s", $p_idname);
//                   $stmtRates->execute();
//                   $resRates = $stmtRates->get_result();
//                   $sumRateQty = 0.0;
//                   $sumRateWeight = 0; // weighted by tatol_product
//                   while ($rr = $resRates->fetch_assoc()) {
//                       $rate = floatval($rr['rate_customertype']);
//                       $qty = intval($rr['tatol_product']);
//                       if ($qty > 0) {
//                           $sumRateQty += $rate * $qty;
//                           $sumRateWeight += $qty;
//                       }
//                   }
//                   $saleRateAvg = ($sumRateWeight > 0) ? ($sumRateQty / $sumRateWeight) : 0;
//                   $totalSellValue = $saleRateAvg * $soldInThisLot;

//                   $product_price = floatval($stock['product_price']);
//                   $shipping_cost_total = floatval($stock['shipping_cost']);
//                   $shipping_one = ($lotQty > 0) ? ($shipping_cost_total / $lotQty) : 0;
//                   $one_capital = $product_price + $shipping_one;
//                   $difference_one = floatval($stock['price_center']) - $one_capital;
                
//                   $capital_all = $one_capital * $lotQty;
//                   //$capital_using = $one_capital * ($lotQty - $soldInThisLot); // คงเหลือหลังหักการขายในล็อตนี้
//                   $capital_using = $one_capital * ($lotQty - $total_sell_succ); // คงเหลือหลังหักการขายในล็อตนี้
//                   //$capitalall_return = $one_capital * $soldInThisLot;
//                   $capitalall_return_succ = $one_capital * $total_sell_succ;
                  

//                   $price_center = floatval($stock['price_center']);
//                   $price_cnter_using = $price_center *($lotQty - $total_sell_succ);
//                   $price_center_return_succ = $price_center * $total_sell_succ;

//                   //$difference_succ = ($price_center * $total_sell_succ) - ($one_capital * $counts_sellsuccess);
//                   $pricecenter_delcaptialshiipin = $price_center_return_succ - $capitalall_return_succ;
//                   $lot_resutl[] = [
//                         'id' => $p_id,
//                         'p_name' => $p_name,
//                         'id_pname'=> $p_idname,
//                         'lot_no'=> $lot_code,
//                         'count_inlot' => $lotQty, //จำนวนที่ซื้อall
//                         'total_sell' => $soldInThisLot, //จำนวนที่ขาย
//                         'total_sell_succ' => $total_sell_succ,
//                         'remain_qty' => $lotQty - $soldInThisLot, //จำนวนที่เหลือ
//                         'remain_qty_succ' => $lotQty - $total_sell_succ,
//                         'product_price' => $product_price, //สินค้าต่อลัง
//                         'product_priceAll' => $product_price * $lotQty, // ต้นทุนทั้งหมด สินค้าต่อลัง * จำนวนที่ซื้อall
//                         'one_capital' => $one_capital, // สินค้า + ค่าส่ง ต่อลัง
//                         'difference_one' => $difference_one, //ราคากลาง - (รา่คา+ค่าส่ง) ต่อลัง
//                         'capital_all' => $capital_all, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ซื้อ
//                         'capital_using' => $capital_using, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่เหลือ
//                         'capitalall_return' => $capitalall_return_succ, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ขาย
//                         'price_center' => $price_center, //  ราคากลางต่อชิ้น
//                         'price_centerAll' => $price_center * $lotQty, // ราคากลางต่อชิ้น * จำนวนที่ซื้อall
//                         'price_center_return' => $price_center_return_succ, // ราคากลางต่อชิ้น * จำนวนที่ขาย
//                         'price_cnter_using' =>$price_cnter_using,
//                         'pricecenter_delcaptialshiipin' => $pricecenter_delcaptialshiipin,
//                         //'difference' => $difference_succ, // กำไรทั้งหมด (ราคากลางต่อชิ้น * จำนวนที่ขาย) * ( (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ขาย )
//                         'prior_lots_qty' => $priorLotQty, // จำนวนที่ซื้อก่อนหน้านี้
//                         'totalSold' => $totalSold, //รวมจำนวนที่ขายไปแล้ว
//                         'currentLotSales' => $currentLotSales,
//                         'filteredSales' => $filteredSales,
//                         'listSellTest'=>$listSellTest
//                     ];
//               }

//  $grouped = [];
//         $lot_itemcount = [];
//         foreach($lot_resutl as $lot){
//           $code = $lot['lot_no'];
//           if(!isset($grouped[$code])){

//             $grouped[$code] = [
//               'lot_code' => $code,
//               'count' => 0,
//               'total_inlot' => 0,
//               'total_sell' => 0,
//               'remain' => 0,
//               'priceAll' =>0, // ต้นทุนรวมทั้งหมด
//               'capital_using' =>0, //ทุนกำลังใช้
//               'capitalall_return' =>0, //ทุนที่ได้คืนมา
//               'pricecenter_All' => 0,
//               'price_cnter_using' =>0,
//               'price_center_return' =>0,
//               'pricecenter_delcaptialshiipin' =>0, //ส่วนต่างราคากลางกับต้นทุนที่ได้คืนมา
//               'shipping_cost' =>0,
//             ];
//             $lot_itemcount[$code] = 0;
//           }
//           $lot_itemcount[$code]++;

//           $grouped[$code]['count'] = $lot_itemcount[$code];
//           $grouped[$code]['total_inlot'] += $lot['count_inlot'];
//           $grouped[$code]['total_sell'] += $lot['total_sell_succ'];
//           $grouped[$code]['remain'] += $lot['remain_qty_succ'];
//           $grouped[$code]['priceAll'] += $lot['product_priceAll'];
//           $grouped[$code]['capital_using'] += $lot['capital_using'];
//           $grouped[$code]['capitalall_return'] += $lot['capitalall_return'];
//           $grouped[$code]['pricecenter_All'] += $lot['price_centerAll'];
//           $grouped[$code]['price_cnter_using'] += $lot['price_cnter_using'];
//           $grouped[$code]['price_center_return'] += $lot['price_center_return'];
//           $grouped[$code]['pricecenter_delcaptialshiipin'] += $lot['pricecenter_delcaptialshiipin'];
//         }

// // $sumByLot = []; 
// // foreach ($lot_resutl as $item) {
// //     $lot = $item['lot_no'];

// //     if (!isset($sumByLot[$lot])) {
// //         $sumByLot[$lot] = [
// //           'lot_no' => $lot,
// //           'sum_count_inlot' => 0,
// //           'sum_total_sell' => 0,
// //           'sum_remain_qty' =>0,
// //           'sum_total_sell_succ' =>0,
// //         ];
// //     }

// //     $sumByLot[$lot]['sum_count_inlot'] += $item['count_inlot'];
// //     $sumByLot[$lot]['sum_total_sell'] += $item['total_sell'];
// //     $sumByLot[$lot]['sum_remain_qty'] += $item['remain_qty'];
// //     $sumByLot[$lot]['sum_total_sell_succ'] += $item['total_sell_succ'];
// // }

// // แสดงผลการรวม
// echo "<pre>".print_r($grouped)."</pre>";

$inputStart = "2025-11-01T00:00"; //2025-10-30 09:20:00
$inputEnd   = "2025-11-30T00:00";

$startDateFilter = str_replace("T", " ", $inputStart) . ":00";
$endDateFilter   = str_replace("T", " ", $inputEnd) . ":00";

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
                    ORDER BY SP.create_at ASC, SP.lot_number ASC, SP.product_id ASC
                    ";
                  $stmt = $conn->prepare($get_products_in_lot_sql);
                  //$stmt->bind_param("s", $lot_number);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  $productsInLot = [];
                  while ($r = $res->fetch_assoc()) {
                      $productsInLot[] = $r;
                  }
                  $stmt->close();

                  $get_total_sold_sql = "SELECT 
                  COALESCE(SUM(LP.tatol_product),0) AS total_sold,
                  MIN(OS.date_time_sell) AS firstSellDate,
                  MAX(OS.date_time_sell) AS lastSellDate
                  FROM list_productsell LP LEFT JOIN orders_sell OS ON LP.ordersell_id= OS.id_ordersell
                  WHERE productname = ?
                  ";
                  $stmtSold = $conn->prepare($get_total_sold_sql);

                  $get_prior_lots_sql = "
                  SELECT COALESCE(SUM(product_count),0) AS prior_qty
                  FROM stock_product
                  WHERE product_name = ?
                    AND (
                        create_at < ? 
                        OR (create_at = ? AND product_id < ?)
                    )
                  ";
                  $stmtPrior = $conn->prepare($get_prior_lots_sql);

                  $get_sale_rates_sql = "
                  SELECT rate_customertype, tatol_product
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

                  foreach ($productsInLot as $key => $stock) {
                  $row_id = intval($stock['product_id']); 
                  $p_idname = $stock['product_name'];        // key to match sales
                  $p_name = $stock['in_productname'];
                  $p_id = $stock['product_id'];
                  $lotQty = intval($stock['product_count']);
                  $lot_code = $stock['lot_number'];
                  $create_at = $stock['create_at'];
                
                  // 1) totalSold ของสินค้านี้ (จาก list_productsell)
                  $stmtSold->bind_param("s", $p_idname);
                  $stmtSold->execute();
                  $rSold = $stmtSold->get_result()->fetch_assoc();
                  
                  $totalSold = intval($rSold['total_sold']);
                
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
                
                  // if remainingToAllocate <= 0 => soldInThisLot becomes 0 automatically

                  $stmtDates->bind_param("s", $p_idname);
                  $stmtDates->execute();
                  $resDates = $stmtDates->get_result();

                  $iisNum = 0;
                  $isDateTestList = [];
                  
                  $listSellTest = [];
                  $countInlotQty = $lotQty;
                  while($sd = $resDates->fetch_assoc()){
                    $listSellTest[] = ['date'=> $sd['date_time_sell'],'total'=>$sd['tatol_product']];
                  }
                  $currentLotSales = [];


                  foreach($listSellTest as $item){
                    $qtys = $item['total'];
                    
                    if($priorLotQty > 0){
                      if($qtys <= $priorLotQty){
                        $priorLotQty -= $qtys;
                         continue;
                      }
                      $remians = $qtys - $priorLotQty;
                      $priorLotQty = 0;
                      $qtys = $remians;
                    }
                    if($countInlotQty <= 0){
                      break;
                    }
                    if($qtys > $countInlotQty){
                      $qtys = $countInlotQty;
                    }
                    $currentLotSales[] = [
                      'date' => $item['date'],
                      'qty'  => $qtys
                    ];

                    $countInlotQty -= $qtys;
                    if($countInlotQty <= 0){
                      break;
                    }

                  }


$filteredSales = [];
$total_sell_succ = 0;

foreach ($currentLotSales as $sale) {

    $saleDate = $sale['date']; // format Y-m-d H:i:s อยู่แล้ว

    if ($saleDate >= $startDateFilter && $saleDate <= $endDateFilter) {
        $total_sell_succ += $sale['qty'];
        $filteredSales[] = $sale;
    }
}

                  $isNums =0;
                  $dateSellList = [];
                  $test = [];
                  $res_num = 0;

                while($rd = $resDates->fetch_assoc()){
                    $test[] = ['date'=> $rd['date_time_sell'], 'qty'=>intval($rd['tatol_product'])];
                    $qtys = intval($rd['tatol_product']);
                    $isNums +=$qtys;
                    if($isPriorLotSetQty == 0){
                      if($isNums > $soldInThisLot){
                        $res_num = $isNums - $soldInThisLot;
                      }else{
                        $res_num = 0;
                      }
                      $dateSellList[] = ['date'=> $rd['date_time_sell'], 'qty'=>$qtys,'isnum'=>$isNums,'x'=>'if','m'=>$res_num,'sx'=>$qtys - $res_num];
                    }else{
                      if($isPriorLotSetQty > 0){
                        if($qtys >= $isPriorLotSetQty){
                          $isPriorLotSetQty = 0;
                          $dateSellList[] = ['date'=> $rd['date_time_sell'], 'qty'=>$qtys,'isnum'=>$isNums,'x'=>'out','m'=>$res_num,'sx'=>$qtys - $res_num];
                        }else{
                          $isPriorLotSetQty -= $qtys;
                        }
                      }
                    }
                    if($isNums >= $soldInThisLot){
                      break;
                    }
                  }

                  $stmtRates->bind_param("s", $p_idname);
                  $stmtRates->execute();
                  $resRates = $stmtRates->get_result();
                  $sumRateQty = 0.0;
                  $sumRateWeight = 0; // weighted by tatol_product
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

                  $product_price = floatval($stock['product_price']);
                  $shipping_cost_total = floatval($stock['shipping_cost']);
                  $shipping_one = ($lotQty > 0) ? ($shipping_cost_total / $lotQty) : 0;
                  $one_capital = $product_price + $shipping_one;
                  $difference_one = floatval($stock['price_center']) - $one_capital;
                
                  $capital_all = $one_capital * $lotQty;
                  //$capital_using = $one_capital * ($lotQty - $soldInThisLot); // คงเหลือหลังหักการขายในล็อตนี้
                  $capital_using = $one_capital * ($lotQty - $total_sell_succ); // คงเหลือหลังหักการขายในล็อตนี้
                  //$capitalall_return = $one_capital * $soldInThisLot;
                  $capitalall_return_succ = $one_capital * $total_sell_succ;
                  

                  $price_center = floatval($stock['price_center']);
                  $price_cnter_using = $price_center *($lotQty - $total_sell_succ);
                  $price_center_return_succ = $price_center * $total_sell_succ;
                  $pricecenter_delcaptialshiipin = $price_center_return_succ - $capitalall_return_succ;

                  $lot_resutl[] = [
                        'id' => $p_id,
                        'p_name' => $p_name,
                        'id_pname'=> $p_idname,
                        'lot_no'=> $lot_code,
                        'count_inlot' => $lotQty, //จำนวนที่ซื้อall
                        'total_sell' => $soldInThisLot, //จำนวนที่ขาย
                        'total_sell_succ' => $total_sell_succ,
                        'remain_qty' => $lotQty - $soldInThisLot, //จำนวนที่เหลือ
                        'remain_qty_succ' => $lotQty - $total_sell_succ,
                        'product_price' => $product_price, //สินค้าต่อลัง
                        'product_priceAll' => $product_price * $lotQty, // ต้นทุนทั้งหมด สินค้าต่อลัง * จำนวนที่ซื้อall
                        'one_capital' => $one_capital, // สินค้า + ค่าส่ง ต่อลัง
                        'difference_one' => $difference_one, //ราคากลาง - (รา่คา+ค่าส่ง) ต่อลัง
                        'capital_all' => $capital_all, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ซื้อ
                        'capital_using' => $capital_using, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่เหลือ
                        'capitalall_return' => $capitalall_return_succ, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ขาย
                        'price_center' => $price_center, //  ราคากลางต่อชิ้น
                        'price_centerAll' => $price_center * $lotQty, // ราคากลางต่อชิ้น * จำนวนที่ซื้อall
                        'price_center_return' => $price_center_return_succ, // ราคากลางต่อชิ้น * จำนวนที่ขาย
                        'price_cnter_using' =>$price_cnter_using,
                        'pricecenter_delcaptialshiipin' => $pricecenter_delcaptialshiipin,
                        'prior_lots_qty' => $priorLotQty, // จำนวนที่ซื้อก่อนหน้านี้
                        'totalSold' => $totalSold, //รวมจำนวนที่ขายไปแล้ว
                        'currentLotSales' => $currentLotSales,
                        'filteredSales' => $filteredSales,
                        'listSellTest'=>$listSellTest
                    ];
              }

              echo "<pre>";
              echo json_encode($lot_resutl, JSON_PRETTY_PRINT);
              echo "</pre>";
              //echo json_encode($lot_resutl, JSON_PRETTY_PRINT);


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
            ];
            $lot_itemcount[$code] = 0;
          }
          $lot_itemcount[$code]++;

          $grouped[$code]['count'] = $lot_itemcount[$code];
          $grouped[$code]['total_inlot'] += $lot['count_inlot'];
          $grouped[$code]['total_sell'] += $lot['total_sell_succ'];
          $grouped[$code]['remain'] += $lot['remain_qty_succ'];
          $grouped[$code]['priceAll'] += $lot['product_priceAll'];
          $grouped[$code]['capital_using'] += $lot['capital_using'];
          $grouped[$code]['capitalall_return'] += $lot['capitalall_return'];
          $grouped[$code]['pricecenter_All'] += $lot['price_centerAll'];
          $grouped[$code]['price_cnter_using'] += $lot['price_cnter_using'];
          $grouped[$code]['price_center_return'] += $lot['price_center_return'];
          $grouped[$code]['pricecenter_delcaptialshiipin'] += $lot['pricecenter_delcaptialshiipin'];
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

       

                  
                ?>
              </div>

             
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="../../assets/scripts/result_finance.js"></script>
</body>
</html>