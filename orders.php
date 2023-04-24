<?php

include 'conn.php';

session_start();

$user_id = $_SESSION['Client_Id'];

if(!isset($user_id)){
   header('location:login.php');
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

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>Your Orders</h3>
   <p> <a href="home.php">Home</a> / Orders </p>
</div>

<section class="placed-orders">

   <h1 class="title">Placed Orders</h1>

   <div class="box-container">

      <?php
         $idcom=connexpdo('bookstore','myparam');
         $query = "CREATE OR REPLACE VIEW MyClient AS SELECT * FROM client WHERE User_Id=:user_id";
         $result = $idcom->prepare($query);
         $result->bindValue(':user_id',$user_id,PDO::PARAM_INT);
         $result->execute();
         $query1 = $idcom->prepare("SELECT * FROM MyClient");
         $query1->execute();
         $nbre=$query1->rowCount();
         if($nbre>0)
               foreach($idcom->query("SELECT * FROM `order`INNER JOIN `MyClient` ON order.Client_Id = MyClient.Client_Id ") as $fetch_orders){?>
               
      <div class="box">
         <p> placed on : <span><?php echo $fetch_orders['Order_Date']; ?></span> </p>
         <p> name : <span><?php echo $fetch_orders['Name']; ?></span> </p>
         <p> number : <span><?php echo $fetch_orders['Phone_Num']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['Email_Client']; ?></span> </p>
         <p> address : <span><?php echo $fetch_orders['Adress']; ?></span> </p>
         <p> city : <span><?php echo $fetch_orders['City']; ?></span> </p>
         <p> state : <span><?php echo $fetch_orders['State']; ?></span> </p>
         <p> payment method : <span><?php echo $fetch_orders['Pay_Method']; ?></span> </p>
         <p> total price : <span>$<?php echo $fetch_orders['Total_Price']; ?>/-</span> </p>
         <p> payment status : <span style="color:<?php if($fetch_orders['Pay_Status'] == 'pending'){ echo 'red'; }else{ echo 'green'; } ?>;"><?php echo $fetch_orders['Pay_Status']; ?></span> </p>
         </div>
      <?php
       }
      else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>