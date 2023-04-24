<?php

use LDAP\Result;

include 'conn.php';

if(isset($_POST['submit'])){

   $email =($_POST['email']);
   $pass =(md5($_POST['password']));
   $cpass =(md5($_POST['cpassword']));
   //$user_type = $_POST['user_type'];
    $request = "SELECT * FROM `user` WHERE User_Email=:email AND Passwod =:pass";
    $idcom=connexpdo('bookstore','myparam');
    $result = $idcom->prepare($request);
    $result->bindValue(':email', $email, PDO::PARAM_STR);
    $result->bindValue(':pass', $pass, PDO::PARAM_STR);
    $result->execute();
    $nbart=$result->rowCount();
   //echo $nbart;
   if($nbart> 0){
      $message[] ='user already exist!';
   }else{
      if($pass!= $cpass){
         $message[] = 'confirm password not matched!';
      }else{
            $result1 = $idcom->prepare("INSERT INTO `user`(User_Email,Passwod) VALUES(:email,:cpass)");
            $result1->bindValue(':email', $email, PDO::PARAM_STR);
            $result1->bindValue(':cpass', $cpass, PDO::PARAM_STR);
            $result1->execute();
            $message[] = 'registered successfully!';
            header('location:login.php');
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>



<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="email" name="email" aria-describedby="email-help" placeholder="you@exemple.com" required class="box">
      <input type="password" name="password" placeholder="enter your password" required class="box">
      <input type="password" name="cpassword" placeholder="confirm your password" required class="box">
      <input type="submit" name="submit" value="register now" class="btn">
      <p>already have an account? <a href="login.php">login now</a></p>
   </form>

</div>

</body>
</html>