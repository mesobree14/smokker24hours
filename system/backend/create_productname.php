<?php
  session_name("session_smokker");
  session_start();
  include_once("../../backend/config.php");
  include_once("../../link/link-2.php");
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  if(!$conn){
      die("not connect". mysqli_connect_error());
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<script type="text/javascript">
  const MySetSweetAlert =(Icons,Titles,Texts,location)=>{
      Swal.fire({
          icon: Icons,
          title: Titles,
          text:Texts,
          confirmButtonText:"OK"
      }).then((result)=>{
           window.location = `${location}`
      })
  }
</script>
  <?php
    date_default_timezone_set("Asia/Bangkok");
      $id_user = $_SESSION['users_data']['id'];
      $day_add = date('Y-m-d H:i:s');
      $checkstatus = [];
      if($_SERVER['REQUEST_METHOD'] === "POST"){
          $product_name = $_POST['product_name'];
          $price_default = $_POST['price_default'];
          $price_center = $_POST['price_center'];
          $count_cord = $_POST['count_cord'];
          $shipping_cost = $_POST['shipping_cost'];
          if(!$_POST['id_name']){
              
              $sql_select = mysqli_query($conn,"SELECT product_name FROM name_product WHERE product_name='$product_name' AND status_del=1");
              $num = mysqli_num_rows($sql_select);
                if($num == 0){
                  
                  $sql = "INSERT INTO name_product(product_name,price,price_center,count_cord,shipping_cost,adder_id,status_del,create_at)
                  VALUES('$product_name','$price_default','$price_center','$count_cord','$shipping_cost','$id_user',1,'$day_add')";
                  $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                  if($query){
                     $is_idname = mysqli_insert_id($conn);
                      $level_sell = $_POST['level_sell'];
                      $rate_vip1 = $_POST['rate_vip1'];
                      $rate_storefront2 = $_POST['rate_storefront2'];
                      $rate_dealers3 = $_POST['rate_dealers3'];
                      $rate_delivery4 = $_POST['rate_delivery4'];
                      for($i = 0; $i < count($level_sell); $i++){
                        $islevel_sell = mysqli_real_escape_string($conn,trim($level_sell[$i]));
                        $israte_vip = mysqli_real_escape_string($conn,trim($rate_vip1[$i]));
                        $israte_storefront = mysqli_real_escape_string($conn,trim($rate_storefront2[$i]));
                        $israte_dealers = mysqli_real_escape_string($conn,trim($rate_dealers3[$i]));
                        $israte_delivery = mysqli_real_escape_string($conn,trim($rate_delivery4[$i]));

                        $sql_rate = "INSERT INTO rate_price (id_productname,product_name,id_adder,level_sell,price_levels_one,price_customer_frontstore,price_customer_deliver,price_customer_dealer,create_at)
                          VALUES ('$is_idname','$product_name','$id_user','$islevel_sell','$israte_vip','$israte_storefront','$israte_delivery','$israte_dealers','$day_add')";
                        $query_insert = mysqli_query($conn,$sql_rate) or die(mysqli_error($conn));
                        if($query_insert){
                          $checkstatus[] = "success level:".$i + 1;
                        }
                      }
                    echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มชื่อสินค้าเรียบร้อยแล้ว\",\"../product.php\")
                        </script>";
                  }else{
                     echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"เพิ่มชื่อสินค้าล้มเหลว\",\"../product.php\")
                        </script>";
                  }

                }else{
                  echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"ชื่อสินค้านี้อยูในระบบแล้ว\",\"../product.php\")
                        </script>";
                }
          }else{
            $ischackstatus = [];
            $id_name = $_POST['id_name'];
            $id_name = (int)$id_name;
            $sql_editselect = mysqli_query($conn,"SELECT product_name,id_name FROM name_product WHERE product_name='$product_name' AND id_name != $id_name AND status_del = 1");
            $num_edit = mysqli_num_rows($sql_editselect);
            if($num_edit == 0){
              $edit_ql = "UPDATE name_product 
                SET product_name='$product_name', price='$price_default', price_center='$price_center',
                count_cord=$count_cord, shipping_cost='$shipping_cost',adder_id=$id_user,status_del=1
                WHERE id_name=$id_name
              ";
              $edit_query = mysqli_query($conn,$edit_ql) or die(mysqli_error($conn));
              if($edit_query){
                $rate_id = $_POST['rate_id'] ?? [];
                echo "<pre>"; 
                  print_r($rate_id);
                echo "</pre>";
                $level_sell = $_POST['level_sell'];
                $rate_vip1 = $_POST['rate_vip1'];
                $rate_storefront2 = $_POST['rate_storefront2'];
                $rate_dealers3 = $_POST['rate_dealers3'];
                $rate_delivery4 = $_POST['rate_delivery4'];

                for($i = 0; $i < count($level_sell); $i++){
                  $islevel_sell = mysqli_real_escape_string($conn,trim($level_sell[$i]));
                  $israte_vip = mysqli_real_escape_string($conn,trim($rate_vip1[$i]));
                  $israte_storefront = mysqli_real_escape_string($conn,trim($rate_storefront2[$i]));
                  $israte_dealers = mysqli_real_escape_string($conn,trim($rate_dealers3[$i]));
                  $israte_delivery = mysqli_real_escape_string($conn,trim($rate_delivery4[$i]));
                  if(isset($rate_id[$i]) && !empty($rate_id[$i])){
                    $is_rateid = mysqli_real_escape_string($conn,trim($rate_id[$i]));
                    $edit_rateql = "UPDATE rate_price 
                      SET product_name='$product_name', id_adder='$id_user', level_sell='$islevel_sell',
                      price_levels_one='$israte_vip', price_customer_frontstore='$israte_storefront',
                      price_customer_deliver='$israte_delivery', price_customer_dealer='$israte_dealers', create_at='$day_add'
                      WHERE id_productname=$id_name AND level_sell='$islevel_sell'
                    ";
                    $ischackstatus[] = "update level:".$islevel_sell;
                    
                  }else{
                    $edit_rateql = "INSERT INTO rate_price (id_productname,product_name,id_adder,level_sell,price_levels_one,price_customer_frontstore,price_customer_deliver,price_customer_dealer,create_at)
                      VALUES ('$id_name','$product_name','$id_user','$islevel_sell','$israte_vip','$israte_storefront','$israte_delivery','$israte_dealers','$day_add')";
                      $ischackstatus[] = "insert level:".$islevel_sell;
                  }
                  
                  $edit_ratequery = mysqli_query($conn,$edit_rateql) or die(mysqli_error($conn));
                  if($edit_ratequery){
                    $checkstatus[] = "update success level:".$i + 1;
                  }
                }
                  
                echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"success\",\"เรียบร้อย\",\"แก้ไขชื่อสินค้าเรียบร้อยแล้ว\",\"../product.php\")
                        </script>";
              }else{
                echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"แก้ไขชื่อสินค้าล้มเหลว\",\"../product.php\")
                        </script>";
              }
            }else{
              echo "<script type=\"text/javascript\">
                        MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"ชื่อสินค้านี้อยูในระบบแล้ว\",\"../product.php\")
                    </script>";
            }
            
          }
      }
      echo "<pre>"; 
        print_r($checkstatus);
      echo "</pre>";
      echo "<br/><hr/><br/>";
      echo "<pre>"; 
            print_r($ischackstatus);
      echo "</pre>";
  ?>
</body>
</html>