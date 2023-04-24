<?php

include 'conn.php';

session_start();

$user_id = $_SESSION['Client_Id'];

if (!isset($user_id)) {
   header('location:login.php');
}

if(isset($_POST['update_cart'])){
   $cart_id = $_POST['Cart_Id'];
   $cart_quantity = $_POST['quantity'];
   $idcom=connexpdo('bookstore','myparam');
   $request1 = "UPDATE `cart` SET quantity =:quantity WHERE Cart_Iden=:Cart_Id";
    $result1 = $idcom->prepare($request1);
    $result1->bindValue(':quantity', $cart_quantity,PDO::PARAM_INT);
    $result1->bindValue(':Cart_Id', $cart_id, PDO::PARAM_INT);
    $result1->execute();
   $message[] = 'cart quantity updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $idcom=connexpdo('bookstore','myparam');
   $result2 = $idcom->prepare("DELETE FROM `cart` WHERE Cart_Iden=:delete_id");
   $result2->bindValue(':delete_id',$delete_id, PDO::PARAM_INT);
   $result2->execute();
   header('location:cart.php');
}

if(isset($_GET['delete_all'])){
    $idcom=connexpdo('bookstore','myparam');
    $result3 =$idcom->prepare("DELETE FROM `cart` WHERE User_Id=:delete_all");
    $result3->bindValue(':delete_all',$user_id,PDO::PARAM_INT);
    $result3->execute();
   header('location:cart.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>cart</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>shopping cart</h3>
   <p> <a href="home.php">home</a> / cart </p>
</div>

<section class="shopping-cart">

   <h1 class="title"> books added</h1>

   <div class="box-container">
      <?php
         $grand_total = 0;
         $idcom=connexpdo('bookstore','myparam');
         $result4 = $idcom->prepare("SELECT * FROM `cart` WHERE User_Id= :user_id");
         $result4->bindValue(':user_id', $user_id, PDO::PARAM_INT);
         $result4->execute();
         $nbre=$result4->rowCount();

         if($nbre > 0){
            foreach($idcom->query("SELECT * FROM `cart` WHERE User_Id= '$user_id'") as $fetch_cart)
          {   
      ?>
      <div class="box">
         <a href="cart.php?delete=<?php echo $fetch_cart['Cart_Iden']; ?>" class="fas fa-times" onclick="return confirm('delete this from cart?');"></a>
         <img src="uploaded_img/<?php echo $fetch_cart['Image_B']; ?>" alt="">
         <div class="name"><?php echo $fetch_cart['Title_B']; ?></div>
         <div class="price"><?php echo $fetch_cart['price']; ?> DT</div>
         <form action="" method="post">
            <input type="hidden" name="Cart_Id" value="<?php echo $fetch_cart['Cart_Iden']; ?>">
            <input type="number" min="1" name="quantity" value="<?php echo $fetch_cart['quantity']; ?>">
            <input type="submit" name="update_cart" value="update" class="option-btn">
         </form>
         <div class="sub-total"> sub total : <span>$<?php echo $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']); ?>/-</span> </div>
      </div>
      <?php
      $grand_total += $sub_total;
         }
      }else{
         echo '<p class="empty">your cart is empty</p>';
      }
      ?>
   </div>

   <div style="margin-top: 2rem; text-align:center;">
      <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all</a>
   </div>

   <div class="cart-total">
      <p>grand total : <span>$<?php echo $grand_total; ?>/-</span></p>
      <div class="flex">
         <a href="shop.php" class="option-btn">continue shopping</a>
         <a href="checkout.php" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
      </div>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>