<?php
session_name("session_smokker");
  session_start();
include_once("../link/link-2.php");
include_once("../backend/config.php");
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
    <link rel="stylesheet" href="../assets/scripts/module/select-picker/select.scss">
  <title>Document</title>
</head>
<body>
  <div class="page-wrapper chiller-theme toggled">
    <?php  navigationOfiicer(); ?>
    <main class="page-content mt-0">
      <?php navbar("รายการสินค้า"); ?>
      <div class="container-fluid row">
          <div class="ml-auto">
            <button class="bd-none au-btn au-btn-icon au-btn--green au-btn--small" data-toggle="modal" 
                data-target="#modalcreateformproduct" id="openModalFormNameProduct"
            >
                <i class="fas fa-plus"></i>
                  เพิ่มข้อมูลินค้า
            </button>
          </div>
          <div class="col-md-12 mt-4"> 
            <div class="col-xl-11 col-md-12">
              <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                      <thead>
                          <tr>
                              <th></th>
                              <th>ลำดับ</th>
                              <th style="width:25%">ซื้อสินค้า</th>
                              <th>ราคาต้นทุนต่อลัง</th>
                              <th>ราคากลางต่อลัง</th>
                              <th>จำนวนคอตต่อลัง</th>
                              <th>ค่าส่งต่อลัง</th>
                              <th>จัดการ</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php
                              $get_productname = mysqli_query($conn, "SELECT * FROM name_product ORDER BY product_name ASC")or die(mysqli_error());
                                foreach($get_productname as $key => $res){
                                    tableNameProduct(
                                        ($key+1), $res['id_name'], $res['product_name'],$res['price'],$res['price_center'],$res['count_cord'],$res['shipping_cost']
                                      );
                                }
                          ?>
                      </tbody>
                  </table> 
              </div>
            </div>
            
          </div>
      </div>
    </main>
    <main-create-product></main-create-product>
  </div>
  <script type="module" src="../assets/scripts/ui-product.js"></script>
</body>
</html>