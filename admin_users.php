<?php

use LDAP\Result;

include 'conn.php';

session_start();

$admin_id = $_SESSION['Admin_Id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $idcom=connexpdo('bookstore','myparam');
   $query = "CREATE OR REPLACE VIEW MyClient1 AS SELECT * FROM client WHERE User_Id=:user_id";
   $result = $idcom->prepare($query);
   $result->bindValue(':user_id',$delete_id,PDO::PARAM_INT);
   $result->execute();
   $query1 = $idcom->prepare("SELECT * FROM MyClient1");
   $query1->execute();
   $nbre=$query1->rowCount();
   echo $nbre; 
   if ($nbre > 0) {
      foreach ($idcom->query("SELECT * FROM MyClient1") as $Client ) {
         $del=$idcom->prepare("DELETE  FROM `order` WHERE Client_Id =:id");
         $del->bindValue(":id", $Client['Client_Id'], PDO::PARAM_STR);
         $del->execute();
      }
   }
   $request ="DELETE FROM `client` WHERE User_Id=:delete_id";
   $result =$idcom->prepare($request);
   $result->bindValue(':delete_id',$delete_id,PDO::PARAM_INT);
   $result->execute();
   $request1="DELETE FROM `user` WHERE User_Id=:delete_id";
   $result1=$idcom->prepare($request1);
   $result1->bindValue(':delete_id',$delete_id,PDO::PARAM_INT);
   $result1->execute();


   header('location:admin_users.php');
}
if (isset($_POST['update_user'])) {

   $update_user_id = (integer) $_POST['update_user_id'];
   $update_Email = $_POST['update_email'];
   $request5 = "UPDATE `user` SET User_Email=:email WHERE User_Id=:id";
   $idcom = connexpdo('bookstore', 'myparam');
   $update_user = $idcom->prepare($request5);
   $update_user->bindValue(':email', $update_Email, PDO::PARAM_STR);
   $update_user->bindValue(':id', $update_user_id, PDO::PARAM_INT);
   $update_user->execute();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>users</title>

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
          <form action="search_user.php" method="post">
           <input type="text" name="search_user" placeholder="enter user id..." class="box">
           <input type="submit" name="submit" value="search user" class="btn">
          </form>
       </section>

</div>      
<BR> <BR>

<section class="users">

   <h1 class="title"> user accounts </h1>

   <div class="box-container">
      <?php
        $idcom=connexpdo('bookstore','myparam');
        $req = $idcom->prepare("SELECT * FROM `user`");
        $req->execute();
        $nbre = $req->rowCount();
      if ($nbre > 0) {
         foreach ($idcom->query("SELECT * FROM `user`") as $fetch_users) { ?> 
      <div class="box">
         <p> user id : <span><?php echo $fetch_users['User_Id']; ?></span> </p>
         <p> email:<br> <span><?php echo $fetch_users['User_Email']; ?></span> </p>
         <p> user type : <span style="color:<?php if ($fetch_users['User_type'] == 'Admin') {
            echo 'var(--orange)';
         } ?>"><?php echo $fetch_users['User_Type']; ?></span> </p>
         <a href="admin_users.php?delete=<?php echo $fetch_users['User_Id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete user</a>
         <a href="admin_users.php?update=<?php echo $fetch_users['User_Id']; ?>" class="option-btn">update</a>
         </div>
         <?php
         }
      }
      else { 
         echo '<p class="empty">no user found!</p>';
      };
      ?>
   </div>

</section>
<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $conn = mysqli_connect('localhost','root','','bookstore') or die('connection failed');
         $update_query = mysqli_query($conn, "SELECT * FROM `user` WHERE  User_Id= '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_user_id" value="<?php echo $fetch_update['User_Id']; ?>">
      <input type="text" name="update_email" value="<?php echo $fetch_update['User_Email']; ?>" class="box"  placeholder="enter the user's email">
      <input type="submit" value="update" name="update_user" class="btn">
      <input type="reset" value="cancel" id="close-update" class="option-btn">
   </form>
   <?php
         }
      }
      }else{
         echo '<script>document.querySelector(".edit-product-form").style.display ="none";</script>';
      }
   ?>

</section>









<!-- custom admin js file link  -->
<script src="js/admin_script.js"></script>

</body>
</html>