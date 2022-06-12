<?php
ob_start();
session_start();
$pageTitle='Members';

if(isset($_SESSION['Username'])){
	
	include 'init.php' ;
$do = isset($_GET['do']) ? $_GET['do'] :'Manage' ;	

if ($do== 'Manage'){

	$query = '';
	if (isset($_GET['page']) && $_GET['page'] == 'Pending'){
		$query = 'AND RegStatus = 0';
	}
	
     $stmt = $con-> prepare( "SELECT * FROM users WHERE GROUPID != 1 $query  ORDER BY UserID DESC");
     $stmt->execute();
     $rows=$stmt->fetchAll();

     if(! empty($rows)){
	?>
     
    
    <h1 class="text-center">Manage Member</h1>
    <div class="container">
    	<div class="table-responsive">
    		<table class="main-table table manage-members text-center table-bordered">
    			<tr>
    				<td>#ID</td>
    				<td>Avatar</td>
    				<td>Username</td>
    				<td>Email</td>
    				<td>Full Name</td>
    				<td>Registerd Date</td>
    				<td>Control</td>
    			</tr>
    			<?php
    			foreach ($rows as $row) {
    				echo "<tr>";
    				echo "<td>". $row['UserID'] . "</td>";
    				echo "<td>";
    				if(empty( $row['avatar'])){
    					echo "No Image";
    				}
    				else{
    				echo "<img src='upload/avatars/". $row['avatar'] . "'alt='' />"; }
    				echo"</td>";
    				echo "<td>". $row['Username'] . "</td>";
    				echo "<td>". $row['Email'] . "</td>";
    				echo "<td>". $row['FullName'] . "</td>";
    				echo "<td>" .$row['Date'] . "</td>";
    				echo "<td><a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
    					<a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
    					if($row['RegStatus'] == 0){
    						echo "<a href='members.php?do=Activate&userid=" . $row['UserID'] . "'class='btn btn-info ml-2 activated'><i class='fa fa-check'></i>Activate</a>";

    					}
    					echo "</td>";
    				echo "</tr>";
    			}
    			?>
    			
    		</table>
    	</div>
    	<a href="members.php?do=Add" class="btn btn-primary"> <i class="fa fa-plus"></i> New Member</a>
</div>

 <?php }
 else {
 	echo '<div class="container">';
 	echo '<div class="nice-message"> There is no Record to show</div>';
 	echo '</div';
 } ?>


<?php } elseif($do== 'Add'){
?>
	<h1 class="text-center">Add Member</h1>
<div class="container">
	<form class="form-horizontal" action ="?do=Insert" method="POST" enctype="multipart/form-data">
		
		<!-- start username-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Username</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="username" class="form-control" autocomplete="off" required="required" placeholder=""/>
			</div>
		</div>
        <!-- start password-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Password</label>
			<div class="col-sm-10 col-md-4">
				
				<input type="password" name="password" class="form-control password" autocomplete="new-password" required="required" placeholder="" />
				<i class= "show-pass fa fa-eye fa-2x"></i>
			</div>
		</div>
         <!-- start email-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Email</label>
			<div class="col-sm-10 col-md-4">
				<input type="email" name="email" class="form-control" placeholder="" required="required"/>
			</div>
		</div>
		<!-- start fullname-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Full Name</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="full" class="form-control" required="required" placeholder=""/>
			</div>
		</div>
			<!-- start Avatar-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">User Avatar</label>
			<div class="col-sm-10 col-md-4">
				<input type="file" name="avatar" class="form-control" required="required" placeholder=""/>
			</div>
		</div>
		<!-- start submit-->
		<div class="form-group row">
			
			<div class="offset-sm-4 col-sm-10">
				<input type="submit" value="Add Member" class="btn btn-primary"/>
			</div>
		</div>

	</form>
</div>
<?php }
elseif( $do=='Insert'){
//echo $_POST['username'] . $_POST['password'] . $_POST['email'] . $_POST['full'];

		
		if($_SERVER['REQUEST_METHOD']=='POST'){
		echo "<h1 class ='text-center'>Update Member</h1>";
		echo "<div class='container'>";

		   // $avatar=$_FILES['avatar'];

			$avatarName= $_FILES['avatar']['name'];
			$avatarSize= $_FILES['avatar']['size'];
			$avatarTmp= $_FILES['avatar']['tmp_name'];
			$avatarType= $_FILES['avatar']['type'];
			$avatarAllowedExtension= array("jpeg","jpg","png","gif");

			$avatarExtension= strtolower(end(explode('.',$avatarName)));




			$user=$_POST['username'];
			$pass=$_POST['password'];
			$email=$_POST['email'];
			$name=$_POST['full'];
           $hashPass= sha1($_POST['password']);

     
			// validate the form
			$formErrors = array();
			if(strlen($user)<4){
				$formErrors[] = '<div class="alert alert-danger">username cant be less than 4 characters</div>';
			}
			if(empty ($user)){
				$formErrors[] = '<div class="alert alert-danger"> username cant be empty </div>';
			}
			if(empty ($pass)){
				$formErrors[] = '<div class="alert alert-danger"> password cant be empty </div>';
			}
			if(empty ($name)){
				$formErrors[] = '<div class="alert alert-danger"> fullname cant be empty </div>';
			}
			if(empty ($email)){
				$formErrors[] = '<div class="alert alert-danger"> email cant be empty </div>';
			}
			if(! empty($avatarName) && ! in_array($avatarExtension,$avatarAllowedExtension)){
				$formErrors[] = '<div class="alert alert-danger">This Extension is not allowed</div>';
			}
			if(empty($avatarName)){
				$formErrors[] = '<div class="alert alert-danger">Image is required</div>';
			}
			if($avatarSize > 4194304){
				$formErrors[] = '<div class="alert alert-danger">Avatar cant be larger than 4MB</div>';
			}


			foreach($formErrors as $error){
				echo $error . '<br/>';
				
			}
			
     if(empty($formErrors)){ 
                $avatar = rand(0,100000) . '_' . $avatarName;
				move_uploaded_file($avatarTmp,"upload\avatars\\" . $avatar);

          
	$check= checkItem("Username", "users",$user);
	if($check==1){
		$theMsg= '<div class="alert alert-danger"> sorry this user is exist</div>'; 
		 redirectHome($theMsg,'back');
	}else{
          $stmt=$con->prepare("INSERT INTO users(Username,Password,Email,FullName,RegStatus,Date,avatar) VALUES(:zuser,:zpass,:zmail,:zname, 1,now(),:zavatar)");
          $stmt->execute(array(
            'zuser' =>  $user,
            'zpass' =>  $hashPass,
            'zmail' =>  $email,
            'zname' =>  $name,
            'zavatar' =>  $avatar
        ));


          
     	
         $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record inserted </div>';
          redirectHome($theMsg,'back');

     }
        }

		} 
		else{
			echo '<div class= "container">';

			$theMsg = '<div class="alert alert-danger">sorry you can not browse this page directly</div>'; 
			redirectHome($theMsg);
			echo '</div>';
		}
		echo "</div>";	
}
elseif ( $do=='Edit')
{ 
	$userid= isset($_GET['userid'])&&is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;


$stmt = $con-> prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1" );
$stmt->execute(array($userid));
$row= $stmt->fetch();
$count =$stmt->rowCount();
if($count >0){?>
	<h1 class="text-center">Edit Member</h1>
<div class="container">
	<form class="form-horizontal" action ="?do=Update" method="POST">
		<input type ="hidden"name ="userid" value="<?php echo $userid ?>"/>
		<!-- start username-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Username</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="username" class="form-control" value="<?php echo $row['Username'] ?>" autocomplete="off" required="required"/>
			</div>
		</div>
        <!-- start password-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Password</label>
			<div class="col-sm-10 col-md-4">
				<input type="hidden" name="oldpassword"value="<?php echo $row['Password'] ?>" />
				<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave blank if you dont want to change" />
			</div>
		</div>
         <!-- start email-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Email</label>
			<div class="col-sm-10 col-md-4">
				<input type="email" name="email" class="form-control" value="<?php echo $row['Email'] ?>" required="required"/>
			</div>
		</div>
		<!-- start fullname-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Full Name</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="full" class="form-control" value="<?php echo $row['FullName'] ?>"/>
			</div>
		</div>
		<!-- start submit-->
		<div class="form-group row">
			
			<div class="offset-sm-4 col-sm-10">
				<input type="submit" value="save" class="btn btn-primary"/>
			</div>
		</div>

	</form>
</div>
	
<?php }	else{
	echo '<div class"container">';
	$theMsg = '<div class="alert alert-danger">There is no such ID</div>';
	 redirectHome($theMsg);
	echo '</div>';
}

	}elseif ($do=='Update') {

		echo "<h1 class ='text-center'>Update Member</h1>";
		echo "<div class='container'>";
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$id=$_POST['userid'];
			$user=$_POST['username'];
			$email=$_POST['email'];
			$name=$_POST['full'];


            $pass=empty($_POST['newpassword'])? $_POST['oldpassword'] : sha1($_POST['newpassword']);
			// validate the form
			$formErrors = array();
			if(strlen($user)<4){
				$formErrors[] = '<div class="alert alert-danger">username cant be less than 4 characters</div>';
			}
			if(empty ($user)){
				$formErrors[] = '<div class="alert alert-danger"> username cant be empty </div>';
			}
			if(empty ($name)){
				$formErrors[] = '<div class="alert alert-danger"> fullname cant be empty </div>';
			}
			if(empty ($email)){
				$formErrors[] = '<div class="alert alert-danger"> email cant be empty </div>';
			}
			foreach($formErrors as $error){
				echo $error . '<br/>';
			}
     if(empty($formErrors)){

     $stmt2 = $con-> prepare("SELECT * FROM users WHERE Username = ? AND UserID != ?" );
     $stmt2->execute(array($user,$id));
     $count=$stmt2->rowCount();
     if ($count == 1){
     $theMsg =  '<div class="alert alert-danger">Sorry This User is Exist </div>';	
      redirectHome($theMsg, 'back');
     }
     else{
     	
 
     	 $stmt=$con->prepare("UPDATE users SET Username=?, Email=?, FullName=? ,Password=? WHERE UserID=?");
         $stmt->execute(array($user,$email,$name,$pass,$id));
         
         $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Updated </div>';
         redirectHome($theMsg, 'back');

     }
        }

		}else{
			$theMsg = '<div class="alert alert-danger">sorry you can not browse this page directly</div>'; 
			 redirectHome($theMsg);
		}
		echo "</div>";
	}

	elseif ($do == 'Delete'){

		echo "<h1 class ='text-center'>Delete Member</h1>";
		echo "<div class='container'>";

		$userid= isset($_GET['userid'])&&is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check= checkItem("UserID", "users", $userid);
     
        if($check > 0) {

        	$stmt=$con->prepare("DELETE FROM users WHERE UserID = :zuser");
        	$stmt->bindParam(":zuser", $userid);
        	$stmt->execute();
        	 $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Deleted </div>';
        	 redirectHome($theMsg,'back');

        	}
             


        	else{$theMsg ='<div class="alert alert-danger">This ID is not exist</div>';
        	redirectHome($theMsg);
        }

        	echo "</div>";


	} elseif ($do == "Activate") {
		echo "<h1 class ='text-center'>Activate Member</h1>";
		echo "<div class='container'>";

		$userid= isset($_GET['userid'])&&is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $check= checkItem("UserID", "users", $userid);
     
        if($check > 0) {

        	$stmt=$con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");
        	
        	$stmt->execute(array($userid));
        	 $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Updated </div>';
        	 redirectHome($theMsg);

        	}
             


        	else{$theMsg ='<div class="alert alert-danger">This ID is not exist</div>';
        	redirectHome($theMsg);
        }

        	echo "</div>";


	}

	include $tpl .'footer.php';
}
else{
	
	header('Location:index.php');
	exit();
}
ob_end_flush();
?>