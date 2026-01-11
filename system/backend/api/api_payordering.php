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
      
      $result_data = [];
      $outstandingAmount = 0;
          $sql = "SELECT 
                OB.order_id,OB.order_name,OB.lot_numbers,OB.slip_order,OB.totalcost_order,OB.count_order,OB.date_time_order,
                COUNT(PRD.payorder_debtpaid_id) AS pay_times,
                COALESCE(SUM(PRD.total_payment), 0) AS paid_total,
                (OB.totalcost_order - COALESCE(SUM(PRD.total_payment), 0)) AS balance
                FROM order_box OB LEFT JOIN payorder_debtpaid PRD ON OB.order_id = PRD.orders_id 
                GROUP BY
                  OB.order_id,
                  OB.order_name,
                  OB.lot_numbers,
                  OB.slip_order,
                  OB.totalcost_order,
                  OB.count_order,
                  OB.date_time_order,
                  OB.create_at
                ORDER BY CASE 
                  WHEN (OB.totalcost_order - COALESCE(SUM(PRD.total_payment), 0)) = 0 
                  THEN 1 
                  ELSE 0 
                END ASC,
                OB.create_at DESC";;
          $query_ql = mysqli_query($conn,$sql)or die(mysqli_error($conn));
          $numrow = mysqli_num_rows($query_ql);
          while($row = mysqli_fetch_assoc($query_ql)){
            $result_data[] = $row;
            $outstandingAmount += (float)$row['balance'];
          }

      print json_encode([
            'status'=> 201,
            'message' => 'get api rate price is success',
            'outstandingAmount' => $outstandingAmount,
            'data' => $result_data,
            
          ],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

?>