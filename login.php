<?php 
ob_start();
session_start();
$pageTitle= 'Login';
if(isset($_SESSION['user'])){
	header('Location:index.php');
}
include 'init.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['login'])){
		$user= $_POST['username'];
		$pass= $_POST['password'];
		$hashedPass= sha1($pass);

		$stmt = $con-> prepare("SELECT  UserID,Username,Password From users WHERE Username = ? AND Password = ?");
		$stmt->execute(array($user,$hashedPass));
		$get = $stmt->fetch();

		$count =$stmt->rowCount();
		
		if($count > 0){
			$_SESSION['user']=$user;
		  $_SESSION['uid'] = $get['UserID'];

			header('Location:index.php');
			exit();
			
	} 
} else{

	$formErrors = array();
	$username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $email = $_POST['email'];

    if(isset($_POST['username'])){
    	$filterdUser = filter_var($_POST['username'],FILTER_SANITIZE_STRING);
    	if(strlen($filterdUser)< 4){
    		$formErrors[] = 'Username must be larger than 4 characters';
    	}
    }
     if(isset($_POST['password']) && isset($_POST['password2'])){
    	$pass1 = sha1($_POST['password']);
    	$pass2 = sha1($_POST['password2']);
    	if($pass1 !== $pass2){
    		$formErrors[] = 'Sorry Password is not Match';
    	}
    }
    if(isset($_POST['email'])){
    	$filterdEmail = filter_var($_POST['email'],FILTER_SANITIZE_EMAIL);
    	if(filter_var($filterdEmail,FILTER_VALIDATE_EMAIL ) != true){
    		$formErrors[] = 'This Email is not valid ';
    	}
    }
     if(empty($formErrors)){ 
          
	$check= checkItem("Username", "users", $username);
	if($check == 1){
		$formErrors[] = 'This user is exist'; 
		
	} else{
          $stmt=$con->prepare("INSERT INTO users(Username,Password,Email,RegStatus,Date) VALUES(:zuser,:zpass,:zmail, 0,now())");
          $stmt->execute(array(
            'zuser' =>  $username,
            'zpass' =>  sha1($password),
            'zmail' =>  $email
            ));


          
     	
         $succesMsg = 'Congrats you are now registered user';

     }
        }

}
	
}



 ?>
 <div class="container login-page">
 	<h1 class="text-center"><span class="selected" data-class="login">Login</span> | 
 		<span data-class="signup">Signup</span></h1>

 	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>"method="POST">

 		<div class="input-container"><input class="form-control mb-2" type="text" name="username" autocomplete="off" placeholder="Type your username" required></div>

 		<input class="form-control mb-2" type="password" name="password" autocomplete="new-password"  placeholder="Type your password">

 		<input class="btn btn-primary btn-block mb-2" name="login" type="submit" value="Login">

 	</form>

 	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>"method="POST">
 		<input pattern=".{4,}" title="Username Must Be 4 Chars"class="form-control mb-2" type="text" name="username" autocomplete="off"  placeholder="Type your username" required/>
 		<input minlength="4"class="form-control mb-2" type="password" name="password" autocomplete="new-password"  placeholder="Type a complex password" required/>
 		<input class="form-control mb-2" type="password" name="password2" autocomplete="new-password"  placeholder="Type a password again" required>
 		<input class="form-control mb-2" type="email" name="email"  placeholder="Type your a valid email"required/>
 		<input class="btn btn-success btn-block mb-2" name="signup" type="submit" value="Signup"/>
 	</form>
 </div>
 <div class="the-errors text-center">
 	<?php 
 	if (!empty($formErrors)){
 		foreach ($formErrors as $error) {
 			echo $error . '<br>';
 			
 		}
 	}
 	if(isset($succesMsg)){
 		echo '<div class="msg success">' . $succesMsg . '</div>';
 	}
?>
</div>
 </div>
 <?php 

 



include $tpl . 'footer.php';

ob_end_flush();
 ?>




