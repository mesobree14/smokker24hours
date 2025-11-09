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

      function setImgpath($nameImg){
        $ext = pathinfo(basename($_FILES[$nameImg]["name"]), PATHINFO_EXTENSION);
          if($ext !=""){
              $new_img_name = "img_".uniqid().".".$ext;
              
              $uploadPath = '../../db/slip-sellorder/'.$new_img_name;
              move_uploaded_file($_FILES[$nameImg]["tmp_name"],$uploadPath);
              $newImage = $new_img_name;
              
          }else{
              $newImage = '';
              
          }
          return $newImage;
      }
      
      if($_SERVER['REQUEST_METHOD'] === "POST"){
                    
          $is_idproductname = $_POST['is_idproductname'];
          $is_products = $_POST['product'];
          $type_level = $_POST['type_level'];
          $is_costommerd = $_POST['costommerds'];
          $tatol_products = $_POST['tatol_product'];
          $resutl_prices = $_POST['resutl_price'];
          $type_custom = $_POST['type_custom'];

          $ordersell_name = $_POST['ordersell_name'];
          $is_totalprice = $_POST['is_totalprice'];
          //$tell_custome = $_POST['tell_custome'];
          $custome_name = $_POST['custome_name'];
          $date_time_sell = $_POST['date_time_sell'];
          $peplegroup_id = $_POST['peplegroup_id'];
          $payment_options = $_POST['payment_option'];
          
          //$sender = $_POST['sender'] ?? "";
          //$tell_sender = $_POST['tell_sender'];
          //$location_send = $_POST['location_send'];

          //$shipping_note = $_POST['shipping_note'] ?? "";
          //$wages = $_POST['wages'] ?? null;
          //if(empty($wages)){
          //  $wages = 0;
          //}else{
          //  $wages = preg_replace('/[^\d.]/','',$wages);
          //  $wages = (float)$wages;
          //}

          
          //$reason = $_POST['reason'] ?? "";

          $count_totalpays = $_POST['count_totalpays'] ?? 0;
          if($count_totalpays === '' || $count_totalpays === null){
            $count_totalpays = 0;
          }
          $count_stuck = $_POST['count_stuck'] ?? 0;

          $chkstatus = [];
 
          $sql_add = "INSERT INTO orders_sell (ordersell_name,is_totalprice,custome_name,date_time_sell,sell_idpeplegroup,slip_ordersell,count_totalpays,count_stuck,adder_id,create_at)
           VALUES ('$ordersell_name','$is_totalprice','$custome_name','$date_time_sell','$peplegroup_id','".setImgpath("sell_slip")."','$count_totalpays','$count_stuck','$id_user','$day_add')";
          $query_add = mysqli_query($conn, $sql_add) or die(mysqli_error($conn));
          if($query_add){
            $id_order_sell = mysqli_insert_id($conn);

            for($s=0; $s < count($payment_options); $s++){
                $payment_option = mysqli_real_escape_string($conn,trim($payment_options[$s]));
                if($payment_option !== ""){
                    $query_payment = mysqli_query($conn,"INSERT INTO sell_typepay (ordersell_id,list_typepay,create_at) VALUES ('$id_order_sell','$payment_option','$day_add')") or die(mysqli_error($conn));
                    if($query_payment){
                        $chkstatus[] = "success";
                    }
                }
            }


            for($i=0; $i < count($is_idproductname); $i++){
              $is_productId = mysqli_real_escape_string($conn,trim($is_idproductname[$i]));
              $is_typelevel = mysqli_real_escape_string($conn,trim($type_level[$i]));
              $formatis_costommerd = number_format((float)$is_costommerd[$i], 2, '.','');
              $is_costomer = mysqli_real_escape_string($conn, trim(number_format((float)$is_costommerd[$i], 2, '.','')));
              $tatol_product = mysqli_real_escape_string($conn, trim($tatol_products[$i]));
              $resutl_price = mysqli_real_escape_string($conn, trim(number_format((float)$resutl_prices[$i], 2, '.','')));
              $istype_custom = mysqli_real_escape_string($conn,trim($type_custom[$i]));
              if($is_productId !== "" || $is_costomer !== "" || $tatol_product !== "" || $resutl_price !== ""){
                //$get_productbuy = mysqli_query($conn,"SELECT COUNT(*) AS totalorder, SUM()")
                //$getcount_productsell = 
                $add_sqls = "INSERT INTO list_productsell (ordersell_id,productname,level_selltype,rate_customertype,type_custom,tatol_product,price_to_pay,create_at)
                VALUES ('$id_order_sell','$is_productId',$is_typelevel,'$is_costomer','$istype_custom','$tatol_product','$resutl_price','$day_add')";
                $query_addsql = mysqli_query($conn,$add_sqls) or die(mysqli_error($conn));
                if($query_addsql){
                  $chkstatus[] = "success";
                }
              }
            }
            echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มข้อมูลตามที่เลือกเรียบร้อยแล้ว\",\"../ordersell.php\")
                        </script>";
          }

        
      }
      
    ?>
</body>
</html>