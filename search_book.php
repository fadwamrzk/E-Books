<?php

include 'conn.php';

session_start();

$admin_id = $_SESSION['Admin_Id'];

if(!isset($admin_id)){
   header('location:login.php');
}
;
$book_s = $_POST['search'];
if(isset($_POST['add_book'])){
   $title=(string)$_POST['title'];
   $ISBN=$_POST['ISBN']; 
   $author=$_POST['author']; 
   $price = (integer)$_POST['price'];
   $date_pub=$_POST['date']; 
   $page=(integer)$_POST['page'];
   $genre=$_POST['genre'];  
   $image=$_FILES['image']['name'];
   $image_size=$_FILES['image']['size'];
   $image_tmp_name=$_FILES['image']['tmp_name'];
   $image_folder='uploaded_img/'.$image;
   
   $idcom=connexpdo('bookstore','myparam');
   $select_book_title=$idcom->prepare("SELECT * FROM `book` WHERE Title=:title");
   $select_book_title->bindValue(':title',$title,PDO::PARAM_STR);
   $select_book_title->execute();
   $nbart=$select_book_title->rowCount();
   if($nbart > 0){
      $message[] = 'book title already added';
   }else{
        
    $request2 = "INSERT INTO `book`(Title,ISBN,Author,Price,Date_Pub,Page_Num,Genre,Image) VALUES(:title,:ISBN,:author,:price,:date,:page,:genre,:image)";
    $idcom=connexpdo('bookstore','myparam');
    $add_book = $idcom->prepare($request2);
    $add_book->bindValue(':title', $title, PDO::PARAM_STR);
    $add_book->bindValue(':ISBN', $ISBN, PDO::PARAM_STR);
    $add_book->bindValue(':author', $author, PDO::PARAM_STR);
    $add_book->bindValue(':price', $price, PDO::PARAM_INT);
    $add_book->bindValue(':date', $date_pub, PDO::PARAM_STR);
    $add_book->bindValue(':page', $page, PDO::PARAM_INT);
    $add_book->bindValue('genre', $genre, PDO::PARAM_STR);
    $add_book->bindValue(':image', $image, PDO::PARAM_STR);
    $add_book->execute();
    if($add_book){
         if($image_size > 2000000){
            $message[] = 'image size is too large';
         }else{
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'product added successfully!';
         }
}}}

if(isset($_GET['delete'])){
  $idcom=connexpdo('bookstore','myparam');
   $delete_id =(integer)$_GET['delete'];
   $request3="SELECT Image FROM `book` WHERE IdBook= :delete_id";
   $delete_image_query=$idcom->prepare($request3);
   $delete_image_query->bindValue(':delete_id',$delete_id,PDO::PARAM_INT);
   $delete_image_query->execute();
   $fetch_delete_image =$delete_image_query->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $idcom=connexpdo('bookstore','myparam');
   $request4="DELETE FROM `book` WHERE IdBook= :delete_id";
   $delete_image=$idcom->prepare($request4);
   $delete_image->bindValue(':delete_id',$delete_id,PDO::PARAM_INT);
   $delete_image->execute();
   header('location:admin_book.php');
}

