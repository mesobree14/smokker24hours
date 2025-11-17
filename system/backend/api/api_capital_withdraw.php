<?php

  include_once("../../../backend/config.php");
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  if(!$conn){
    die("not conn");
    
  }
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");	
   header("Cache-Control: post-check=0, pre-check=0", false);	
    header("Pragma: no-cache");
    date_default_timezone_set("Asia/Bangkok");
    if($_SERVER['REQUEST_METHOD'] === "GET"){
      $capitals = mysqli_query($conn,"SELECT SUM(count_capital) as countcapital FROM capital");
      $acc_capintal = mysqli_fetch_assoc($capitals);
      
      $res_total = mysqli_query($conn,"SELECT SUM(product_count) as totalproduct FROM stock_product");
      $acc_totals = mysqli_fetch_assoc($res_total);
      $used_capital = mysqli_query($conn,"SELECT SUM(totalcost_order) as costordercount FROM order_box");
      $acc_usecapital = mysqli_fetch_assoc($used_capital);
      $profit = mysqli_query($conn,"SELECT SUM(is_totalprice) as totalpices, SUM(count_totalpays) as custom_pay, SUM(count_stuck) as countstuck FROM orders_sell");
      $acc_qlprofits = mysqli_fetch_assoc($profit);
      $total_psell = mysqli_query($conn,"SELECT SUM(tatol_product) as totalproductsell FROM list_productsell");
      $acc_total_psell = mysqli_fetch_assoc($total_psell);
      $quall = "SELECT SUM(is_totalprice) as is_prices, SUM(count_totalpays) as custom_pay, SUM(count_stuck) as countstuck FROM orders_sell";
      $is_quall = mysqli_query($conn,$quall);
      $is_accquall = mysqli_fetch_assoc($is_quall);

      $average_cost = $acc_totals['totalproduct'] > 0 ? $acc_usecapital['costordercount'] / $acc_totals['totalproduct'] : 0;
      $average_sell = $acc_total_psell['totalproductsell'] > 0 ? $acc_qlprofits['totalpices'] / $acc_total_psell['totalproductsell'] : 0;
      $total_capitals = $average_cost * $acc_total_psell['totalproductsell'];
      $total_profit = $acc_qlprofits['totalpices'] - $total_capitals;

      $costorder = $acc_usecapital['costordercount'] - $total_capitals;
      $available = $acc_capintal['countcapital'] - $costorder;
      $remain_capital = bcsub($acc_capintal['countcapital'] ?? 0 ,bcadd($costorder,$is_accquall['countstuck'] ?? 0, 2), 2);

      $sql_useprofit = mysqli_query($conn,"SELECT SUM(count_withdraw) as use_prefit FROM withdraw");
      $acc_useprofit = mysqli_fetch_assoc($sql_useprofit);

      print json_encode(array(
        'status'=> 201,
        'data' => array(
          'capital_all' => number_format($acc_capintal['countcapital'] ?? 0,2,'.',','), //ทุนทั้งหมด
          'use_capital' => number_format($costorder ?? 0,2,'.',','), // ทุนที่กำลังใช้
          'customer_debt' => number_format($is_accquall['countstuck'] ?? 0,2,'.',','), // หนี้ลูกค้ายังไม่จ่าย
          'funds_that_can_be_used' => number_format($remain_capital ?? 0 ,2,'.',','), // ทุนที่สามารถใช้ได้
          'total_profit' => number_format($total_profit ?? 0,2,'.',','), //กำไรทั้งหมด
          'already_withdrawn' => number_format($acc_useprofit['use_prefit'] ?? 0,2,'.',','), //เบิกถอนไปแล้ว
          'can_be_used'=>number_format($total_profit - $acc_useprofit['use_prefit'],2,'.',','), // สามารถใช้ได้
          'average_cost' => number_format($average_cost ?? 0 ,2,'.',','), //เฉลี่ยทุนต่อลัง
          'average_sell' => number_format($average_sell ?? 0,2,'.',',') //เฉลี่ยขายต่อลัง
        )
      ));
      mysqli_close($conn);
    }
?>