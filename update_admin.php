<?php

include 'conn.php';

session_start();

if(isset($_SESSION['Admin_Id'])){
   $user_id = $_SESSION['Admin_Id'];
}else{
   $user_id = '';
}
$idcom=connexpdo('bookstore','myparam');
if(isset($_POST['submit'])){

  
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);

   $update_profile = $idcom->prepare("UPDATE `user` SET User_Email =:email WHERE User_Id=:id");
   $update_profile->bindValue(":email", $email, PDO::PARAM_STR);
   $update_profile->bindValue(":id", $user_id, PDO::PARAM_INT);
   $update_profile->execute();

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $prev_pass = $_POST['prev_pass'];
   $old_pass = md5($_POST['old_pass']);
   $new_pass = md5($_POST['new_pass']);
   $cpass =md5($_POST['cpass']);

   if($old_pass == $empty_pass){
      $message[] = 'please enter old password!';
   }elseif($old_pass != $prev_pass){
      $message[] = 'old password not matched!';
   }elseif($new_pass != $cpass){
      $message[] = 'confirm password not matched!';
   }else{
      if($new_pass != $empty_pass){
         $update_admin_pass = $idcom->prepare("UPDATE `user` SET Passwod =:pass WHERE User_Id =:id");
         $update_admin_pass->bindValue(":pass", $cpass, PDO::PARAM_STR);
         $update_admin_pass->bindValue(":id", $user_id, PDO::PARAM_INT);
         $update_admin_pass->execute();
         $message[] = 'password updated successfully!';
      }else{
         $message[] = 'please enter a new password!';
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
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>update now</h3>
      <input type="hidden" name="prev_pass" value="<?= $_SESSION['Admin_Pass']; ?>">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $_SESSION["AdminEmail"]; ?>">
      <input type="password" name="old_pass" placeholder="enter your old password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="enter your new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" placeholder="confirm your new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="update now" class="btn" name="submit">
   </form>

</section>













<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>