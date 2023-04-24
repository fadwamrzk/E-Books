<?php

include 'conn.php';

session_start();

if(!isset($_SESSION['Client_Id'])) {
   $user_id = '';}
else 
{ 
   $user_id=$_SESSION['Client_Id'];
}

if(isset($_POST['send'])){
    $idcom=connexpdo('bookstore','myparam');
    $name=$_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $topic= $_POST['topic'];
    $msg = $_POST['message'];

    $request_msg = "SELECT * FROM `message_contact` WHERE Name_Cont=:name AND Email_Cont=:email AND Phone_Cont=:phone AND Topic=:topic AND Message=:message";
    $select_message = $idcom->prepare($request_msg);
    $select_message->bindValue(":name", $name, PDO::PARAM_STR);
    $select_message->bindValue(":email", $email, PDO::PARAM_STR);
    $select_message->bindValue(":phone", $number, PDO::PARAM_STR);
    $select_message->bindValue(":topic", $name, PDO::PARAM_STR);
    $select_message->bindValue(":message", $msg, PDO::PARAM_STR);
    $select_message->execute();
    $nbre=$select_message->rowCount();
    

   if($nbre > 0){
      $message[] = 'message sent already!';
   }else{
      $insert_message="INSERT INTO `message_contact`(Name_Cont,Email_Cont,Phone_Cont,Topic,Message) VALUES(:name,:email,:number,:topic,:msg)";
      $contact = $idcom->prepare($insert_message);
      $contact->bindValue(':name', $name, PDO::PARAM_STR);
      $contact->bindValue('email', $email, PDO::PARAM_STR);
      $contact->bindValue('number', $number,PDO::PARAM_STR);
      $contact->bindValue(':topic', $topic, PDO::PARAM_STR);
      $contact->bindValue(":message", $msg, PDO::PARAM_STR);
      $select_message->execute();

      $message[] = 'message sent successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="heading">
   <h3>contact us</h3>
   <p> <a href="home.php">home</a> / contact </p>
</div>

<section class="contact">

   <form action="" method="post">
      <h3>say something!</h3>
      <input type="text" name="name" required placeholder="enter your name" class="box">
      <input type="email" name="email" required placeholder="enter your email" class="box">
      <input type="number" name="number" required placeholder="enter your number" class="box">
      <input type="text" name="topic" required placeholder="enter the topic of your message" class="box">
      <textarea name="message" class="box" placeholder="enter your message" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>

</section>








<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>