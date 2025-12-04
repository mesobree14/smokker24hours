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
      <?php navbar("สรุปรายรับรายจ่ายราคากลาง"); ?>
      <div class="container-fluid row">
        <div class="col-12 row mb-2">
          <button type="button" class="ml-auto px-4 mx-2 py-1 w-20 btn-print" id="select_stcokdate">PDF สรุปกำไรเป็นวัน</button>
            <a class=" px-4 py-1 w-22 btn-print" href="details/PDF/PDF_finance.php" target="_blank">
                <i class="fas fa-file-code px-2"></i> PDF
            </a>
          </div>
        <div class="col-12 row">
          <?php
            $pricecenter_all = mysqli_query($conn,"SELECT SUM(price_center - product_price) AS profit_center FROM stock_product");
            $acc_res = mysqli_fetch_assoc($pricecenter_all);
            $count_profit = $acc_res['profit_center'] ?? 0;
            setData("กำไรทั้งหมด",number_format($count_profit,2,'.',','));
            $pricecenter_profit = mysqli_query($conn,"SELECT COUNT(*) AS count, SUM(LP.tatol_product) AS is_total,NP.price,NP.price_center,NP.product_name FROM list_productsell LP LEFT JOIN name_product NP ON NP.id_name = LP.productname GROUP BY LP.productname");
            while($row=mysqli_fetch_assoc($pricecenter_profit)){
              echo $row['product_name']." | ";
              echo $row['is_total']." | ";
              echo $row['price']." | ";
              echo $row['price_center']." | ";
              echo $row['price_center'] - $row['price'];
              echo "<br/>";
            }

          ?>
        </div>
        <div class="col-12 row">
              <div class="ml-auto mt-3">
                <button class="bd-none au-btn au-btn-icon au-btn-orange au-btn--small mx-4" data-toggle="modal" 
                    data-target="#modalFormWithdrawProfit" id="modal_formwithdraw_profit"
                >
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                      เพิ่มข้อมูลเบิกถอน
                </button>
              </div>
            </div>
      </div>
      <main-profit-withdraw></main-profit-withdraw>
    </main>
  </div>
</body>
<script src="../assets/scripts/ui-isprofit.js"></script>
</html>