<?php
session_name("session_smokker");
  session_start();
include_once("../../backend/config.php");
include_once("../../link/link-2.php");
include_once("../../components/component.php");
$custom_name = $_GET['custom_name'];
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
      <?php navbar("รายละเอียดลูกค้า / ".$custom_name."", "../"); ?>
      <div class="container-fluid row">
          <div class="col-md-12">
            <?php
              $sql = "SELECT COUNT(*) AS count_order, SUM(is_totalprice) AS prices_sell, custome_name, SUM(count_totalpays) AS prices_pay, SUM(count_stuck) AS count_stuck FROM orders_sell WHERE custome_name='$custom_name' GROUP BY custome_name";
              $query_sqli = mysqli_query($conn,$sql) or die(mysqli_error($conn));
              $rows = mysqli_fetch_assoc($query_sqli);

              //$sql_tell = mysqli_query($conn,"SELECT tell_custome,location_send FROM orders_sell WHERE custome_name='$custom_name' ORDER BY create_at DESC LIMIT 1");
              //$rows_tell = mysqli_fetch_assoc($sql_tell);

              $sqli_debt = mysqli_query($conn,"SELECT COUNT(*) AS total_pay, SUM(count_debtpaid) AS count_dabtprice FROM custom_debtpaid WHERE name_customer='$custom_name'");
              $row_pay = mysqli_fetch_assoc($sqli_debt);
            ?>
            <div class="col-12 shadow-lg row">
              <div class="col-12 row">
                <a href="#" target="_blank" class="ml-auto px-5 mt-4">
                   <i class="fas fa-file-code\"></i>
                   Print PDF
                </a>
                <div class="col-12 row p-0 m-0">
                    <div class="col-sm-12 col-md-5 col-lg-4">
                      <div class="card shadow  border col-12" style="min-height:195px;">
                        <div class="card-body">
                          <div class="row align-items-center">
                            <div class="text-xs font-weight-bold text-uppercase">
                              ชือลูกค้า : [ <?php echo $rows['custome_name'] ?> ]
                            </div>
                            <!-- <div class="text-xs font-weight-bold text-uppercase mt-3">
                              เบอร์โทร(ล่าสุด) :  <?php //echo $rows_tell['tell_custome'] ?>
                            </div>
                            <div class="text-xs font-weight-bold text-uppercase mt-3">
                              ที่อยู่(ล่าสุด) :  <?php //echo $rows_tell['location_send'] ?>
                            </div> -->
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-12 col-md-7 col-lg-8 row">
                      <?php 
                        $debtpay_balance = $rows['count_stuck'] - $row_pay['count_dabtprice'];
                        boxCustom("จำนวนรายการที่ซื้อ",$rows['count_order'], "ครั้ง");
                        boxCustom("จำนวนหนี้ที่ติด",number_format($rows['count_stuck'] ?? 0,2,'.',','),"บาท");
                        boxCustom("จ่ายหนี้ [ ".number_format($row_pay['total_pay'] ?? 0)." ครั้ง]",number_format($row_pay['count_dabtprice'] ?? 0,2,'.',','),"บาท");
                        boxCustom("จำนวนเงินที่จ่าย",number_format($rows['prices_pay'] ?? 0,2,'.',','),"บาท");
                        boxCustom("จำนวนเงินทั้งหมด",number_format($rows['prices_sell'] ?? 0,2,'.',','),"บาท");
                        boxCustom("หนี้คงเหลือ",number_format($debtpay_balance ?? 0,2,'.',','),"บาท");
                      ?>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
              <div class="col-12 row">
                <div class="tabs">
                  <div class="tab-button-outer">
                    <ul id="tab-button-custom">
                      <li><a href="#tabC01">ประวัติการสั่งซื้อ</a></li>
                      <li><a href="#tabC02">ประวัติการจ่ายหนี้</a></li>
                    </ul>
                  </div>
                </div>
                <div class="ml-auto border">
                  <button class="bd-none au-btn au-btn-icon au-btn--green au-btn--small" 
                      data-toggle="modal" data-custome='<?php echo $custom_name ?>' data-debt='<?php echo $debtpay_balance ?>' 
                      data-types="IN" data-target="#modalFormPayOffDebt"
                      id="modelpayoff_debt"
                  >
                      <i class="fas fa-plus"></i>
                        เพิ่มการจ่ายหนี้
                  </button>
                </div>
              </div>
              <div id="tabC01" class="tab-contents">
                <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ลำดับ</th> 
                            <th style="width:20%;">รหัสรายการขาย</th>
                            <th>วันที่สั่งซื้อ <i class="fa-solid fa-arrow-up"></i></th>
                            <th>ราคาทั้งหมด</th>
                            <th>ราคาที่จ่าย</th>
                            <th>ราคาที่ติดค้าง</th>
                            <th>รายการสินค้า</th>
                            <th>กดดูรายละเอีด</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $sql = "SELECT * FROM orders_sell WHERE custome_name='$custom_name' ORDER BY create_at DESC";
                        $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                        foreach($query as $key => $list_order){
                          listOrderForCustomer(
                            ($key+1),$list_order['id_ordersell'],$list_order['ordersell_name'],$list_order['date_time_sell'],
                            $list_order['is_totalprice'],$list_order['count_totalpays'],$list_order['count_stuck'],3
                          );
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div id="tabC02" class="tab-contents">
                <div class="table-responsive table-responsive-data2 mt-2">
                  <table class="table table-data2 mydataTablePatron">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ลำดับ</th> 
                            <th>รหัสการจ่าย</th>
                            <th>จำนวนเงินที่จ่าย</th>
                            
                            <th>วันที่จ่าย <i class="fa-solid fa-arrow-up"></i></th>
                            <th style="width:35%">เหตุผล</th>
                            <th>รูปภาพ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                          $query_debt = mysqli_query($conn,"SELECT * FROM custom_debtpaid WHERE name_customer='$custom_name' ORDER BY create_at DESC");
                          foreach($query_debt as $key => $result){
                            listhistoryPayDebt(($key+1),$result['id_debtpaid'],$result['serial_number'],$result['name_customer'],
                            $result['text_reason'],$result['count_debtpaid'],$result['debtpaid_balance'],$result['datetime_pays'],$result['img_debt']);
                          }
                        ?>
                    </tbody>
                  </table>
                </div>
              </div>
          </div>
        </div>
      </div>
      <main-pay-debt></main-pay-debt>
    </main>
  </div>
  <script src="../../assets/scripts/ui-custom.js"></script>
  
</body>
</html>