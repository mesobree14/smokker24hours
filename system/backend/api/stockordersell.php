<?php

include_once("../../../backend/config.php");
  if(!$conn){
    die("not conn");
    
  }
  header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");	
   header("Cache-Control: post-check=0, pre-check=0", false);	
    header("Pragma: no-cache");
    date_default_timezone_set("Asia/Bangkok");
    if($_SERVER['REQUEST_METHOD'] === "DELETE"){
      $id_ordersell = $_GET['ordersell_id'];
      $arrays = [];
      $countsuccess = 0;
      $trashimg = "";
      $type_pays = [];

      $sql = mysqli_query($conn,"SELECT id_ordersell, slip_ordersell,ordersell_name FROM orders_sell WHERE id_ordersell=$id_ordersell");
      if(mysqli_num_rows($sql) > 0){
        $data = mysqli_fetch_assoc($sql);
        $trash_ordersell = mysqli_query($conn,"DELETE FROM orders_sell WHERE id_ordersell=$id_ordersell");
        if($trash_ordersell){
          if($data['slip_ordersell'] !== ""){
            if(file_exists(__DIR__ . "/../../../db/slip-sellorder/" . $data['slip_ordersell'])){
              if(unlink(__DIR__ . "/../../../db/slip-sellorder/" . $data['slip_ordersell'])){
                $trashimg = "unlink image success";
              }
            }
          }
          $del_typepay = mysqli_query($conn,"DELETE FROM sell_typepay WHERE ordersell_id=$id_ordersell");
          if($del_typepay){
            $type_pays[] = [
              'deletetype_pay' => 'success'
            ];
          }else{
            $type_pays[] = [
              'deletetype_pay' => 'false'
            ];
          }
              $trash_productsell = mysqli_query($conn,"DELETE FROM list_productsell WHERE ordersell_id=$id_ordersell");
              if($trash_productsell){
                $arrays[] = [
                  'name' => 'test',
                  'status'=> 'success'
                ];
              }else{
                $arrays[] = [
                    'name' => 'test',
                    'status' => 'fail'
                  ];
              }
            }
            print json_encode(array(
                'status'=> 201,
                'order_id'=> $id_ordersell,
                'trashimg' => $trashimg,
                'message' => 'delete finished',
                'result' => $arrays,
                'type_pays'=>$type_pays
              ));
         // }
        //}
      }
    }elseif($_SERVER['REQUEST_METHOD'] == "GET"){
      $id_ordersell = $_GET['ordersell_id'];
      $query_product = mysqli_query($conn,"SELECT * FROM list_productsell LP LEFT JOIN name_product NP ON LP.productname = NP.id_name WHERE LP.ordersell_id=$id_ordersell");
      //$query_product = mysqli_query($conn,"SELECT * FROM list_productsell WHERE ordersell_id=$id_ordersell");

      $num_row = mysqli_num_rows($query_product);
      if($num_row > 0){
        $get_data = array();
        foreach($query_product as $res_data){
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