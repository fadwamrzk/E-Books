<?php

include 'conn.php';

session_start();

if(!isset($_SESSION['Client_Id'])) {
   $user_id = '';}
else 
{ 
   $user_id=$_SESSION['Client_Id'];
}
if (isset($_POST['add_to_cart'])) {

    $product_title = $_POST['product_title'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];
    $idcom = connexpdo('bookstore', 'myparam');
    $check_cart_numbers = $idcom->prepare("SELECT * FROM `cart` WHERE Title_B=:product_title AND User_Id =:user_id");
    $check_cart_numbers->bindValue(":product_title", $product_title, PDO::PARAM_STR);
    $check_cart_numbers->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $check_cart_numbers->execute();
    $nbre = $check_cart_numbers->rowCount();


    if ($nbre > 0) {
        $message[] = 'already added to cart!';
    } else {
        $idcom = connexpdo('bookstore', 'myparam');
        $request1 = "INSERT INTO `cart`(User_Id,Title_B,price, quantity, Image_B) VALUES(:user_id,:product_title,:product_price,:product_quantity,:product_image)";
        $result1 = $idcom->prepare($request1);
        $result1->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $result1->bindValue(':product_title', $product_title,PDO::PARAM_STR);
        $result1->bindValue(':product_price', $product_price,PDO::PARAM_INT);
        $result1->bindValue(':product_quantity',$product_quantity,PDO::PARAM_INT);
        $result1->bindValue(':product_image',$product_image,PDO::PARAM_STR);
        $result1->execute();
        $message[] = 'product added to cart!';
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Our Shop</h3>
   <p> <a href="home.php">Home</a> / Shop </p>
</div>

<section class="products">

   <h1 class="title">Latest Books</h1>

   <div class="box-container">

      <?php  
        $idcom=connexpdo('bookstore','myparam');
        $selected_books = $idcom->prepare("SELECT * FROM `book` LIMIT 6");
        $selected_books->execute();
        $nbre2 = $selected_books->rowCount();
        if($nbre2> 0){
           foreach($idcom->query("SELECT * FROM `book`") as $fetch_books){
     ?>
     <form action="" method="post" class="box">
      <img class="image" src="uploaded_img/<?php echo $fetch_books['Image']; ?>" alt="">
      <div class="name"><?php echo $fetch_books['Title']; ?></div>
      <div class="price"><?php echo $fetch_books['Price']; ?> DT</div>
      <input type="number" min="1" name="product_quantity" value="1" class="qty">
      <input type="hidden" name="product_title" value="<?php echo $fetch_books['Title']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_books['Price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_books['Image']; ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
     </form>
      <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>