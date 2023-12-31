<?php

require 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];

   $query = "UPDATE orders SET payment_status = ? WHERE id = ?";
   $prep_state = $conn->prepare($query);
   $prep_state->bind_param("si", $update_payment, $order_update_id);
   $prep_state->execute();

   // mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'Status pembayaran telah diperbarui!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   $query = "DELETE FROM orders WHERE id = ?";
   $prep_state = $conn->prepare($query);
   $prep_state->bind_param("i", $delete_id);
   $prep_state->execute();
   // $conn->close();
   // mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_orders.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php require 'admin_header.php'; ?>

<section class="orders">

   <h1 class="title">LIST ORDER</h1>

   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> ID User           : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> Tanggal Order     : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> Nama              : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> No. Telp          : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> Email             : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> Alamat            : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> Total Barang      : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> Total Harga       : <span>Rp. <?php echo $fetch_orders['total_price']; ?>/-</span> </p>
         <p> Metode Pembayaran : <span><?php echo $fetch_orders['method']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="Pending">Pending</option>
               <option value="Selesai">Selesai</option>
            </select>
            <input type="submit" value="Perbarui" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('Hapus pengguna ini?');" class="delete-btn">Hapus</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Keranjang anda kosong!</p>';
      }

      $conn->close();
      ?>
   </div>

</section>


<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>