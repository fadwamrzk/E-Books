<?php

include 'conn.php';

session_start();

$admin_id = $_SESSION['Admin_Id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $idcom=connexpdo('bookstore','myparam');
   $request = "DELETE FROM `message_contact` WHERE Contact_Id= :delete_id";
   $result = $idcom->prepare($request);
   $result->bindValue(':delete_id',$delete_id,PDO::PARAM_INT);
   $result->execute();
   header('location:admin_contacts.php');
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>messages</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title"> messages </h1>

   <div class="box-container">
   <?php
      $idcom=connexpdo('bookstore','myparam');
      $result2 = $idcom->prepare("SELECT * FROM `message_contact`");
      $result2->execute();
      $nbart=$result2->rowCount();
      if($nbart > 0)
        { $idcom=connexpdo('bookstore','myparam');
         foreach($idcom->query("SELECT * FROM `message_contact`") as $fetch_message){
      
   ?>
   <div class="box">
      <p> contact id : <span><?php echo $fetch_message['Contact_Id']; ?></span> </p>
      <p> name : <span><?php echo $fetch_message['Name_Cont']; ?></span> </p>
      <p> email : <span><?php echo $fetch_message['Email_Cont']; ?></span> </p>
      <p> number : <span><?php echo $fetch_message['Phone_Cont']; ?></span> </p>
      <p> topic : <span><?php echo $fetch_message['Topic']; ?></span> </p>
      <p> message : <span><?php echo $fetch_message['Message']; ?></span> </p>
      <a href="admin_contacts.php?delete=<?php echo $fetch_message['Contact_Id']; ?>" onclick="return confirm('delete this message?');" class="delete-btn">delete message</a>
   </div>
   <?php
      };
   }else{
      echo '<p class="empty">you have no messages!</p>';
   }
   ?>
   </div>

</section>

