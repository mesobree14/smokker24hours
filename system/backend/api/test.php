<?php

  include_once("../../../backend/config.php");
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  if(!$conn){
    die("not conn");
  }
  // header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");	
  //  header("Cache-Control: post-check=0, pre-check=0", false);	
  //   header("Pragma: no-cache");
    date_default_timezone_set("Asia/Bangkok");
    if($_SERVER['REQUEST_METHOD'] === "GET"){

$lot_number = "LOT-A0001";

// 1) ดึงสินค้าทั้งหมดใน LOT นี้
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

  $stmt = $conn->prepare($get_products_in_lot_sql);
  $stmt->bind_param("s",$lot_number);
  $stmt->execute();
  $res = $stmt->get_result();

  $productsInLot = [];
  while ($row = $res->fetch_assoc()) {
      $productsInLot[] = $row;
  }
  $stmt->close();

  $lot_result = [];
  foreach($productsInLot as $stock){
    $p_id = $stock['product_id'];
    $p_name    = $stock['product_name'];
    $p_idname  = $stock['in_productname'];
    $lot_code  = $stock['lot_number'];
    $lotQty    = $stock['product_count']; // จำนวนซื้อใน lot
    $create_at = $stock['create_at'];

    $sql_prior = "SELECT 
      COALESCE(SUM(product_count),0) AS prior_qty
      FROM stock_product WHERE product_name = ? AND lot_number < ?
    ";

    $stmt =$conn->prepare($sql_prior);
    $stmt->bind_param("is", $p_id, $lot_code);
    $stmt->execute();
    $prior_oty = $stmt->get_result()->fetch_assoc()['prior_qty'];
    $stmt->close();

    $sql_sell = "SELECT
      SUM(tatol_product) AS totalSold,
      MIN(create_at) AS firstSellDate,
      MAX(create_at) AS lastSellDate
      FROM list_productsell WHERE productname = ?
    ";
    $stmt = $conn->prepare($sql_sell);
    $stmt->bind_param("i", $p_id);
    $stmt->execute();
    $sellData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $totalSoldAll = intval($sellData['totalSold']);
    $lastSellDate = $sellData['lastSellDate'] ?? null;

    if($totalSoldAll <= $prior_oty){
      $startSellDate = null;
    }else{
      $sql_sell_list = "SELECT tatol_product,create_at FROM list_productsell WHERE productname = ? ORDER BY create_at ASC";
      $stmt = $conn->prepare($sql_sell_list);
        $stmt->bind_param("i", $p_id);
        $stmt->execute();
        $sellList = $stmt->get_result();

        $acc = 0;
        $startSellDate = null;

        while($s = $sellList->fetch_assoc()){
          $acc += $s['tatol_product'];

          if($acc > $prior_oty){
            $startSellDate = $s['create_at'];
            break;
          }
        }
        $stmt->close();
    }
    $soldInThisLot =max(0,$totalSoldAll-$prior_oty);
    if($soldInThisLot > $lotQty){
      $soldInThisLot = $lotQty;
    }
    $price_center     = $stock['price_center'];
    $shipping_cost    = $stock['shipping_cost'];
    $product_price    = $stock['product_price'];
    $expenses         = $stock['expenses'];

    $one_capital      = $product_price + $shipping_cost;
    $difference_one   = $price_center - $one_capital;
    $capital_all      = $one_capital * $lotQty;
    $capital_using    = $one_capital * ($lotQty - $soldInThisLot);
    $capital_return   = $one_capital * $soldInThisLot;
    $price_center_return = $price_center * $soldInThisLot;

    $saleRateAvg = $price_center;  
    $totalSellValue = $saleRateAvg * $soldInThisLot;

    $lot_result[] = [
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
        'capitalall_return' => $capital_return,

        'price_center' => $price_center,
        'price_centerAll' => $price_center * $lotQty,
        'price_center_return' => $price_center_return,

        'difference' => $totalSellValue - $price_center_return,

        'one_sell' => $saleRateAvg,
        'expenses' => $expenses,
        'profit_all' => ($saleRateAvg * $soldInThisLot) - ($price_center * $soldInThisLot),

        'shipping_one' => $shipping_cost,
        'shipping_cost' => $shipping_cost * $lotQty,

        'total_sell_value' => $totalSellValue,

        'create_at' => $create_at,
        'prior_lots_qty' => $prior_oty,
        'totalSold' => $totalSoldAll,

        // 🎉 NEW
        'date_sell' => $startSellDate,   // วันที่เริ่มขาย LOT นี้
        'date_current' => $lastSellDate  // วันที่ขายล่าสุด
    ];

    echo "<pre>"; 
    print_r($lot_result);
    echo"</pre>";
  }


}


?>