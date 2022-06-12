


<!DOCTYPE html>
<html>
   <head>
   	<meta charset="utf-8" />
   	<title><?php getTitle()?></title>
   	<link rel="stylesheet" href="layout/css/bootstrap.min.css"/>
   	<link rel="stylesheet" href="layout/css/font-awesome.min.css"/>
   	<link rel="stylesheet" href="layout/css/jquery-ui.css"/>
   	<link rel="stylesheet" href="layout/css/jquery.selectBoxIt.css"/>
   	<link rel="stylesheet" href="layout/css/frontend.css"/>
   		
   </head>
   <body>
      <div class="upper-bar">
        <div class="container">
          <?php 

if(isset($_SESSION['user'])){?>
  <div class="btn-group my-info">
    <img class= "img-thumbnail rounded-circle"src="1.jpg" alt="" />
    <span class="btn dropdown-toggle" data-toggle="dropdown">
      <?php echo $sessionUser ?>
      
    </span>
      <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
        <li><a class="dropdown-item" href ="newad.php">New Item</a></li>
        <li><a class="dropdown-item" href ="profile.php#my-ads">My Items</a></li>
        <li><a class="dropdown-item" href ="logout.php">Logout</a></li>
      </ul>
  </div>
  <?php
  
} else{


?>
          <a href="login.php">
            <span class="pull-right">Login/Signup</span>
          </a>
        <?php } ?>
        </div>
      </div>
      <nav class="mb-1 navbar navbar-expand-lg navbar-dark primary-color bg-primary">
  <a class="navbar-brand" href="index.php">HomePage</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
    aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
    <ul class="navbar-nav  ml-auto">
      
     <?php
     $categories= getCat();
      foreach($categories as $cat){
           echo  '<li class="nav-item"><a class="nav-link" href="categories.php?pageid=' . $cat['ID']  . '">' . $cat['Name'] . '</a></li>';
}
?>
    </ul>
    
  </div>
</nav>
<!--/.Navbar -->


 