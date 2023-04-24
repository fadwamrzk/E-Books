<?php

include 'conn.php';

session_start();

$user_id = $_SESSION['Client_Id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['order_btn'])){

   $name =$_POST['name'];
   $number = $_POST['number'];
   $email = $_POST['email'];
   $method =$_POST['method'];
   $address =$_POST['adress'];
   $city=$_POST['city'];
   $state=$_POST['state'];
   $placed_on = date('Y-m-d');

   $cart_total = 0;
   $cart_products[] = '';
   
   $idcom=connexpdo('bookstore','myparam');
   $cart_query = "SELECT * FROM `cart` WHERE User_Id =:user_id";
   $cart_result =$idcom->prepare($cart_query);
   $cart_result->bindValue(':user_id', $user_id, PDO::PARAM_INT);
   $cart_result->execute();
   $nbre = $cart_result->rowCount();
   if($nbre> 0){
      foreach($idcom->query("SELECT * FROM `cart` WHERE User_Id='$user_id'") as $cart_item){
         $cart_products[] = $cart_item['Title_B'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }
   $idcom=connexpdo('bookstore','myparam');
   $total_book = implode(', ',$cart_products);
   $order_query = "INSERT INTO `client` ( User_Id,Name , Adress , City , State , Email_Client,Phone_Num) VALUES (:id,:Name ,:Adress ,:City ,:State ,:Email_Client ,:Phone_Num )";
   $order_result = $idcom->prepare($order_query);
   $order_result->bindValue('id',$user_id, PDO::PARAM_INT);
   $order_result->bindValue(':Name', $name, PDO::PARAM_STR);
   $order_result->bindValue(':Adress',$address, PDO::PARAM_STR);
   $order_result->bindValue(':City', $city, PDO::PARAM_STR);
   $order_result->bindValue(':State', $state, PDO::PARAM_STR);
   $order_result->bindValue(':Email_Client',$email,PDO::PARAM_STR);
   $order_result->bindValue(':Phone_Num', $number, PDO::PARAM_STR);
   $order_result->execute();
   $id=$idcom->lastInsertId();
   $idcom=connexpdo('bookstore','myparam');
   $client_query = "INSERT INTO `order`(Client_Id,Order_Date,Total_Price,Pay_Method,Total_Book) VALUES (:client_id,:order_date,:total_price,:Pay_method,:Total_Book)";
   $client_result = $idcom->prepare($client_query);
   $client_result->bindValue(":client_id", $id,PDO::PARAM_INT);
   $client_result->bindValue(":order_date",$placed_on,PDO::PARAM_STR);
   $client_result->bindValue(":total_price",$cart_total, PDO::PARAM_INT);
   $client_result->bindValue(":Pay_method",$method, PDO::PARAM_STR);
   $client_result->bindValue(':Total_Book', $total_book, PDO::PARAM_INT);
   $client_result->execute();
   $message[] = 'order placed successfully!';
   $idcom=connexpdo('bookstore','myparam');
   $delete_result=$idcom->prepare("DELETE FROM `cart` WHERE User_Id =:user_id");
   $delete_result->bindValue(":user_id",$user_id,PDO::PARAM_INT);
   $delete_result->execute();

   
      }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="home.php">home</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $idcom=connexpdo('bookstore','myparam');
      $select_result=$idcom->prepare("SELECT * FROM `cart` WHERE User_Id = :user_id");
      $select_result->bindValue("user_id",$user_id, PDO::PARAM_INT);
      $select_result->execute();
     $nbre = $select_result->rowCount();
      if($nbre > 0){
         foreach($idcom->query("SELECT * FROM `cart` WHERE User_Id = '$user_id'") as $fetch_cart){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['Title_B']; ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   <div class="grand-total"> grand total : <span>$<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>place your order</h3>
      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" required placeholder="enter your name">
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" required placeholder="enter your phone number">
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" required placeholder="enter your email">
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            <select name="method">
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
            </select>
         </div>
         <div class="inputBox">
            <span>address :</span>
            <input type="text" name="adress" required placeholder="e.g. adress name">
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" required placeholder="e.g. Marsa">
         </div>
         <div class="inputBox">
            <span>state :</span>
            <input type="text" name="state" required placeholder="e.g. Nabeul">
         </div>
      </div>
      <input type="submit" value="order now" class="btn" name="order_btn">
   </form>

</section>









<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>