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
      <?php navbar("คลังสินค้า"); ?>
      <div class="container-fluid row">
          <div class="ml-auto border">
            <a href="details/PDF/PDF_stocks.php" target="_blank" class="font-weight-bold remove-btn ml-auto px-5 mt-4">
                <i class="fa-solid fa-file-pdf"></i>
                  print pdf
            </a>
          </div>
          <div class="col-md-12 mt-4">
            <div class="table-responsive table-responsive-data2 mt-2">
                        <table class="table table-data2 mydataTablePatron">
                            <thead>
                                <tr>
                                    <th style="width:5%">ลำดับ</th>
                                    <th style="">ชื่อ สินค้า</th>
                                    <!-- <th>จำนวนครั้งที่ซื้อ</th> -->
                                    <th style="width:15%">จำนวนทั้งหมด ลัง</th>
                                    <!-- <th>ต้นทุนทั้งหมด บ.</th> -->
                                    <th style="width:15%">ขายไปแล้ว ลัง.</th>
                                    <th style="width:15%">สินค้าคงเหลือ ลัง</th>
                                    <th style="width:5%">จัดการ</th>
                                </tr> 
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT SP.product_name, 
                                     SUM(SP.product_count * SP.product_price) AS resutl_price, SUM(SP.product_count) AS total_count,
                                     COALESCE(PS.tatol_product, 0) AS total_product, COALESCE(PS.price_to_pay, 0) AS total_pay, NP.product_name AS is_productname
                                     FROM stock_product SP LEFT JOIN name_product NP ON SP.product_name = NP.id_name 
                                     LEFT JOIN ( SELECT productname, SUM(tatol_product) AS tatol_product, SUM(price_to_pay) AS price_to_pay FROM list_productsell GROUP BY productname) PS 
                                     ON SP.product_name = PS.productname GROUP BY SP.product_name, NP.product_name";
                                     $selectStockProduct = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                                     $num_rows = mysqli_num_rows($selectStockProduct);
                                     if($num_rows > 0){
                                      foreach($selectStockProduct as $key => $res){
                                          tablelistStock(
                                              ($key+1),$res['product_name'], $res['is_productname'], 0,$res['total_count'],$res['resutl_price'],$res['total_product'] ?? 0,
                                            );
                                      }
                                    }
                                ?>
                            </tbody>
                        </table> 
                    </div>
          </div>
      </div>
      
    </main>
  </div>
  
</body>
</html>