<?php

require 'config.php';
require 'security.php';
session_start();


if(isset($_POST['submit'])){
   // login_attempt_rate_limit($maxAttempts, $timeFrameInSeconds, $timeoutDuration);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $pass = mysqli_real_escape_string($conn, $_POST['password']);

   $query = "SELECT * FROM users WHERE email = ?";
   $prep_state = $conn->prepare($query);
   $prep_state->bind_param("s", $email);
   $prep_state->execute();
   
   $select_users = $prep_state->get_result();
   $row = mysqli_fetch_assoc($select_users);
   
   if(mysqli_num_rows($select_users) === 1 && password_verify($pass, $row['password'])){

      // $row = mysqli_fetch_assoc($select_users);
      
      if($row['user_type'] == 'admin'){
         
         $_SESSION['admin_name'] = $row['name'];
         $_SESSION['admin_email'] = $row['email'];
         $_SESSION['admin_id'] = $row['id'];
         header('location:admin_page.php');
         
      }elseif($row['user_type'] == 'user'){
         
         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');
      }

   }else{
      $message[] = 'email atau password salah !';
   }
   $conn->close();

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<div class="form-container">

   <form action="" method="post">
      <h3>LOG IN</h3>
      <input type="email" name="email" placeholder="Email address" required class="box">
      <input type="password" name="password" placeholder="Password" required class="box">
      <input type="submit" name="submit" value="login" class="btn" id="loginButton">
      <p>Belum punya akun? <a href="register.php">Daftar</a></p>
   </form>

</div>

 
</body>
</html>

