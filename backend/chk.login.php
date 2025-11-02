<?php
    session_name("session_smokker");
  session_start();
    include_once("config.php");
    include_once("../link/link-2.php"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
</head>
<body>
    

<?php
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username' && password='$password'";
     $que = mysqli_query($conn,$sql) or die(mysqli_error());
     $num = mysqli_num_rows($que);
    if(!$num){
        echo "
        <script type=\"text/javascript\">
            Swal.fire({
                icon: 'error',
                title: 'ล้มเหลว',
                text: 'อีเมลหรื่อรหัสผ่านไม่ถูกต้อง โปรดล็อคอินอีกครั้ง',
                showConfirmButton: false,
          }).then(()=>{
              window.location = '../index.php';
          })
        </script>
        ";
    }else{

        $fetch = mysqli_fetch_assoc($que);
        $_SESSION['users_data'] = $fetch;
            echo '
                    <script type="text/javascript">
                        Swal.fire({
                            icon: "success",
                            title: "สวัสดีคุณแอดมิน",
                            text: "เข้าสู่ระบบเรียบร้อย",
                            showConfirmButton: false,
                        }).then(()=>{
                            window.location = "../system/"
                        })
                    </script>
                ';
    }


?>
</body>
</html>