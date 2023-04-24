<?php

include 'conn.php';

session_start();

if(!isset($_SESSION['Client_Id'])) {
   $user_id = '';}
else 
{ 
   $user_id=$_SESSION['Client_Id'];
}

if(empty($user_id)&& isset($_POST['add_to_cart'])){
   header('location:login.php');
};
$idcom=connexpdo('bookstore','myparam');
if(isset($_POST['add_to_cart'])){

   $product_title = $_POST['product_title'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];
   $check_cart_numbers = $idcom->prepare("SELECT * FROM `cart`WHERE Title_B=title AND User_Id=:id ");
   $check_cart_numbers->bindValue('title', $product_title, PDO::PARAM_STR);
   $check_cart_numbers->bindValue(':id', $id_user, PDO::PARAM_INT);
    $check_cart_numbers->execute();
   $n=$check_cart_numbers->rowCount();


   if($n>0){
      $message[] = 'already added to cart!';
   }else{
    $request1 = "INSERT INTO `cart`(User_Id,Title_B,price, quantity, Image_B) VALUES(:user_id,:product_title,:product_price,:product_quantity,:product_image)";
    $result1 = $idcom->prepare($request1);
    $result1->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $result1->bindValue(':product_title', $product_title,PDO::PARAM_STR);
    $result1->bindValue(':product_price', $product_price,PDO::PARAM_INT);
    $result1->bindValue(':product_quantity',$product_quantity,PDO::PARAM_INT);
    $result1->bindValue(':product_image',$product_image,PDO::PARAM_STR);
    $result1->execute();
    $message[] = 'product added to cart'; };

};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>search page</h3>
   <p> <a href="home.php">home</a> / search </p>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="search products..." class="box">
      <input type="submit" name="submit" value="search" class="btn">
   </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">
   <?php
      if(isset($_POST['submit'])){
         $search_book = $_POST['search'];
         $selected_books ="SELECT * FROM `book` WHERE Title LIKE '%{$search_book}%' ";
         $result1 = $idcom->query($selected_books);
         $nbre =$result1->rowCount();
         if($nbre > 0){
         foreach($idcom->query("SELECT * FROM  `book` WHERE Title LIKE '%{$search_book}%'") as $fetch_product ){
   ?>
   <form action="" method="post" class="box">
      <img src="uploaded_img/<?php echo $fetch_product['Image']; ?>" alt="" class="image">
      <div class="name"><?php echo $fetch_product['Title']; ?></div>
      <div class="price">$<?php echo $fetch_product['Price']; ?>/-</div>
      <input type="number"  class="qty" name="product_quantity" min="1" value="1">
      <input type="hidden" name="product_title" value="<?php echo $fetch_product['Title']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_product['Price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_product['Image']; ?>">
      <input type="submit" class="btn" value="add to cart" name="add_to_cart">
   </form>
   <?php
            }
         }else{
            echo '<p class="empty">no result found!</p>';
         }
      }else{
         echo '<p class="empty">search something!</p>';
      }
   ?>
   </div>
  

</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>