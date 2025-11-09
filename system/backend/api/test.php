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
      $get_product = "SELECT COUNT(*) AS total_lot, product_id, product_name, SUM(product_count) AS sum_product, lot_number FROM stock_product WHERE product_name=1 GROUP BY lot_number";
      $query = $conn->query($get_product);
      $data = [];
      while($row = $query->fetch_assoc()){
        $data[] = $row;
      }
      $get_productsell = "SELECT COUNT(*) AS totalcount, SUM(tatol_product) AS tatol_product, SUM(price_to_pay) AS price_to_pay FROM list_productsell WHERE productname=1";
      $query_sell = $conn->query($get_productsell);
      $data_sell = [];
      while($is_row = $query_sell->fetch_assoc()){
        $data_sell[] = $is_row;
      }
       print json_encode([
        'data' => $data,
        'data_sell' => $data_sell,
      ],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
      
    }

?>