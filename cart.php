<?php

require 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['cart_id'];
   $cart_quantity = $_POST['cart_quantity'];

   $query = "UPDATE cart SET quantity = ? WHERE id = ?";
   $prep_state = $conn->prepare($query);
   $prep_state->bind_param("ii", $cart_quantity, $cart_id);
   $prep_state->execute();
   
   // mysqli_query($conn, "UPDATE `cart` SET quantity = '$cart_quantity' WHERE id = '$cart_id'") or die('query failed');
   $message[] = 'Jumlah keranjang belanja diperbarui!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];

   $query = "DELETE FROM cart WHERE id = ?";
   $prep_state = $conn->prepare($query);
   $prep_state->bind_param("i", $delete_id);
   $prep_state->execute();

   // mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$delete_id'") or die('query failed');
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
   mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Keranjang</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php require 'header.php'; ?>

<div class="heading">
   <h3>Keranjang Belanja</h3>
</div>

<section class="shopping-cart">

   <h1 class="title">Produk Ditambahkan</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){   
               // <!--change 6-->
               $cart_token = bin2hex(random_bytes(16));
      ?>
      <div class="box">
      // <!--change 7-->
         
      <a href="cart.php?delete=<?php echo $cart_token['id']; ?>" class="fas fa-times" onclick="return confirm('hapus dari keranjang ?');"></a>
         <img src="uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['name']; ?></div>
         <div class="price">Rp. <?php echo $fetch_cart['price']; ?>/-</div>
         <form action="" method="post">
            <input type="hidden" name="cart_token" value="<?php echo $cart_token; ?>">
            <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
            <input type="submit" name="update_cart" value="update" class="option-btn">
         </form>
         <div class="sub-total"> sub total : <span>Rp. <?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">Keranjangmu kosong!</p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">Hapus Semua</a>
   </div>

   <div class="cart-total">
      <p>Total : <span>Rp. <?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">Lanjutkan shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">Pergi ke checkout</a>
      </div>
   </div>

</section>








<?php require 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>