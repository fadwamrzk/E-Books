<?php

include 'conn.php';

session_start();

$admin_id = $_SESSION['Admin_Id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = (integer)$_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $idcom=connexpdo('bookstore','myparam');
   $request1="UPDATE `order` SET Pay_Status= :update_payment WHERE  Order_Id= :order_update_id";
   $resultat1=$idcom->prepare($request1);
   $resultat1->bindValue(':update_payment',$update_payment,PDO::PARAM_STR);
   $resultat1->bindValue(':order_update_id',$order_update_id,PDO::PARAM_STR);
   $resultat1->execute();
   $message[] = 'payment status has been updated!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $idcom=connexpdo('bookstore','myparam');
   $request2="DELETE FROM `order` WHERE Order_Id=:delete_id";
   $resultat2=$idcom->prepare($request2);
   $resultat2->bindValue(':delete_id',$delete_id,PDO::PARAM_INT);
   $resultat2->execute();
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
   
<?php include 'admin_header.php'; ?>
<BR> <br>
<div class="box-container">
       <section class="search-form">
          <form action="search_order.php" method="post">
           <input type="text" name="search_order" placeholder="enter order id..." class="box">
           <input type="submit" name="submit" value="search order" class="btn">
          </form>
       </section>

</div>      
<BR> <BR>
<div class="box-container">
<section class="orders">

   <h1 class="title">placed orders</h1>
   <div class="box-container">
      <?php
      $idcom=connexpdo('bookstore','myparam');
      $request3 = "SELECT * FROM `order`INNER JOIN `client` ON order.Client_Id = client.Client_Id ";
      $resultat3=$idcom->prepare($request3);
      $resultat3->execute();
      $nbart=$resultat3->rowCount();
      //echo $nbart;



      if($nbart>0){
        
         foreach($idcom->query("SELECT * FROM `order`INNER JOIN `client` ON order.Client_Id = client.Client_Id ") as $fetch_orders){
      ?>
      <div class="box">
         <p> Order Id : <span><?php echo $fetch_orders['Order_Id']; ?></span> </p>
         <p> Client Id : <span><?php echo $fetch_orders['Client_Id']; ?></span> </p>
         <p> placed on : <span><?php echo $fetch_orders['Order_Date']; ?></span> </p>
         <p> name : <span><?php echo $fetch_orders['Name']; ?></span> </p>
         <p> number : <span><?php echo $fetch_orders['Phone_Num']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['Email_Client']; ?></span> </p>
         <p> address : <span><?php echo $fetch_orders['Adress']; ?></span> </p>
         <p> city : <span><?php echo $fetch_orders['City']; ?></span> </p>
         <p> state : <span><?php echo $fetch_orders['State']; ?></span> </p>
         <p> total price : <span>$<?php echo $fetch_orders['Total_Price']; ?>/-</span> </p>
         <p> payment method : <span><?php echo $fetch_orders['Pay_Status']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['Order_Id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $fetch_orders['Pay_Status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_orders.php?delete=<?php echo $fetch_orders['Order_Id']; ?>" onclick="return confirm('delete this order?');" class="delete-btn">delete</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>
   </div>

</section>










<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>