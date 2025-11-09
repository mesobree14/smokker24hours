<?php
  include_once("../../../backend/config.php");
  if(!$conn){
    die("not conn");
    
  }
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");	
   header("Cache-Control: post-check=0, pre-check=0", false);	
    header("Pragma: no-cache");
     date_default_timezone_set("Asia/Bangkok");
     if($_SERVER['REQUEST_METHOD'] === "GET"){
          // $sql_stock = "SELECT SP.product_name, RP.price_levels_one, RP.price_customer_frontstore, RP.price_customer_deliver, RP.price_customer_dealer,RP.level_sell,
          // COUNT(*) AS total, SUM(SP.product_count * SP.product_price) AS resutl_price, SUM(SP.product_count) AS total_count,LPS.tatol_products_sell,NP.product_name AS is_productname
          // FROM stock_product SP LEFT JOIN name_product NP ON SP.product_name = NP.id_name LEFT JOIN rate_price RP ON SP.product_name = RP.id_productname
          // LEFT JOIN (SELECT productname, SUM(tatol_product) AS tatol_products_sell FROM list_productsell GROUP BY productname) LPS ON SP.product_name = LPS.productname
          // GROUP BY SP.product_name, RP.price_levels_one, RP.price_customer_frontstore, RP.price_customer_deliver, RP.price_customer_dealer
          // ";
          $sql_stock = "SELECT SP.product_name,
          COUNT(*) AS total, SUM(SP.product_count * SP.product_price) AS resutl_price, SUM(SP.product_count) AS total_count,LPS.tatol_products_sell,NP.product_name AS is_productname
          FROM stock_product SP LEFT JOIN name_product NP ON SP.product_name = NP.id_name
          LEFT JOIN (SELECT productname, SUM(tatol_product) AS tatol_products_sell FROM list_productsell GROUP BY productname) LPS ON SP.product_name = LPS.productname
          GROUP BY SP.product_name
          ";
          $query = mysqli_query($conn, $sql_stock)or die(mysqli_error($conn));
          $num_row = mysqli_num_rows($query);
          
          if($num_row > 0){
             $get_data = array();
             foreach($query as $res_data){
                $res_data['remaining_product'] = $res_data['total_count'] - $res_data['tatol_products_sell'];
                $get_data[] = $res_data;
             }
             header('Content-Type: application/json');
              print json_encode(array(
                'status'=> 201,
                'message'=> 'get data is success',
                'data'=> $get_data
              ));
              mysqli_close($conn);
         }else{
           print json_encode(array(
               'status'=> 201,
               'message'=> 'get data is success',
               'data'=> []
             ));
         }
     }
?>