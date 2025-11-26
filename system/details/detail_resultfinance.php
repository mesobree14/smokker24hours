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
           
            <div class="col-12 shadow-lg row">
              <div class="col-12 row">
                <button type="button" class="ml-auto px-4 mx-2 py-1 w-20 btn-print" id="select_detailStcokDate" data-lotnumber="<?php echo $lot_number; ?>">
                  PDF สรุปรายละเอียดสินค้าเป็นวัน
                </button>
                <a class="px-4 mx-4 py-1 w-22 btn-print" href="../details/PDF/PDF_financeinlot.php?lot_number=<?php echo $lot_number ?>" target="_blank">
                  <i class="fas fa-file-code px-2"></i> PDF
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-12">
              <div id="tabC01" class="tab-contents">
                <?php
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
                        'count_inlot' => $lotQty, //จำนวนที่ซื้อall
                        'total_sell' => $soldInThisLot, //จำนวนที่ขาย
                        'remain_qty' => $lotQty - $soldInThisLot, //จำนวนที่เหลือ
                        'product_price' => $product_price, //สินค้าต่อลัง
                        'product_priceAll' => $product_price * $lotQty, // ต้นทุนทั้งหมด สินค้าต่อลัง * จำนวนที่ซื้อall
                        'one_capital' => $one_capital, // สินค้า + ค่าส่ง ต่อลัง
                        'difference_one' => $difference_one, //ราคากลาง - (รา่คา+ค่าส่ง) ต่อลัง
                        'capital_all' => $capital_all, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ซื้อ
                        'capital_using' => $capital_using, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่เหลือ
                        'capitalall_return' => $capitalall_return, //ต้นทุนทั้งหมด (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ขาย
                        'price_center' => $price_center, //  ราคากลางต่อชิ้น
                        'price_centerAll' => $price_center * $lotQty, // ราคากลางต่อชิ้น * จำนวนที่ซื้อall
                        'price_center_return' => $price_center_return, // ราคากลางต่อชิ้น * จำนวนที่ขาย
                        'difference' => $difference, // กำไรทั้งหมด (ราคากลางต่อชิ้น * จำนวนที่ขาย) * ( (สินค้า + ค่าส่ง ต่อลัง) * จำนวนที่ขาย )
                        'one_sell' => $saleRateAvg, // ราคาขายต่อชิ้น
                        'expenses' => $stock['expenses'], // รวมราคาสั่งสื้อทั้งหมด  สินค้าต่อลัง+ค่าส่ง * จำนวนที่ซื้อall
                        'profit_all' => ($saleRateAvg * $soldInThisLot) - ($price_center * $soldInThisLot), //(ราคาขาย * จำนวนขาย) - (ราคากลาง*จำนวนขาย)
                        'shipping_one' => $shipping_one, //ค่าส่งต่อชิ้น
                        'shipping_cost' => $shipping_cost_total, //ค่าส่งทั้งหมด
                        'total_sell_value' => $totalSellValue,
                        'create_at' => $create_at, //date
                        'prior_lots_qty' => $priorLotQty, // จำนวนที่ซื้อก่อนหน้านี้
                        'totalSold' => $totalSold, //รวมจำนวนที่ขายไปแล้ว
                    ];
                }
                  // free statements
  $stmtSold->close();
  $stmtPrior->close();
  $stmtRates->close();
                ?>
                <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                    <thead>
                        <tr>
                            <th></th>
                            <th style="width:15%;">สินค้า</th>
                            <!-- <th>จำนวนซื้อ</th> -->
                            <th>จำนวนขาย</th>
                            <!-- <th>จำนวนคงเหลือ</th> -->
                             <th>ราคา/ต่อลัง</th>
                             <th>กลาง/ต่อลัง</th>
                            <th>(ซื่อ+ค่าส่ง)ต่อลัง</th>
                            <th>ราคาซื้อ + ค่าส่ง</th>
                            <th>ราคากลาง</th>
                            <th>กำไร</th>
                            <!-- <th>ขายต่อลัง</th>
                            
                            
                            <th>ราคาขาย</th>
                            <th>กำไร</th> -->
                            
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach($lot_resutl as $key => $res){
                          detailLotFinance(($key+1),$res['id'],$res['p_name'],$res['id_pname'],$res['lot_no'],$res['count_inlot'],$res['total_sell'],$res['remain_qty'],
                          $res['one_capital'],$res['capitalall_return'],$res['product_price'],$res['price_center'],$res['price_center_return'],$res['difference'],$res['shipping_one'],$res['shipping_cost'],
                          $res['profit_all'],$res['expenses'],$res['create_at']);
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
             
          </div>
        </div>
      </div>
    </main>
  </div>
  <script src="../../assets/scripts/result_finance.js"></script>
</body>
</html>