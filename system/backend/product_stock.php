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
      $day_add = date('Y-m-d H:i:s');
      $id_user = $_SESSION['users_data']['id'];
      if($_SERVER['REQUEST_METHOD'] === "POST"){
        if($_POST['status_form'] === "create_rate"){
            $is_idname = $_POST['is_idname'];
            $rate_id = $_POST['rate_id'];
            $product_name = $_POST['product_name'];
            $rate_storefront = $_POST['rate_storefront'];
            $rate_vip = $_POST['rate_vip'];
            $rate_dealers = $_POST['rate_dealers'];
            $rate_delivery = $_POST['rate_delivery'];

            $url_encode = urlencode($_POST['product_name']);
            
            if(!$rate_id){
              $sql_rate = "INSERT INTO rate_price (id_productname,product_name,id_adder,price_levels_one,price_customer_frontstore,price_customer_deliver,price_customer_dealer,create_at)
                  VALUES ('$is_idname','$product_name','$id_user','$rate_vip','$rate_storefront','$rate_delivery','$rate_dealers','$day_add')";
                $query_insert = mysqli_query($conn,$sql_rate) or die(mysqli_error($conn));
                if($query_insert){
                  echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มข้อมูลเรียบร้อยแล้ว\",\"../details/detail_stock.php?id_productname=$is_idname\")
                        </script>";
                }else {
                  echo "<script type=\"text/javascript\">
                        MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"เพิ่มข้อมูลไม่สำเร็จ!\",\"../details/detail_stock.php?id_productname=$is_idname\")
                      </script>";
                }
            }else{
              $update_rate = "UPDATE rate_price 
                SET id_productname='$is_idname',product_name='$product_name',id_adder='$id_user',price_levels_one='$rate_vip', price_customer_frontstore='$rate_storefront', 
                price_customer_deliver='$rate_delivery', price_customer_dealer='$rate_dealers',create_at='$day_add' WHERE rate_id=$rate_id";
              $query_update = mysqli_query($conn,$update_rate) or die(mysqli_error($conn));
              if($query_update){
                echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"success\",\"เรียบร้อย\",\"แก้ไขข้อมูลเรียบร้อยแล้ว\",\"../details/detail_stock.php?id_productname=$is_idname\")
                        </script>";
              }else{
                echo "<script type=\"text/javascript\">
                        MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"แก้ไขข้อมูลไม่สำเร็จ!\",\"../details/detail_stock.php?id_productname=$is_idname\")
                      </script>";
              }
            }
        }
      }
  ?>
</body>
</html>