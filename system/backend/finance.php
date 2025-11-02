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
          
          $uploadPath = '../../db/slip-finance/'.$new_img_name;
          move_uploaded_file($_FILES[$nameImg]["tmp_name"],$uploadPath);
          $newImage = $new_img_name;
          
      }else{
          $newImage = '';
          
      }
      return $newImage;
  }

  if($_SERVER['REQUEST_METHOD'] === "POST"){
    if($_POST['status_form'] === "capital"){
      $capital_balance = floatval($_POST['capital_balance']);
      $count_capital = $_POST['count_capital'];
      $date_time_capital = $_POST['date_time_capital'];
        if($_POST['capital_id']){
          $capital_id = $_POST['capital_id'];
          $img_name = $_POST['capital_img'] ?? "";

          if(isset($_FILES['capital_slip']) && $_FILES['capital_slip']['error'] == 0){
            $sql_edit = "UPDATE capital SET capital_balance='$capital_balance', count_capital='$count_capital', slip_capital='".setImgpath("capital_slip")."', date_time_ad='$date_time_capital',adder_id='$id_user' WHERE capital_id='$capital_id'";
          }else{
            $sql_edit = "UPDATE capital SET capital_balance='$capital_balance', count_capital='$count_capital', slip_capital='$img_name', date_time_ad='$date_time_capital',adder_id='$id_user' WHERE capital_id='$capital_id'";
          }
          $query_edit = mysqli_query($conn,$sql_edit) or die(mysqli_error($conn));
           if($query_edit){
            echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มทุนเรียบร้อยแล้ว\",\"../finance.php\")
                  </script>";
          }else{
             echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"เพิ่มทุนล้มเหลว\",\"../finance.php\")
                  </script>";
          }


        }else{
          $sql = "INSERT INTO capital(capital_balance,count_capital,slip_capital,date_time_ad,adder_id,create_at)
            VALUES('$capital_balance','$count_capital','".setImgpath("capital_slip")."','$date_time_capital','$id_user','$day_add')
          ";
          $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
          if($query){
            echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มทุนเรียบร้อยแล้ว\",\"../finance.php\")
                  </script>";
          }else{
             echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"เพิ่มทุนล้มเหลว\",\"../finance.php\")
                  </script>";
          }
        }
        
        
    }elseif($_POST['status_form'] === "withdraw"){
      $withdraw_balance = floatval($_POST['withdraw_balance']);
      $count_withdraw = $_POST['count_withdraw'];
      $date_time_withdraw = $_POST['date_time_withdraw'];
      $reason = $_POST['reason'];
      if($_POST['withdraw_id']){
        $withdraw_id = $_POST['withdraw_id'];
        $withdraw_img = $_POST['withdraw_img'] ?? "";
        if(isset($_FILES['withdraw_slip']) && $_FILES['withdraw_slip']['error'] == 0){
          $sql_edit = "UPDATE withdraw SET withdraw_balance='$withdraw_balance',count_withdraw='$count_withdraw', slip_withdraw='".setImgpath("withdraw_slip")."', date_withdrow='$date_time_withdraw',id_adder='$id_user' WHERE withdraw_id='$withdraw_id'";
        }else{
          $sql_edit = "UPDATE withdraw SET withdraw_balance='$withdraw_balance', count_withdraw='$count_withdraw', slip_withdraw='$withdraw_img', date_withdrow='$date_time_withdraw',id_adder='$id_user' WHERE withdraw_id='$withdraw_id'";
        }
        $query_edit = mysqli_query($conn,$sql_edit) or die(mysqli_error($conn));
        if($query_edit){
            echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มเบิกถอนเรียบร้อยแล้ว\",\"../finance.php\")
                  </script>";
          }else{
             echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"เพิ่มเบิกถอนล้มเหลว\",\"../finance.php\")
                  </script>";
          }
      }else{
        $sql = "INSERT INTO withdraw(withdraw_balance,count_withdraw,slip_withdraw,date_withdrow,reason,id_adder,create_at)
          VALUES('$withdraw_balance','$count_withdraw','".setImgpath("withdraw_slip")."','$date_time_withdraw','$reason','$id_user','$day_add')
        ";
        $query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
          if($query){
            echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มเบิกถอนเรียบร้อยแล้ว\",\"../finance.php\")
                  </script>";
          }else{
             echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"เพิ่มเบิกถอนล้มเหลว\",\"../finance.php\")
                  </script>";
          }
        
      }
    }
  }
?>
</body>
</html>