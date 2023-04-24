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

<header class="header">

   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <p> New <a href="login.php">Login</a> | <a href="register.php">Register</a> </p>
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">Bookly.</a>

         <nav class="navbar">
            <a href="Home.php">Home</a>
            <a href="About.php">About</a>
            <a href="Shop.php">Shop</a>
            <a href="Contact.php">Contact</a>
            <a href="Orders.php">Orders</a>
         </nav>
         <?php if (isset($_SESSION['Client_Id'])) {?> 
         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <a id="user-btn" class="fas fa-user"></a>  
            <?php
                $idcom=connexpdo('bookstore','myparam');
                $select_cart_number = $idcom->prepare("SELECT * FROM `cart` WHERE User_id = :user_id");
                $select_cart_number->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $select_cart_number->execute();
                $nbart = $select_cart_number->rowCount();
            ?>
            <a href="cart.php"> <i class="fas fa-shopping-cart"></i> <span>(<?php echo $nbart; ?>)</span> </a>
            </div> 
              <div class="user-box">
              <p>email : <span><?php echo $_SESSION['ClientEmail']; ?></span></p>
              <a href="update_user.php" class="delete-btn">update profile</a>
              <BR> <BR>
              <a href="logout.php" class="delete-btn">logout</a> 
              </div>
                <?php }
            else{?>
               <div class="icons">
               <a href="search_page.php" class="fas fa-search"></a><?php }?>
               </div>
              
         </div>
      </div>
   </div>

</header>