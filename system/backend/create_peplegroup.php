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
  date_default_timezone_set("Asia/Bangkok");
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

  $id_user = $_SESSION['users_data']['id'];
  $day_add = date('Y-m-d H:i:s');

  if($_SERVER['REQUEST_METHOD'] === "POST"){
    $peplegroup_names = $_POST['peplegroup_names'];
    $phone_group = $_POST['phone_group'];
    $status = $_POST['status'];
    if(!$_POST['id_peplegroup']){
      echo "insert";
      $sql_get = mysqli_query($conn,"SELECT id_peplegroup,name_peplegroup FROM peple_groups WHERE name_peplegroup='$peplegroup_names' AND status_del=1");
      $num_row = mysqli_num_rows($sql_get);
      if($num_row == 0){
        $sql_insert = "INSERT INTO peple_groups(name_peplegroup,phone_group,status_group,id_adder,status_del,create_at)
        VALUES('$peplegroup_names','$phone_group','$status','$id_user',1,'$day_add')";
        $query_ins = mysqli_query($conn,$sql_insert) or die(mysqli_error($conn));
        if($query_ins){
                    echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"success\",\"เรียบร้อย\",\"เพิ่มชื่อสมาชิกเรียบร้อยแล้ว\",\"../peplegroup.php\")
                        </script>";
                  }else{
                     echo "<script type=\"text/javascript\">
                            MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"เพิ่มชื่อสมาชิกล้มเหลว\",\"../peplegroup.php\")
                        </script>";
                  }
      }else{
        echo "<script type=\"text/javascript\">
                  MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"มีชื่อสมาชิกนี้อยูในระบบแล้ว\",\"../peplegroup.php\")
              </script>";
      }
    }else{
      $is_idpeplegroup = $_POST['id_peplegroup'];
      echo "update";
      $sql_getupdate = mysqli_query($conn,"SELECT id_peplegroup,name_peplegroup FROM peple_groups WHERE name_peplegroup='$peplegroup_names' AND id_peplegroup != '$is_idpeplegroup' AND status_del = 1");
      $num_rows = mysqli_num_rows($sql_getupdate);
      if($num_rows == 0){
        $query_update = "UPDATE peple_groups SET name_peplegroup='$peplegroup_names', phone_group='$phone_group', status_group='$status',id_adder='$id_user',status_del=1 WHERE id_peplegroup = $is_idpeplegroup";
        $is_queryedit = mysqli_query($conn,$query_update) or die(mysqli_error($conn));
          if($is_queryedit){
              echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"success\",\"เรียบร้อย\",\"แก้ไขชื่อสมาชิกเรียบร้อยแล้ว\",\"../peplegroup.php\")
                  </script>";
          }else{
              echo "<script type=\"text/javascript\">
                      MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"แก้ไขชื่อสมาชิกล้มเหลว\",\"../peplegroup.php\")
                    </script>";
          }
      }else{
        echo "<script type=\"text/javascript\">
                  MySetSweetAlert(\"warning\",\"ล้มเหลว!\",\"มีชื่อสมาชิกนี้อยูในระบบแล้ว\",\"../peplegroup.php\")
              </script>";
      }

    }
  }

?>

</body>
</html>