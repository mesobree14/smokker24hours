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
      if($_SERVER['REQUEST_METHOD'] === "POST"){
          $product_name = $_POST['product_name'];
          $price_default = $_POST['price_default'];
          $price_center = $_POST['price_center'];
          $count_cord = $_POST['count_cord'];
          $shipping_cost = $_POST['shipping_cost'];
          if(!$_POST['id_name']){
              echo "insert:";
              echo $_POST['id_name'];
              $sql_select = mysqli_query($conn,"SELECT product_name FROM name_product WHERE product_name='$product_name' AND status_del=1");
              $num = mysqli_num_rows($sql_select);
                if($num == 0){
                  $sql = "INSERT INTO name_product(product_name,price,price_center,count_cord,shipping_cost,adder_id,status_del,create_at)
                  VALUES('$product_name','$price_default','$price_center','$count_cord','$shipping_cost','$id_user',1,'$day_add')";
                  $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
                  if($query){
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
  ?>
</body>
</html>