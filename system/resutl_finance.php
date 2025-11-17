<?php
session_name("session_smokker");
  session_start();
  include_once("../backend/config.php");
include_once("../link/link-2.php");
include_once("../components/component.php");
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
    <link rel="stylesheet" href="../assets/scripts/module/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <link rel="stylesheet" href="../assets/scss/navigationTrue-a-j.scss">
    <link rel="stylesheet" href="../assets/scss/revenue.scss">
    <link rel="stylesheet" href="../assets/scripts/module/test/test.scss">
    <script src="../assets/scripts/module/test/test.js"></script>
    <script src="../assets/scripts/script-bash.js"></script>
  <title>Document</title>
</head>
<body>
  <div class="page-wrapper chiller-theme toggled">
    <?php  navigationOfiicer(); ?>
    <main class="page-content mt-0 mx-0">
      <?php navbar("สรุปการเงิน"); ?>
      <div class="container-fluid row">
        <a class="ml-auto px-4 mx-4 py-1 w-22 text-success btn-print" href="details/PDF/PDF_stocks.php" target="_blank">
            <i class="fas fa-file-code px-2"></i> PDF สรุปสินค้าทั้งหมด
          </a>
          <a class="px-4 mx-4 py-1 w-22 btn-print" href="details/PDF/PDF_financelot.php" target="_blank">
            <i class="fas fa-file-code px-2"></i> PDF สรุปสต็อกสินค้า
          </a>
        <?php
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
          $rate_costometype = 0;
          $totalSellValue = 0;
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
              //$totalSellValue += $soldFromLot * $saleRate;
            }
          
          }
          $price_shipping = $stock['product_price'] + ($stock['shipping_cost'] / $lotQty);
          $capital_received = ($price_shipping * $lotQty) - ($price_shipping * $remainQty); //ทุนที่ได้กลับมา
          
          $shipping_sell = ($stock['shipping_cost'] / $lotQty) * $soldQtry; //ค่าส่งที่ขายไป
          
          $capital_sell = $price_shipping * $soldQtry; //ทุนที่ขายไป + ค่าส่ง
          $expenses = $rate_costometype * $soldQtry; //ราคาขายทั้งหมด
          $pricecenter_delcaptialshiipin = ($stock['price_center'] * $soldQtry) - $capital_sell; //ส่วนต่าง (ราคากต้นทุน + ค่าส่ง ลบ ราคากลาง)
          $profit = $expenses - ($stock['price_center'] * $soldQtry); //กำไร = ราคาขาย - ราคากลาง


          $lot_resutl[] = [
            'id' => $p_id,
            'p_name' =>$p_name,
            'id_pname'=> $p_idname,
            'lot_no'=> $lot_code,
            'count_inlot' => $lotQty, //จำนวนฝน lot
            'total_sell' => $soldQtry, // จำนวนขาย
            'remain_qty' => $remainQty, //คงเหลือ
            
            'product_price' => $stock['product_price'], //ราคาต้นทุนต่อลัง
            'price_shipping' => $price_shipping, // ราคาต้นทุน + ค่าส่ง ต่อลัง
            'product_priceAll' => $stock['product_price'] * $lotQty, //ราคาซื้อทั้งหมด
            'pricebuy_shippingAll' => $price_shipping * $lotQty, // ราคาต้นทุน + ค่าส่ง ทั้งหมด
            'capital_using'=> $price_shipping * $remainQty, //ทุนกำลังใช้
            'capital_received' => $capital_received,  //ทุนที่ได้รับกลับมา สูทธิ์
            'capital_delshipping' => $capital_received, // ทุนที่ได้ไม่รวมค่าส่ง
            'captial_shipping' => $capital_received + $shipping_sell, // ทุนที่ได้รวม ค่าส่งสั่งซื้อ
             
            'price_center' => $stock['price_center'], // ราคากลาง
            'price_centerAll' => $stock['price_center'] * $lotQty, // ราคากลางทั้งหมด
            'pricecenter_using' => $stock['price_center'] * $remainQty, // ราคากลางกำลังใช้
            'pricecenter_received' => $stock['price_center'] * $soldQtry, // ราคากลางที่ได้รับกลับมา
            'pricecenter_delcaptialshiipin' => $pricecenter_delcaptialshiipin, // ส่วนต่าง ราคากลาง - (ต้นทุน + ค่าส่งสั่งซื้อ)
            'shipping_one' => $stock['shipping_cost'] / $lotQty, // ค่าส่งสั่งซื้อทั้งหมด / จำนวน
            'shipping_cost' => $stock['shipping_cost'], //ค่าส่งทั้งหมดจากสั่งซื้อ
            
            'profit' => $expenses - ($stock['price_center'] * $soldQtry), //กำไร = ราคาขาย - ราคากลาง
            'expenses' => $expenses, //ราคาขายทั้งหมด
            'sell' => 'sell',
            
            //'total_sell_value' => $totalSellValue 
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
              'priceAll' =>0,
              'pricebuy_shippingAll' => 0,
              'pricecenter_All' => 0,
              'shipping_cost' =>0,
              'expenses' => 0,
              'price_seller' => 0,
              'capital_using' => 0,
              'capital_received' => 0,
              'capital_delshipping' => 0,
              'captial_shipping' => 0,
              'pricecenter_using' => 0,
              'pricecenter_received' => 0,
              'pricecenter_delcaptialshiipin' => 0,
              'profit' => 0,
            ];
            $lot_itemcount[$code] = 0;
          }
          $lot_itemcount[$code]++;

          $grouped[$code]['count'] = $lot_itemcount[$code];
          $grouped[$code]['total_inlot'] += $lot['count_inlot'];
          $grouped[$code]['total_sell'] += $lot['total_sell'];
          $grouped[$code]['remain'] += $lot['remain_qty'];
          $grouped[$code]['priceAll'] += $lot['product_priceAll'];
          $grouped[$code]['pricebuy_shippingAll'] += $lot['pricebuy_shippingAll'];
          $grouped[$code]['pricecenter_All'] += $lot['price_centerAll'];
          $grouped[$code]['shipping_cost'] += $lot['shipping_cost'];
          $grouped[$code]['expenses'] += $lot['expenses'];

          $grouped[$code]['capital_using'] += $lot['capital_using'];
          $grouped[$code]['capital_received'] += $lot['capital_received'];
          $grouped[$code]['capital_delshipping'] += $lot['capital_delshipping'];
          $grouped[$code]['captial_shipping'] += $lot['captial_shipping'];
          $grouped[$code]['pricecenter_using'] += $lot['pricecenter_using'];
          $grouped[$code]['pricecenter_received'] += $lot['pricecenter_received'];
          $grouped[$code]['pricecenter_delcaptialshiipin'] += $lot['pricecenter_delcaptialshiipin'];
          $grouped[$code]['profit'] += $lot['profit'];
          //$grouped[$code]['price_seller'] += $lot['total_sell_value'];

        }

        $grouped = array_values($grouped);
        ?>
        <div class="col-12 row mt-2 bg-white mx-0 p-0">
          <div class="col-md-12">
            <div class="table-responsive table-responsive-data2 mt-2">
                <table class="table table-data2 border p-0">
                    <thead class="alert alert-primary">
                        <tr>
                            <th></th>
                            <th>Lot No.</th>
                            <th>จำนวน</th>
                            <th>ทุนทั้งหมด + ค่าส่ง</th>
                            <!-- <th>ต้นทุนที่กำลังใช้</th> -->
                            <th>ต้นทุนที่ได้กลับมา</th>
                            <th>ราคากลาง</th>
                            <th>ส่วนต่าง</th>
                            <th>ราคาขาย</th>
                            <th>กำไร</th>
                            <th style="width:5%;">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        foreach($grouped as $key => $rows){
                          listResutlFinance(
                            ($key+1),$rows['lot_code'],$rows['count'],$rows['total_inlot'],$rows['total_sell'],$rows['remain'],
                            $rows['pricebuy_shippingAll'],$rows['pricecenter_All'],$rows['shipping_cost'],$rows['expenses'],$rows['capital_using'],
                            $rows['capital_received'],$rows['capital_delshipping'],$rows['captial_shipping'],
                            $rows['pricecenter_using'],$rows['pricecenter_received'],$rows['pricecenter_delcaptialshiipin'],
                            $rows['profit']
                          );
                        }
                      ?>
                    </tbody>
                </table>
                || <?php echo count($grouped); ?>
            </div>
          </div>
      </div>
      </div>
    </main>
  </div>
</body>
</html>