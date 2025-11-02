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
   const MySetSweetAlertSuccess =(Icons,Titles,Texts,location)=>{
      Swal.fire({
          icon: Icons,
          title: Titles,
          confirmButtonText:"OK",
          html: Texts,
      }).then((result)=>{
           window.location = `${location}`
      })
    }
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
              
              $uploadPath = '../../db/slip-paydebt/'.$new_img_name;
              move_uploaded_file($_FILES[$nameImg]["tmp_name"],$uploadPath);
              $newImage = $new_img_name;
              
          }else{
              $newImage = "";
              
          }
          return $newImage;
    }
      
      if($_SERVER['REQUEST_METHOD'] === "POST"){

          $type_page = $_POST['type_page'];
        
          $serial_number = $_POST['serial_number'];
          $customer_name = $_POST['customer_name'];
          $count_paydebt = $_POST['count_paydebt'];
          $debtpaid_balance = $_POST['debtpaid_balance'];
          $date_add = $_POST['date_add'];
          $orther_text = $_POST['orther_text'];

          $payment_option = $_POST['payment_option'];
          $is_ordersell_id = $_POST['is_ordersell_id'];
          //$priceordersell = $_POST['priceordersell'];
          $count_order = count($is_ordersell_id);
          
          

          $check_payment = [];
          $check_secc_count_order = [];
         
          
          $sql = "INSERT INTO custom_debtpaid(serial_number,name_customer,count_debtpaid,debtpaid_balance,count_order_pay,datetime_pays,text_reason,img_debt,adder_id,create_at) 
            VALUES('$serial_number','$customer_name','$count_paydebt','$debtpaid_balance','$count_order','$date_add','$orther_text','".setImgpath("payoffdebt_slip")."','$id_user','$day_add')";
          
          $query_sql = mysqli_query($conn,$sql) or die(mysqli_error($conn));
         if($query_sql){
            $remaining = $count_paydebt;
            $id_debtpay = mysqli_insert_id($conn);
            foreach($is_ordersell_id as $key => $val){
            list($ids, $price, $text) = explode("|",$val);
              if($remaining <= 0){
                $paid = 0;
              }elseif($remaining >= $price){
                $paid = $price;
                $remaining -= $price;
              }else{
                $paid = $remaining;
                $remaining = 0;
              }
              $staus = ($paid == $price) ? 'ครบถ้วน' : 'จ่ายไม่ครบ';
             
              $sql_inserts = "INSERT INTO order_was_paid(debtpaid_id,ordersell_ids,ordersell_names,priceto_pay,amount_paid,status_pay,create_at)
              VALUES('$id_debtpay','$ids','$text','$price','$paid','$staus','$day_add')";
              $querys = mysqli_query($conn, $sql_inserts);
              if($querys){
                $check_secc_count_order[] = [
                  'ids'=>$ids,
                  'status'=>'success'
                ];
              }else{
                $check_secc_count_order[] = [
                  'ids'=>$ids,
                  'status'=>'error'
                ];
              }
            }

            for($i=0;$i < count($payment_option);$i++){
              $is_payment = mysqli_real_escape_string($conn,trim($payment_option[$i]));
              if($is_payment !== ""){
                $insert_typepay = mysqli_query($conn,"INSERT INTO type_paydebt(debtpay_id,type_pay,create_at) VALUES('$id_debtpay','$is_payment','$day_add')");
                if($insert_typepay){
                  $check_payment[] = "success";
                }else{
                  $check_payment[] = "error";
                }
              }
            }
            if($type_page == "IN"){
               echo "<script type=\"text/javascript\">
                        MySetSweetAlertSuccess('success', 'เรียบร้อย', 'เพิ่มข้อมูลการจ่ายหนี้ของ $customer_name เรียบร้อย <a href=\"../details/PDF/PDF_historydebt.php?id_paydebt=$id_debtpay\" target=\"_blank\" class=\"text-uppercase\"> print pdf</a>', '../details/detail_customer.php?custom_name=".urlencode($customer_name)."')
                    </script>";
            }else{
              echo "<script type=\"text/javascript\">
                        MySetSweetAlertSuccess('success', 'เรียบร้อย', 'เพิ่มข้อมูลการจ่ายหนี้ของก $customer_name เรียบร้อย <a href=\"../details/PDF/PDF_historydebt.php?id_paydebt=$id_debtpay\" target=\"_blank\" class=\"text-uppercase\"> print pdf </a>', '../customer.php')
                    </script>";
            }
          }else{
            if($type_page == "IN"){
              echo "<script type=\"text/javascript\">
                        MySetSweetAlert('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถทำงานบางส่วนได้', '../details/detail_customer.php?custom_name=".urlencode($customer_name)."')
                    </script>";
            }else{
              echo "<script type=\"text/javascript\">
                        MySetSweetAlert('error', 'เรียบร้อย', 'เกิดข้อผิดพลาด', 'ไม่สามารถทำงานบางส่วนได้' '../customer.php?custom_name=".urlencode($customer_name)."')
                    </script>";
            }
          }       
      
         }
    ?>
</body>
</html>