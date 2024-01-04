<?php

require 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $query = "SELECT * FROM cart WHERE name = ? AND user_id = ?";
   $prep_state = $conn->prepare($query);
   $prep_state->bind_param("si", $product_name, $user_id);
   $prep_state->execute();

   $check_cart_numbers = $prep_state->get_result();

   // $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'produk sudah ditambahkan!';
   }else{

      $user_id = htmlspecialchars($user_Id, ENT_QUOTES, "UTF-8");
      $product_name = htmlspecialchars($product_name, ENT_QUOTES, 'UTF-8'); 
      $product_price = htmlspecialchars($product_price, ENT_QUOTES, 'UTF-8');
      $product_quantity = htmlspecialchars($product_quantity, ENT_QUOTES, 'UTF-8');
      $product_Image = htmlspecialchars($product_Image, ENT_QUOTES, 'UTF-8');


      $insert_cart = mysqli_prepare($conn, "INSERT INTO 'cart' (user_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?)");
      mysqli_stmt_bind_param($insert_cart, "issis", $user_id, $product_name, $product_price, $product_quantity, $product_image);
      mysqli_stmt_execute($insert_cart);
      

   }
   if(mysqli_stmt_affected_rows($insert_cart) > 0){
      $message[] = 'Produk berhasil ditambahkan!';
   } else{
      $message[] = 'Gagal menambahkan produk!';
   }
   mysqli_stmt_close($insert_cart);
   $message[] = 'Produk berhasil ditambahkan!';

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php require 'header.php'; ?>

<section class="home">

   <div class="content">
      <h3>Toko Buku NyamNyam AOL SecProg</h3>
      <p>ga tau mau nulis apa tigor ga ngasih ide buat nulis apa jadi kesimpulannya tigor yg salah</p>
   </div>

</section>

<section class="products">

   <h1 class="title">Produk Terbaru</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>

     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
      <div class="name"><?php echo $fetch_products['name']; ?></div>
      <div class="price">Rp. <?php echo $fetch_products['price']; ?>/-</div>
      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <!-- change 1 -->
      <!-- change 5 -->
      <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
      <input type="submit" value="Tambahkan ke keranjang" name="add to cart" class="btn">
     </form>

      <?php
         }
      }else{
         echo '<p class="empty">Belum ada produk yang terunggah!</p>';
      }
      $conn->close();
      ?>
   </div>

   <div class="load-more" style="margin-top: 2rem; text-align:center">
      <a href="shop.php" class="option-btn">Ke Shop</a>
   </div>

</section>



<?php require 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>