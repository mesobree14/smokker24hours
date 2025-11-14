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
    <main class="page-content mt-0">
      <?php navbar("สต็อกสินค้า"); ?>
      <div class="container-fluid row">
        <a class="ml-auto px-4 mx-4 py-1 w-22 text-success btn-print" href="details/PDF/PDF_stocks.php" target="_blank">
            <i class="fas fa-file-code px-2"></i> PDF รวมสินค้า
          </a>
          <a class="px-4 mx-4 py-1 w-22 btn-print" href="details/PDF/PDF_lot.php" target="_blank">
            <i class="fas fa-file-code px-2"></i> PDF สต็อกสินค้า
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
            'product_price' => $stock['product_price'], //ราคาเริ่มต้นต่อชิ้น
            'product_priceAll' => $stock['product_price'] * $lotQty,
            'price_center' => $stock['price_center'], // ราคากลาง
            'price_centerAll' => $stock['price_center'] * $lotQty,
            'shipping_one' => $stock['shipping_cost'] / $lotQty,
            'shipping_cost' => $stock['shipping_cost'],
            'expenses' => $stock['expenses'],
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
              'priceAll' =>0,
              'pricecenter_All' => 0,
              'shipping_cost' =>0,
              'expenses' => 0,
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
          $grouped[$code]['pricecenter_All'] += $lot['price_centerAll'];
          $grouped[$code]['shipping_cost'] += $lot['shipping_cost'];
          $grouped[$code]['expenses'] += $lot['expenses'];
          $grouped[$code]['price_seller'] += $lot['total_sell_value'];

        }

        $grouped = array_values($grouped);
        ?>
        <div class="col-12 row mt-2 bg-white">
          <div class="col-md-12">
            <div class="table-responsive table-responsive-data2 mt-2">
                <table class="table table-data2 border">
                    <thead class="alert alert-primary">
                        <tr>
                            <th></th>
                            <th style="width:17%;">Lot No.</th>
                            <th>รายการ</th>
                            <th>จำนวนในล็อต</th>
                            <th>จำนวนขาย</th>
                            <th>คงเหลือ</th>
                            <!-- <th>ราคาเริ่มต้น</th>
                            <th>ราคากลาง</th>
                            <th>ค่าส่ง</th> -->
                            <!-- <th>ราคาซื้อทั้งหมด</th>
                            <th>ราคาขาย</th> -->
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        foreach($grouped as $key => $rows){
                          listLotProduct(
                            ($key+1),$rows['lot_code'],$rows['count'],$rows['total_inlot'],$rows['total_sell'],$rows['remain'],
                            $rows['priceAll'],$rows['pricecenter_All'],$rows['shipping_cost'],$rows['expenses'],$rows['price_seller']
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