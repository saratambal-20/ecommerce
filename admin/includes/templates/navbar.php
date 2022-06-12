<nav class="mb-1 navbar navbar-expand-lg navbar-dark primary-color bg-primary">
  <a class="navbar-brand" href="dashboard.php"><?php echo lang('HOME_ADMIN')?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-333"
    aria-controls="navbarSupportedContent-333" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent-333">
    <ul class="navbar-nav mr-auto">
      
      <li class="nav-item">
        <a class="nav-link" href="categories.php"><?php echo lang('CATEGORIES')?></a>
      </li>
      <li class="nav-item">
         <a class="nav-link" href="items.php"><?php echo lang('ITEMS')?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="members.php?do=Manage"><?php echo lang('MEMBERS')?></a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="comments.php"><?php echo lang('COMMENTS')?></a>
      </li>
      <li class="nav-item">
       <a class="nav-link" href="#"><?php echo lang('STATISTICS')?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang('LOGS')?></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto nav-flex-icons">
      
  
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-333" data-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">Osama
        
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-default"
          aria-labelledby="navbarDropdownMenuLink-333">
          <a class="dropdown-item" href="../index.php">Visit Shop</a>
          <a class="dropdown-item" href="members.php?do=Edit&userid=<?php echo $_SESSION['ID']?>">Edit Profile</a>
          <a class="dropdown-item" href="#">setting</a>
          <a class="dropdown-item" href="logout.php">logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>
<!--/.Navbar -->