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
              
              $uploadPath = '../../db/slip-orders/'.$new_img_name;
              move_uploaded_file($_FILES[$nameImg]["tmp_name"],$uploadPath);
              $newImage = $new_img_name;
              
          }else{
              $newImage = "";
              
          }
          return $newImage;
    }
      
      if($_SERVER['REQUEST_METHOD'] === "POST"){
        
          $issucess = false;
          $countInsert = 0; 
          if($_POST['status_form'] != "PayInstallments"){
            $order_name = $_POST['order_name'];
            $lot_number = $_POST['lot_number'];
            $totalcost_order = $_POST['totalcost_order'];
            $date_time_order = $_POST['date_time_order'];
            $count_order = count($_POST['product_name']);

            $totalprice_sell = $_POST['totalprice_sell'];

            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $count_product = $_POST['count_product'];
            $price_center = $_POST['price_center'];
            $price_product = $_POST['price_product'];
            $count_cord = $_POST['count_cord'];
            $shipping_cost = $_POST['shipping_cost'];
            $expenses = $_POST['expenses'];
          }
          if($_POST['status_form'] == "create"){


              $insertOrder = "INSERT INTO order_box (order_name,lot_numbers,slip_order,totalcost_order,count_order,id_adder,date_time_order,create_at) 
              VALUES ('$order_name','$lot_number','".setImgpath("slipt_order")."','$totalcost_order','$count_order','$id_user','$date_time_order','$day_add')";
              $queryOrder = mysqli_query($conn,$insertOrder) or die(mysqli_error($conn));
              if($queryOrder){
                $id_order = mysqli_insert_id($conn);

                mysqli_query($conn,"INSERT INTO payorder_debtpaid 
                  (orders_id,total_payment,type_pay,slip,adder_id,create_at)
                  VALUES ($id_order,$totalprice_sell,'ยังไม่ใช้','n',$id_user,'$day_add')");
              
              
                  for($i=0; $i< count($product_name); $i++){
                    $is_product_id = mysqli_real_escape_string($conn,trim($product_id[$i]));
                    $is_product_name = mysqli_real_escape_string($conn,trim($product_name[$i]));
                    $is_count_product = mysqli_real_escape_string($conn,trim($count_product[$i]));
                    $is_price_product = mysqli_real_escape_string($conn,trim($price_product[$i]));
                    $is_price_center = mysqli_real_escape_string($conn,trim($price_center[$i]));
                    $is_count_cord = mysqli_real_escape_string($conn,trim($count_cord[$i]));
                    $is_expenses = mysqli_real_escape_string($conn, trim($expenses[$i]));
                    $is_shipping_cost = mysqli_real_escape_string($conn, trim($shipping_cost[$i]));
                    if($is_product_name !== "" || $is_count_product !== "" || $is_price_product !== ""){
                      $insertQl = "INSERT INTO stock_product (product_name,product_count,product_price,price_center,count_cord,shipping_cost,expenses,id_adder,id_order,lot_number,create_at) 
                          VALUES ('$is_product_id','$is_count_product','$is_price_product',$is_price_center,'$is_count_cord','$is_shipping_cost','$is_expenses','$id_user','$id_order','$lot_number','$day_add')
                      ";
                      $queryQl = mysqli_query($conn, $insertQl) or die(mysqli_error($conn));
                      if($queryQl){
                        $countInsert++;
                        $issucess = true;
                      }
                    }
                  }
                  if($issucess){
                       echo "<script type=\"text/javascript\">
                                MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มข้อมูลตามที่เลือกเรียบร้อยแล้ว\",\"../orders.php\")
                            </script>";
                  
                  }else{
                    echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"ไม่มีข้อมูลอะไรให้เพิ่มเลย!\",\"../orders.php\")
                          </script>";
                  }
              }
          }elseif($_POST['status_form'] == "update"){
            $coun_update = 0;
            $count_delete = 0;
            $count_insert = 0;
            $order_id = $_POST['order_id'];
            $default_img = $_POST['default_img'];

            $all_success = true;
            if(isset($_FILES['order_Slip']) && $_FILES['order_Slip']['error'] == 0){
            $update_order = "UPDATE order_box SET order_name='$order_name',lot_numbers='$lot_number', slip_order='".setImgpath("order_Slip")."',
              totalcost_order='$totalcost_order', count_order='$count_order', id_adder='$id_user', date_time_order='$date_time_order' WHERE order_id='$order_id'";
            }else{
              $update_order = "UPDATE order_box SET order_name='$order_name',lot_numbers='$lot_number', slip_order='$default_img',
              totalcost_order='$totalcost_order', count_order='$count_order', id_adder='$id_user', date_time_order='$date_time_order' WHERE order_id='$order_id'";
            }
              $query_update = mysqli_query($conn,$update_order) or die(mysqli_error($conn));
            if($query_update){
              mysqli_query($conn,"INSERT INTO payorder_debtpaid 
                  (orders_id,total_payment,type_pay,slip,adder_id,create_at)
                  VALUES ($order_id,$totalprice_sell,'ยังไม่ใช้','n',$id_user,'$day_add')");
              if(isset($_FILES['order_Slip']) && $_FILES['order_Slip']['error'] == 0){
                unlink(__DIR__  . '/../../db/slip-orders/' . $default_img);
              }
              $old_product = [];
              $res_ponse = mysqli_query($conn, "SELECT product_id, product_name, id_order FROM stock_product WHERE id_order=$order_id") or die(mysqli_error($conn));
              while($row = mysqli_fetch_assoc($res_ponse)){
                $old_product[] = $row['product_id'];
              }

              $form_product_id = $_POST['is_idproduct'];
              $product_name = $_POST['product_name'];
              $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : [];
              $count_product = $_POST['count_product'];
              $price_center = $_POST['price_center'];
              $price_product = $_POST['price_product'];
              $count_cord = $_POST['count_cord'];
              $expenses = $_POST['expenses'];

              $edit_id = [];
              $add_id = [];
              $trash_id = [];
              $newIds = [];

              foreach($form_product_id as $key => $pname_id){
               
                $is_pname = $product_name[$key];
                $is_id_product = $form_product_id[$key];
                $pid = !empty($product_id[$key]) ? $product_id[$key] : null;
                $pcount = $count_product[$key];
                $pprice = $price_product[$key];
                $p_price_center = $price_center[$key];
                $p_count_cord = $count_cord[$key];
                $p_exp = $expenses[$key];
                $p_shipping_cost = $shipping_cost[$key];

                if($pid){
                  $sql_edit = "UPDATE stock_product SET product_name='$is_id_product', product_count='$pcount', product_price='$pprice',price_center='$p_price_center',count_cord='$p_count_cord',shipping_cost='$p_shipping_cost', expenses='$p_exp' ,id_adder='$id_user',lot_number='$lot_number' WHERE product_id=$pid AND id_order=$order_id";
                  $query_edit = mysqli_query($conn,$sql_edit) or die(mysqli_error($conn));
                  if($query_edit){
                    $coun_update++;
                    $edit_id[] = $pid;
                    $newIds[] = $pid;
                  }else{
                    $all_success = false;
                  }
                }else{

                  $sql_insert = "INSERT INTO stock_product(product_name,product_count,product_price,price_center,count_cord,shipping_cost,expenses,id_adder,id_order,lot_number,create_at)
                  VALUES ('$is_id_product','$pcount','$pprice','$p_price_center','$p_count_cord','$p_shipping_cost','$p_exp','$id_user','$order_id','$lot_number','$day_add')";
                  $query_inserts = mysqli_query($conn,$sql_insert) or die(mysqli_error($conn));
                  if($query_inserts){
                    $count_insert++;
                    $is_id = mysqli_insert_id($conn);
                    $add_id[] = $is_id;
                    $newIds[] = $is_id;
                  }else{
                    $all_success = false;
                  }
                }
              }
              $id_to_delete = array_diff($old_product, $newIds);
              if(!empty($id_to_delete)){
                $ids = implode(",", $id_to_delete);
                $query_del = mysqli_query($conn,"DELETE FROM stock_product WHERE product_id IN ($ids)");
                if($query_del){
                  $count_delete++;
                  $trash_id[] = $id_to_delete;
                }else{
                  $all_success = false;
                }
              }
              if($all_success){
                echo "<script type=\"text/javascript\">
                        MySetSweetAlert('success', 'เรียบร้อย', 'อัปเดต: $coun_update, เพิ่ม: $count_insert, ลบ: $count_delete รายการ', '../orders.php')
                    </script>";
              }else{
                echo "<script type=\"text/javascript\">
                    MySetSweetAlert('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถทำงานบางส่วนได้', '../orders.php')
                </script>";
              }
            }else{
              echo "<script type=\"text/javascript\">
                  MySetSweetAlert('error', 'เกิดข้อผิดพลาด', 'ไม่สามารถอัปเดต order ได้', '../orders.php')
              </script>";
              exit;
            }
          }elseif($_POST['status_form'] == "PayInstallments"){

              $is_order_id = $_POST['is_order_id'];
              $count_paydebt = $_POST['count_paydebt'];
              $payment_option = $_POST['payment_option'];

              $remaining = $count_paydebt;
              $check_secc_count_order = [];
              foreach($is_order_id as $key => $val){
                list($idorder, $price, $lot) = explode("|",$val);
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
                
                if($paid != 0){
                  $insertPay = "INSERT INTO payorder_debtpaid 
                  (orders_id,total_payment,type_pay,slip,adder_id,create_at)
                  VALUES ($idorder,$paid,'ยังไม่ใช้','n',$id_user,'$day_add')";
                  $query_pay = mysqli_query($conn,$insertPay) or die(mysqli_error($conn));
                    if($query_pay){
                      $check_secc_count_order[] = [
                        'lot'=>$lot,
                        'status'=>'บันทึกเรียบร้อย',
                        'price' => $price,
                        'pay' => $paid,
                        'balance' => $price - $paid,
                      ];
                    }else{
                      $check_secc_count_order[] = [
                        'lot'=>$lot,
                        'status'=>'error',
                        'price' => $price,
                        'pay' => $paid,
                        'balance' => $price - $paid,
                      ];
                    }
                }else{
                    $check_secc_count_order[] = [
                    'lot'=>$lot,
                    'status'=>'ไม่บันทึก ค่าใช้จ่ายไม่พอ',
                    'price' => $price,
                    'pay' => $paid,
                    'balance' => $price - $paid,
                  ];
                }

              }
                              $tableHtml = '
                  <table border="1" width="100%" style="border-collapse:collapse;text-align:center">
                    <thead>
                      <tr style="background:#f1f1f1">
                        <th>Lot</th>
                        <th>ยอดทั้งหมด</th>
                        <th>ยอดที่จ่าย</th>
                        <th>คงเหลือ</th>
                        <th>สถานะ</th>
                      </tr>
                    </thead>
                    <tbody>
                  ';

                  foreach ($check_secc_count_order as $row) {

                      // $statusText = match ($row['status']) {
                      //     'success' => '✅ success',
                      //     'error' => '❌ error',
                      //     default => '⚠️ not insert'
                      // };

                      $tableHtml .= "
                      <tr>
                        <td>{$row['lot']}</td>
                        <td>".number_format($row['price'],2)."</td>
                        <td>".number_format($row['pay'],2)."</td>
                        <td>".number_format($row['balance'],2)."</td>
                        <td>{$row['status']}</td>
                      </tr>";
                  }

                  $tableHtml .= '
                    </tbody>
                  </table>';

                  echo "
                    <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'รายละเอียดการชำระเงิน',
                        width: 700,
                        html: ".json_encode($tableHtml).",
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        window.location.href = '../orders.php';
                    });
                    </script>
                    ";
          }
        
      }
    ?>
</body>
</html>