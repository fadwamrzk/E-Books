<?php

include 'conn.php';
session_start();
/*if ((!(isset($_POST['email'])) || (!isset($_POST['password'])))) {
   echo "Missing Information!";
   return;

}*/
if(isset($_POST['submit'])){

   $UserEmail = ($_POST['email']);
   $pass =md5($_POST['password']);
   //echo $UserName;
   //echo $pass;

   $request= "SELECT * FROM `user` WHERE User_Email =:UserEmail AND Passwod =:pass";
   $idcom=connexpdo('bookstore','myparam');
   $result = $idcom->prepare($request);
   $result->bindValue(":UserEmail",$UserEmail,PDO::PARAM_STR);
   $result->bindValue(":pass",$pass,PDO::PARAM_STR);
   $result->execute();
   $nbart=$result->rowCount();
   //include_once 'index.php';
   //echo $nbart;
   if($nbart>0){

      $row =$result->fetch(PDO::FETCH_ASSOC);
      $titres=array_keys($row);
      $ligne=array_values($row);

      /*foreach($titres as $titre)
      
         echo $titre;
         echo "empty";
      }
      
      foreach($lignes as $data)
      {
         echo $data;
         echo " empty";
      }*/

      if($row['User_Type'] == 'Admin'){

         $_SESSION['AdminEmail'] = $row['User_Email'];
         $_SESSION['Admin_Id'] = $row['User_Id'];
         $_SESSION['Admin_Name'] =$row['User_Name'];
         $_SESSION['Admin_Pass'] =md5($row['Passwod']);
         header('location:admin_page.php');

      }elseif($row['User_Type'] == 'Client'){

         $_SESSION['ClientEmail'] = $row['User_Email'];
         $_SESSION['Client_Id'] = $row['User_Id'];
         $_SESSION['Client_Name'] =$row['User_Name'];
         $_SESSION['Client_Pass'] =md5($row['Passwod']);

         header('location:home.php');

      }

   }else{
      $message[] = 'incorrect email or password!';
   }
   //include_once 'index.php';

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

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
      <h3>login now</h3>
      <input type="email" name="email" placeholder="enter your email" required class="box">
      <input type="password" name="password" placeholder="enter your password" required class="box">
      <input type="submit" name="submit" value="login now" class="btn">
      <p>don't have an account? <a href="register.php">register now</a></p>
   </form>

</div>

</body>
</html>
