<?php

require 'config.php';

if(isset($_POST['submit'])){

   $name = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['name']));
   $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['email']));
   $pass = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['password']));
   $cpass = htmlspecialchars(mysqli_real_escape_string($conn, $_POST['cpassword']));
   $user_type = htmlspecialchars($_POST['user_type']);

   $query = "SELECT * FROM users WHERE email = ?";
   $prep_state = $conn->prepare($query);
   $prep_state->bind_param("s", $email);
   $prep_state->execute();
   $select_users = $prep_state->get_result();
   
   // validasi input dari selection user type, biar tidak ada injection
   if($user_type !== "user" && $user_type !== "admin"){
      $message[] = 'Pendaftaran gagal!';
      header('Location:register.php');
   }
   
   if(mysqli_num_rows($select_users) === 1){
      $message[] = 'Email sudah digunakan!';
   }else{
      if($pass != $cpass){
         $message[] = 'Konfirmasi kata sandi tidak cocok!';
      }else{
         //filter var buat ngecek email beneran ato engga, kalo somehow dia ngelewatin regex
         if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            die("Unfortunately, doing that doesn't work here :)");
            //bingung mau diapain lagi, tambahin gini dulu aja sementara
         }
         $query = "INSERT INTO users(name, email, password, user_type) VALUES(?, ?, ?, ?)";
         $pass = password_hash($pass, PASSWORD_DEFAULT);
         $prep_state = $conn->prepare($query);
         $prep_state->bind_param("ssss", $name, $email, $pass, $user_type);
         $prep_state->execute();
         $conn->close();
         
         $message[] = 'Pendaftaran berhasil!';
         header('location:login.php');
      }
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
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

   
<div class="form-container">

   <form name="form" onsubmit="return validateForm() && validateFormPass()" method="post">
      <h3>Registrasi Sekarang</h3>
      <input type="text" name="name" placeholder="masukkan nama anda" required class="box">
      <input type="email" name="email" id="email" placeholder="masukan email anda" required class="box">
      <input type="password" name="password" id="password" placeholder="masukkan password" required class="box">
      <input type="password" name="cpassword" placeholder="konfirmasi password" required class="box">
      <select name="user_type" class="box">
         <option value="user">user</option>
         <option value="admin">admin</option>
      </select>
      <input type="submit" name="submit" value="register now" class="btn">
      <p>Sudah memiliki akun? <a href="login.php">Log In</a></p>
   </form>
   <script src="js/form_validation.js"></script>
</div>

</body>
</html>