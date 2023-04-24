<?php

include 'conn.php';

session_start();

$admin_id = $_SESSION['Admin_Id'];

if(!isset($admin_id)){
   header('location:login.php');
}
;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin panel</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>
<!-- admin dashboard section starts  -->

<section class="dashboard">

   <h1 class="title">dashboard</h1>

   <div class="box-container">

      <div class="box">
         <?php
            $total_pendings = 0;
            $idcom=connexpdo('bookstore','myparam');
            $request1 = "SELECT Total_Price FROM `order` WHERE Pay_Status= 'pending'";
            $select_pending = $idcom->prepare($request1);
            $select_pending->execute();
            $nbart1=$select_pending->rowCount();
            if($nbart1> 0){
               while($fetch_pendings =$select_pending->fetch(PDO::FETCH_ASSOC)){
                  $total_price = $fetch_pendings['Total_Price'];
                  $total_pendings += $total_price;
               };
            };
         ?>
         <h3>DT<?php echo $total_pendings; ?>/-</h3>
         <p>total pendings</p>
      </div>
      <div class="box">
         <?php
           $total_completed = 0;
           $idcom=connexpdo('bookstore','myparam');
           $request2 = "SELECT Total_Price FROM `order` WHERE Pay_Status = 'completed'";
           $select_completed=$idcom->prepare($request2);
           $select_completed->execute();
           $nbart2=$select_completed->rowCount();
           if($nbart2> 0){
               while($fetch_completed =$select_completed->fetch(PDO::FETCH_ASSOC)){
                  $total_price = $fetch_completed['Total_Price'];
                  $total_completed += $total_price;
               };
            };
         ?>
         <h3>$<?php echo $total_completed; ?>/-</h3>
         <p>completed payments</p>
      </div>
         <div class="box">
         <?php
         $request3 = "SELECT * FROM `order`";
             $idcom=connexpdo('bookstore','myparam');
             $select_orders = $idcom->prepare($request3);
             $select_orders->execute();
             $nbart3=$select_orders->rowCount();
         ?>
         <h3><?php echo $nbart3; ?></h3>
         <p>order placed</p>
      </div>
      
      <div class="box">
         <?php
            $request4 = "SELECT * FROM `book`";
            $idcom=connexpdo('bookstore','myparam');
            $select_products = $idcom->prepare($request4);
            $select_products->execute();
            $nbart4=$select_products->rowCount();
         ?>
         <h3><?php echo $nbart4; ?></h3>
         <p>books added</p>
      </div>
      <div class="box">
         <?php
            $request5 = "SELECT * FROM `user` WHERE user_type = 'Client'";
            $idcom=connexpdo('bookstore','myparam');
            $select_clients = $idcom->prepare($request5);
            $select_clients->execute();
            $nbart5=$select_clients->rowCount();
         ?>
         <h3><?php echo $nbart5; ?></h3>
         <p>client accounts</p>
      </div>
      <div class="box">
         <?php 
       $request6 = "SELECT * FROM `user` WHERE user_type = 'Admin'";
       $idcom=connexpdo('bookstore','myparam');
       $select_admins = $idcom->prepare($request6);
       $select_admins->execute();
       $nbart6=$select_admins->rowCount();
                                              ?>
       <h3><?php echo $nbart6; ?></h3>
      <p>admin accounts</p>
      </div>
      <div class="box">
         <?php
         $request7 = "SELECT * FROM `user`"; 
         $idcom=connexpdo('bookstore','myparam');
         $select_account=$idcom->prepare($request7);
         $select_account->execute();
         $nbart7=$select_account->rowCount();
         ?>
         <h3><?php echo $nbart7; ?></h3>
         <p>total accounts</p>
      </div>
      
      <div class="box">
         <?php
            $request8 = "SELECT * FROM `message_contact`";
            $idcom=connexpdo('bookstore','myparam');
            $select_messages = $idcom->prepare($request8);
            $select_messages->execute();
            $nbart8=$select_messages->rowCount();
         ?>
         <h3><?php echo $nbart8; ?></h3>
         <p>new messages</p>
      </div>
   </div>   


</section>

<!-- admin dashboard section ends -->









<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>