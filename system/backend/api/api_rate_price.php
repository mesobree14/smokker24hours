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
      $productname = $_GET['id_productname'];
      $result_data = [];
      if($productname){
          $sql = "SELECT * FROM rate_price WHERE id_productname=$productname";
          $query_ql = mysqli_query($conn,$sql)or die(mysqli_error($conn));
          $numrow = mysqli_num_rows($query_ql);
          while($row = mysqli_fetch_assoc($query_ql)){
            $result_data[] = $row;
          }
      }else{
        $result_data[] = [];
      }
      print json_encode([
            'status'=> 201,
            'message' => 'get api rate price is success',
            'data' => $result_data
          ],JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

?>