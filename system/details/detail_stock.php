<?php
session_name("session_smokker");
  session_start();

include_once("../../backend/config.php");
include_once("../../link/link-2.php");
include_once("../../components/component.php");
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
$id_productname = $_GET['id_productname'];
$sql_nameP = mysqli_query($conn,"SELECT * FROM name_product WHERE id_name=$id_productname")or die(mysqli_error($conn));
$acc_name = mysqli_fetch_assoc($sql_nameP);
$sql = "SELECT SP.product_name, SP.product_price,SP.price_center,
       COUNT(SP.product_id) AS total,SUM(SP.expenses) AS resutl_price, SUM(SP.product_count) AS total_count, SUM(SP.count_cord) AS count_cord
       FROM stock_product SP WHERE SP.product_name='$id_productname' GROUP BY SP.product_name";
       $selectStockProduct = mysqli_query($conn,$sql) or die(mysqli_error($conn));
       $acc_fetch = mysqli_fetch_assoc($selectStockProduct);
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
    <link rel="stylesheet" href="../../assets/scripts/module/test/test.scss">
    <script src="../../assets/scripts/module/test/test.js"></script>
    <script src="../../assets/scripts/script-bash.js"></script>
    <link rel="stylesheet" href="../../assets/scripts/module/select-picker/select.scss">
  <title>Document</title>
</head>
<body>
  <div class="page-wrapper chiller-theme toggled">
      <?php  navigationOfiicer("../"); ?>
       <main class="page-content mt-0">
      <?php navbar("รายละเอียดสินค้า / ".$acc_name['product_name']."", "../"); ?>
      <div class="container-fluid row">
          <div class="col-md-12">
            <?php

              $sql_sell = "SELECT productname,COUNT(*) AS counts, SUM(tatol_product) AS total_products, SUM(price_to_pay) AS prices FROM list_productsell WHERE productname='$id_productname' GROUP BY productname";
              $quer_sell = mysqli_query($conn,$sql_sell) or die(mysqli_error($conn));
              $acc_sell = mysqli_fetch_assoc($quer_sell);
 
               detailStock($acc_name['product_name'],$acc_fetch['total_count'],$acc_fetch['count_cord'], $acc_fetch['resutl_price'],$acc_fetch['product_price'], $acc_sell['total_products'] ?? 0,$acc_sell['prices'] ?? 0,$acc_sell['counts'] ?? 0);
            ?>
          </div>
          <div class="col-md-12 mt-4">
                <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                    <thead>
                       <tr>
                          <th></th>
                          <th class="font-weight-bold">ระดับที่ : </th>
                          <th class="font-weight-bold">ราคา เรท 1 vip</th>
                          <th class="font-weight-bold">ราคา เรท 2 หน้าร้าน</th>
                          <th class="font-weight-bold">ราคา เรท 3 ตัวแทน</th>
                          <th class="font-weight-bold">ราคา เรท 4 จัดส่ง</th>
                          <th class="font-weight-bold">จัดการ</th>
                       </tr>
                    </thead>
                    <tbody>
                      <?php
                        
                          $sql = "SELECT * FROM rate_price WHERE id_productname=$id_productname";
                          $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                          $rate_acc_fetch = mysqli_fetch_assoc($query);
                          if($rate_acc_fetch){
                            foreach($query as $key => $res_acc)
                            listRatePrice(
                              $res_acc['rate_id'],$res_acc['level_sell'],$res_acc['price_customer_frontstore'],$res_acc['price_levels_one'],
                              $res_acc['price_customer_dealer'],$res_acc['price_customer_deliver'],$acc_name['product_name'],
                              $acc_name['price'],$acc_name['id_name'],$acc_name['price_center'],$acc_name['count_cord'],$acc_name['shipping_cost']
                            );
                          }else{
                            listRatePrice("","","","","","",$acc_name['product_name'],$acc_name['price'],$acc_name['id_name'],$acc_name['price_center'],$acc_name['count_cord'],$acc_name['shipping_cost']);
                          }
                      ?>
                    </tbody>
                  </table>
                </div>
          </div>
          <div class="col-md-12 mt-4">
            
              <div class="tabs">
                <div class="tab-button-outer">
                  <ul id="tab-button">
                    <li><a href="#tab01">รอบการสั่งซื้อ</a></li>
                    <li><a href="#tab02">รอบการขาย</a></li>
                  </ul>
                </div>
              </div>
            
            <div id="tab01" class="tab-contents">
            <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                      <thead>
                          <tr>
                              <th>ลำดับ</th> 
                              <th>ชื่อ</th>
                              <th>ล็อตสินค้าที่</th>
                              <th>ราคาต้นทุนต่อลัง</th>
                              <th>ราคากลางต่อลัง</th>
                              <th>จำนวน</th>
                              <th>ราคารวมลัง</th>
                              <th>ค่าส่ง</th>
                              <th>ราคาทั้งหมด</th>
                              <th>เวลา</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                              $get_sql = "SELECT stock_product.product_id,stock_product.product_count,stock_product.product_price,stock_product.count_cord,stock_product.price_center,
                              stock_product.shipping_cost,stock_product.expenses,order_box.lot_numbers,order_box.order_name,order_box.date_time_order,name_product.id_name,name_product.product_name AS is_product_name FROM stock_product 
                              LEFT JOIN name_product ON stock_product.product_name = name_product.id_name 
                              LEFT JOIN order_box ON order_box.order_id = stock_product.id_order 
                              WHERE stock_product.product_name='$id_productname' ORDER BY stock_product.create_at DESC";
                               $get_datastock = mysqli_query($conn, $get_sql) or die(mysqli_error($conn));
                                foreach($get_datastock as $key => $res){
                                    tableDetailStock(
                                        ($key+1), $res['product_id'], $res['id_name'],$res['is_product_name'],$res['product_count'],$res['product_price'],$res['count_cord'],$res['price_center'],
                                        $res['order_name'],$res['lot_numbers'],$res['date_time_order'],$res['shipping_cost'],$res['expenses']
                                      );
                                }
                          ?>
                      </tbody>
                  </table> 
            </div>
            </div>
            <div id="tab02" class="tab-contents">
                <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                    <thead>
                        <tr>
                            <th>ลำดับ</th> 
                            <th>ชื่อ</th>
                            <th>จากคำสั่งขาย</th>
                            <th>ประเภทลูกค้า</th>
                            <th>ราคาขายต่อชิ้น</th>
                            <th>จำนวน</th>
                            <th>ราคารวม</th>
                            <th>เวลา</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php
                  $sell_sql = "SELECT * FROM list_productsell PS LEFT JOIN orders_sell OS ON OS.id_ordersell = PS.ordersell_id WHERE PS.productname='$id_productname' ORDER BY PS.create_at DESC";
                  $get_sqlsell = mysqli_query($conn,$sell_sql) or die(mysqli_error($conn));
                  foreach($get_sqlsell as $key => $res2){
                    tableDetailStockSell(($key+1),$res2['list_sellid'],$res2['productname'],$res2['ordersell_name'],$res2['tatol_product'],$res2['rate_customertype'],$res2['type_custom'],$res2['date_time_sell']);
                  }
                ?>
                    </tbody>
                  </table>
                </div>
            </div>
          </div>
      </div>
      <main-rate-price></main-rate-price>
      <main-update-price></main-update-price>
    </main>
  </div>
  <script src="../../assets/scripts/ui-stock.js"></script>
</body>
</html>