if(isset($_POST['update_book'])){

   $update_b_id =(integer)$_POST['update_b_id'];
   $update_title= $_POST['update_title'];
   $update_price =(integer)$_POST['update_price'];
   $update_ISBN = $_POST['update_ISBN'];
   $update_author = $_POST['update_author'];
   $update_date = $_POST['update_date'];
   $update_page=(integer)$_POST['update_page'];
   $update_genre = $_POST['update_genre'];
   $request5 = "UPDATE `book` SET Title=:title,Price=:price,ISBN=:ISBN,Author=:author,Date_Pub=:date,Page_Num=:page,Genre=:genre";
   $idcom=connexpdo('bookstore','myparam');
   $update_book = $idcom->prepare($request5);
   $update_book->bindValue(':title', $update_title, PDO::PARAM_STR);
   $update_book->bindValue(':price', $update_price, PDO::PARAM_INT);
   $update_book->bindValue(':ISBN', $update_ISBN, PDO::PARAM_STR);
   $update_book->bindValue(':author', $update_author, PDO::PARAM_STR);
   $update_book->bindValue(':date',$update_date,PDO::PARAM_STR);
   $update_book->bindValue(':page', $update_page, PDO::PARAM_INT);
   $update_book->bindValue(':genre', $update_genre, PDO::PARAM_STR);
   $update_book->execute();
   

   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_folder = 'uploaded_img/'.$update_image;
   $update_old_image = $_POST['update_old_image'];

   if(!empty($update_image)){
      if($update_image_size > 2000000){
         $message[] = 'image file size is too large';
      }else{
         $request6 = "UPDATE `book` SET Image = ':update_image' WHERE IdBook = :update_b_id";
         $idcom=connexpdo('bookstore','myparam');
         $updateimg = $idcom->prepare($request6);
         $updateimg->bindValue(':update_iamge',$update_image,PDO::PARAM_STR);
         $updateimg->bindValue(':update_b_id', $update_b_id, PDO::PARAM_INT);
         $updateimg->execute();
         move_uploaded_file($update_image_tmp_name, $update_folder);
         unlink('uploaded_img/'.$update_old_image);
      }
   }

   header('location:admin_book.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Books</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<!-- product CRUD section starts  -->

<section class="add-products">

   <h1 class="title">shop books</h1>

   <form action="" method="post" enctype="multipart/form-data">
      <h3>add book</h3>
      <input type="text" name="title" class="box" placeholder="enter the book title" required>
      <input type="text" name="ISBN" class="box" placeholder="enter the book ISBN" required>
      <input type="text" name="author" class="box" placeholder="enter the book's author" required>
      <input type="number" min="0" name="price" class="box" placeholder="enter the book price" required>
      <input type="date" name="date" class="box" placeholder="enter the publishing date" required>
      <input type="number" name="page" class="box" placeholder="enter the number of pages " required>
      <input type="text" name="genre" class="box" placeholder="enter the book's genre" required> 
      <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required>
      <input type="submit" value="add book" name="add_book" class="btn">
   </form>

</section>

<!-- product CRUD section ends -->

<!-- show products  -->
<div class="box-container">
       <section; class="search-form">
          <form action="search_book.php" method="post">
           <input type="text" name="search" placeholder="search book..." class="box">
           <input type="submit" name="submit" value="search" class="btn">
          </form>
       </section>
</div>       
<BR> <BR>
<section class="show-products">
<div class="box-container">
      <?php
         $conn = mysqli_connect('localhost','root','','bookstore') or die('connection failed');
         $select_products = mysqli_query($conn, "SELECT * FROM `book` WHERE  Title LIKE '%{$book_s}%'") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <div class="box">
         <img src="uploaded_img/<?php echo $fetch_products['Image']; ?>" alt="">
         <div class="name">Title : <?php echo $fetch_products['Title']; ?></div>
         <div class="name">ISBN : <?php echo $fetch_products['ISBN']; ?></div>
         <div class="name">Author : <?php echo $fetch_products['Author']; ?></div>
         <div class="name">Release Date : <?php echo $fetch_products['Date_Pub']; ?></div>
         <div class="name">Pages: <?php echo $fetch_products['Page_Num']; ?></div>
         <div class="name">Genre: <?php echo $fetch_products['Genre']; ?></div>
         <div class="price"><?php echo $fetch_products['Price']; ?>DT</div>
         <a href="admin_book.php?update=<?php echo $fetch_products['IdBook']; ?>" class="option-btn">update</a>
         <a href="admin_book.php?delete=<?php echo $fetch_products['IdBook']; ?>" class="delete-btn" onclick="return confirm('delete this book?');">delete</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no book added yet!</p>';
      }
      ?>
   </div>

</section>

<section class="edit-product-form">

   <?php
      if(isset($_GET['update'])){
         $update_id = $_GET['update'];
         $conn = mysqli_connect('localhost','root','','bookstore') or die('connection failed');
         $update_query = mysqli_query($conn, "SELECT * FROM `book` WHERE  IdBook= '$update_id'") or die('query failed');
         if(mysqli_num_rows($update_query) > 0){
            while($fetch_update = mysqli_fetch_assoc($update_query)){
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_b_id" value="<?php echo $fetch_update['IdBook']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['Image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['Image']; ?>" alt="">
      <input type="text" name="update_title" value="<?php echo $fetch_update['Title']; ?>" class="box"  placeholder="enter the book's title">
      <input type="text" name="update_ISBN" value="<?php echo $fetch_update['ISBN']; ?>" class="box"  placeholder="enter the book's ISBN">
      <input type="text" name="update_author" value="<?php echo $fetch_update['Author']; ?>" class="box"  placeholder="enter the book's author">
      <input type="number" name="update_price" value="<?php echo $fetch_update['Price']; ?>" min="0" class="box"  placeholder="enter book's price ">
      <input type="date" name="update_date" value="<?php echo $fetch_update['Date_Pub']; ?>" class="box"  placeholder="enter the book's release date">
      <input type="number" name="update_page" value="<?php echo $fetch_update['Page_Num']; ?>" class="box"  placeholder="enter the book's pages number">
      <input type="text" name="update_genre" value="<?php echo $fetch_update['Genre']; ?>" class="box"  placeholder="enter the book's genre">
      <input type="file" class="box" name="update_image" accept="image/jpg, image/jpeg, image/png">
      <input type="submit" value="update" name="update_book" class="btn">
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