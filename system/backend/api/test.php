<?php

  include_once("../../../backend/config.php");
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  if(!$conn){
    die("not conn");
  }
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");	
   header("Cache-Control: post-check=0, pre-check=0", false);	
    header("Pragma: no-cache");
    date_default_timezone_set("Asia/Bangkok");
    if($_SERVER['REQUEST_METHOD'] === "GET"){
      // $get_product = "SELECT COUNT(*) AS total_lot, NP.product_name AS in_productname, SP.product_id, SP.product_name, 
      // SP.product_price,SP.price_center,SP.shipping_cost,SP.expenses, SUM(SP.product_count) AS sum_product, SP.lot_number FROM stock_product SP 
      // LEFT JOIN name_product NP ON SP.product_name = NP.id_name GROUP BY SP.product_id, SP.lot_number";
      // $query = $conn->query($get_product);
      // $data = [];
      // while($row = $query->fetch_assoc()){
      //   $data[] = $row;
      // }
      //  echo "<pre>";
      //  print_r($data);
      //  echo "</pre>";
      //  echo "<br/><hr/>";
    

      // $get_productsell = "SELECT LP.productname, NP.product_name, SUM(LP.tatol_product) AS tatol_product, SUM(LP.price_to_pay) AS price_to_pay 
      // FROM list_productsell LP LEFT JOIN name_product NP ON LP.productname = NP.id_name GROUP BY LP.productname";
      // $get_productsell = "SELECT LP.list_sellid, LP.productname, NP.product_name,
      // LP.level_selltype,LP.rate_customertype,LP.tatol_product,LP.price_to_pay
      // FROM list_productsell LP LEFT JOIN name_product NP ON LP.productname = NP.id_name ORDER BY LP.list_sellid ASC";
      // $query_sell = $conn->query($get_productsell);
      // $data_sell = [];
      // while($is_row = $query_sell->fetch_assoc()){
      //   $data_sell[] = $is_row;
      // }
      

      // $lot_resutl = [];

      // foreach($data as $stock){
      //   $lotQty = $stock['sum_product'];
      //   $remainQty = $lotQty;
      //   $soldQtry = 0;
      //   $totalSellValue = 0;
      //   $lot_code = $stock['lot_number'];
      //   $p_idname = $stock['product_name'];
      //   $p_name = $stock['in_productname'];
      //   $p_id = $stock['product_id'];
        
      //   foreach($data_sell as &$sales){
      //     if($sales['productname'] !== $p_idname) continue;
      //     if($remainQty <= 0) break;

      //     $saleQty = (int)$sales['tatol_product'];
      //     $saleRate = (float)$sales['rate_customertype'];
      //     if($saleQty > 0){
      //       if($remainQty >= $saleQty){
      //         $soldFromLot = $saleQty;
      //         $remainQty -= $saleQty;
      //         $sales['tatol_product'] = 0;
      //       }else{
      //         $soldFromLot = $remainQty;
      //         $sales['tatol_product'] -= $remainQty;
      //         $remainQty = 0;
      //       }
      //       $soldQtry += $soldFromLot;
      //       $totalSellValue += $soldFromLot * $saleRate;
      //     }
         
      //   }
      //   $lot_resutl[] = [
      //     'id' => $p_id,
      //     'p_name' =>$p_name,
      //     'id_pname'=> $p_idname,
      //     'lot_no'=> $lot_code,
      //     'count_inlot' => $lotQty, //จำนวนฝน lot
      //     'total_sell' => $soldQtry, // จำนวนขาย
      //     'remain_qty' => $remainQty, //คงเหลือ
      //     'product_price' => $stock['product_price'], //ราคาเริ่มต้นต่อลัง
      //     'product_priceAll' => $stock['product_price'] * $lotQty,
      //     'price_center' => $stock['price_center'], // ราคากลาง
      //     'price_centerAll' => $stock['price_center'] * $lotQty,
      //     'shipping_one' => $stock['shipping_cost'] / $lotQty,
      //     'shipping_cost' => $stock['shipping_cost'],
      //     'expenses' => $stock['expenses'],
      //     'sell' => 'sell',
      //     'total_sell_value' => $totalSellValue
      //   ];
      // }
          

      // echo "<br/><hr style='border:3px solid blue;'/>";
      // echo "<pre>";
      // print_r($lot_resutl);
      // echo "</pre>";

      // echo "<br/><hr style='border:3px solid blue;'/>";
      // $grouped = [];
      // $lot_itemcount = [];
      // foreach($lot_resutl as $lot){
      //   $code = $lot['lot_no'];
      //   if(!isset($grouped[$code])){
          
      //     $grouped[$code] = [
      //       'lot_code' => $code,
      //       'count' => 0,
      //       'total_inlot' => 0,
      //       'total_sell' => 0,
      //       'remain' => 0,
      //       'priceAll' =>0,
      //       'pricecenter_All' => 0,
      //       'shipping_cost' =>0,
      //       'expenses' => 0,
      //       'price_seller' => 0,
      //     ];
      //     $lot_itemcount[$code] = 0;
      //   }
      //   $lot_itemcount[$code]++;
        
      //   $grouped[$code]['count'] = $lot_itemcount[$code];
      //   $grouped[$code]['total_inlot'] += $lot['count_inlot'];
      //   $grouped[$code]['total_sell'] += $lot['total_sell'];
      //   $grouped[$code]['remain'] += $lot['remain_qty'];
      //   $grouped[$code]['priceAll'] += $lot['product_priceAll'];
      //   $grouped[$code]['pricecenter_All'] += $lot['price_centerAll'];
      //   $grouped[$code]['shipping_cost'] += $lot['shipping_cost'];
      //   $grouped[$code]['expenses'] += $lot['expenses'];
      //   $grouped[$code]['price_seller'] += $lot['total_sell_value'];

      // }

      // $grouped = array_values($grouped);

      // echo "<pre>";
      // print_r($grouped);
      // echo "</pre>";
     
      //  print json_encode([
      //   'data' => $data,
      //   'data_sell' => $data_sell,
      // ],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      // ----- เริ่ม: แทนที่บล็อกเดิมทั้งหมดที่ดึง $data และคำนวณ $lot_resutl -----

// 1) ดึงรายการสินค้าที่อยู่ในล็อตที่ร้องขอ (หลายสินค้าต่อ 1 lot)
$lot_number = $_GET['LOT'];
$get_products_in_lot_sql = "
SELECT 
  
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
echo "productsInLot <br/>";
echo "<pre>";
  print_r($productsInLot);
  echo "</pre>";
  echo "<br/><hr/>";


// Prepare statement to get total sold for a product
$get_total_sold_sql = "
SELECT COALESCE(SUM(tatol_product),0) AS total_sold
FROM list_productsell
WHERE productname = ?
";
$stmtSold = $conn->prepare($get_total_sold_sql);

// Prepare statement to get prior lots total qty for a product (older than current lot)
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

// Optional: prepare statement to get sale rates (we'll compute average saleRate for product)
$get_sale_rates_sql = "
SELECT rate_customertype, tatol_product
FROM list_productsell
WHERE productname = ?
ORDER BY list_sellid ASC
";
$stmtRates = $conn->prepare($get_sale_rates_sql);

$get_sales_bydate_sql = "SELECT
      create_at,tatol_product
      FROM list_productsell
      WHERE productname = ?
      ORDER BY create_at ASC
    ";
    $stmtSalesByDate = $conn->prepare($get_sales_bydate_sql);

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

    // sold allocated to previous lots = min(totalSold, priorLotQty)
    $soldAllocatedToPrev = min($totalSold, $priorLotQty);

    // remaining to allocate to this and later lots
    $remainingToAllocate = max(0, $totalSold - $soldAllocatedToPrev);

    // sold in THIS lot = min(lotQty, remainingToAllocate)
    $soldInThisLot = min($lotQty, $remainingToAllocate);

    $sold_out_date = null;
    $stmtSalesByDate->bind_param("s",$p_idname);
    $stmtSalesByDate->execute();
    $resSales = $stmtSalesByDate->get_result();
    $cumulativeSold = 0;

    while($rs = $resSales->fetch_assoc()){
      $saleDate = $rs['create_at'];
      $saleQty = intval($rs['tatol_product']);
      $cumulativeSold += $saleQty;
      if($cumulativeSold >= ($priorLotQty + $lotQty)){
        $sold_out_date = $saleDate;
        break;
      }
    }

    // if remainingToAllocate <= 0 => soldInThisLot becomes 0 automatically

    // 3) คำนวณราคาขายต่อลัง (ตัวอย่างใช้ average rate จาก list_productsell)
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

    // 4) คำนวณต้นทุน / ค่าส่ง ต่อลัง
    $product_price = floatval($stock['product_price']);
    $shipping_cost_total = floatval($stock['shipping_cost']);
    $shipping_one = ($lotQty > 0) ? ($shipping_cost_total / $lotQty) : 0;
    $one_capital = $product_price + $shipping_one;
    $difference_one = floatval($stock['price_center']) - $one_capital;

    $capital_all = $one_capital * $lotQty;
    $capital_using = $one_capital * ($lotQty - $soldInThisLot); // คงเหลือหลังหักการขายในล็อตนี้
    $capitalall_return = $one_capital * $soldInThisLot;

    $price_center = floatval($stock['price_center']);
    $price_center_return = $price_center * $soldInThisLot;
    $difference = ($price_center * $soldInThisLot) - ($one_capital * $soldInThisLot);

    $lot_resutl[] = [
        'id' => $p_id,
        'p_name' => $p_name,
        'id_pname'=> $p_idname,
        'lot_no'=> $lot_code,
        'count_inlot' => $lotQty,
        'total_sell' => $soldInThisLot,
        'remain_qty' => $lotQty - $soldInThisLot,
        'product_price' => $product_price,
        'product_priceAll' => $product_price * $lotQty,
        'one_capital' => $one_capital,
        'difference_one' => $difference_one,
        'capital_all' => $capital_all,
        'capital_using' => $capital_using,
        'capitalall_return' => $capitalall_return,
        'price_center' => $price_center,
        'price_centerAll' => $price_center * $lotQty,
        'price_center_return' => $price_center_return,
        'difference' => $difference,
        'one_sell' => $saleRateAvg,
        'expenses' => $stock['expenses'],
        'profit_all' => ($saleRateAvg * $soldInThisLot) - ($price_center * $soldInThisLot),
        'shipping_one' => $shipping_one,
        'shipping_cost' => $shipping_cost_total,
        'total_sell_value' => $totalSellValue,
        'create_at' => $create_at,
        'prior_lots_qty' => $priorLotQty,
        'sold_out_date' => $sold_out_date,
        'totalSold' => $totalSold,
    ];
}

// free statements
$stmtSold->close();
$stmtPrior->close();
$stmtRates->close();
  echo "<pre>";
  print_r($lot_resutl);
  echo "</pre>";



// ----- จบการคำนวณ $lot_resutl -----

      
    }

?>