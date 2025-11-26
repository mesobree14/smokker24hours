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
    <link rel="stylesheet" href="../assets/scripts/module/select-picker/select.scss">
  <title>Document</title>
</head>
<body>
  <div class="page-wrapper chiller-theme toggled">
    <?php  navigationOfiicer(); ?>
    <main class="page-content mt-0">
      <?php navbar("ข้อมูลลูกค้า"); ?>
      <div class="container-fluid">
        <div class="col-12 row mt-2">
          <div class="col-md-12 border-right">
            <div class="table-responsive table-responsive-data2 mt-2">
                <table class="table table-data2">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th style="width:20%">ชื่อลูกค้า</th>
                            <th style="width:12%">รายการที่ซื้อ</th>
                            <th style="width:15%">รวมค่าใช้จ่าย</th>
                            <th style="width:15%">จำนวนเงินที่จ่ายแล้ว</th>
                            <th style="width:15%">จำนวนหนี้ที่ติดค้างอยู่</th>
                            <th style="width:15%">จำนวนครั้งที่จ่ายหนี้</th>
                            <th style="width:13%">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sql = "SELECT 
                            o.custome_name,
                            o.count_order,
                            o.prices_sell,
                            o.prices_pay,
                            o.count_stuck,
                            COALESCE(d.total_debt, 0) AS total_debt,
                            COALESCE(d.count_debt, 0) AS count_debt
                        FROM (
                            SELECT 
                                custome_name,
                                COUNT(id_ordersell) AS count_order,
                                SUM(is_totalprice) AS prices_sell,
                                SUM(count_totalpays) AS prices_pay,
                                SUM(count_stuck) AS count_stuck
                            FROM orders_sell
                            GROUP BY custome_name
                        ) o
                        LEFT JOIN (
                            SELECT 
                                name_customer AS custome_name,
                                COUNT(*) AS total_debt,
                                SUM(count_debtpaid) AS count_debt
                            FROM custom_debtpaid
                            GROUP BY name_customer
                        ) d ON o.custome_name = d.custome_name
                        ";
                        $query_sql = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        $nums = mysqli_num_rows($query_sql);
                        if($nums > 0){
                          
                          foreach($query_sql as $key => $res){
                            listCustomer(($key+1),$res['custome_name'],$res['count_order'],$res['prices_sell'],$res['prices_pay'],$res['count_stuck'],$res['count_debt'],$res['total_debt']);
                          }
                        }
                      ?>
                    </tbody>
                </table>
            </div>
          </div>

        </div>
      </div>
      <main-pay-debt></main-pay-debt>
    </main>
  </div>
  <script src="../assets/scripts/ui-custom.js"></script>
</body>
</html>