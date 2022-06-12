<?php
session_start();

$pageTitle= 'Create New items';

include'init.php';
if(isset($_SESSION['user'])){
	

 if($_SERVER['REQUEST_METHOD']== 'POST'){
 	$formErrors = array();
 	$name     = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
 	$desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
 	$price    = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
 	$country  = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
 	$status   = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT); 	
 	$category = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
 	$tags     = filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
 
       if(strlen($name) < 4) {
    		$formErrors[] = 'Item Title must be larger than 4 characters';
    	}
    	if(strlen($desc) < 10){
    		$formErrors[] = 'Item description must be at least 10 characters';
    	}
    	if(strlen($country) < 2){
    		$formErrors[] = 'Country must be larger than 2 characters';
    	}
    	if(empty($price)){
    		$formErrors[] = 'Price must be not empty';
    	}
    	if(empty($status)){
    		$formErrors[] = 'Status must be not empty';
    	}
    	if(empty($category)){
    		$formErrors[] = 'Category must be not empty';
    	}
    	 if(empty($formErrors)){ 
          
	
          $stmt=$con->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags) VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:ztags)");
          $stmt->execute(array(
            'zname' =>  $name,
            'zdesc' =>  $desc,
            'zprice' =>  $price,
            'zcountry' =>  $country,
            'zstatus' =>  $status,
            'zcat' =>  $category,
            'zmember' =>  $_SESSION['uid'],
            'ztags' =>  $tags,

        ));


        if($stmt)  {
     	 $succesMsg ='Item Has been Added';
     }
        }


    }
?>
<h1 class="text-center"><?php echo $pageTitle ?>

</h1>
<div class="create-ad block">
	<div class="container">
		<div class="card">
			<div class="card-header"><?php echo $pageTitle ?></div>
			<div class="card-body">
				<div class="row">
					<div class="col-md-8">
						<form class="form-horizontal main-form" action ="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
					
					<!-- start Name-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center ">Name</label>
						<div class="col-sm-10 col-md-8">
							<input pattern=".{4,}" title="This Field Require at Least 4 Characters" type="text" name="name" class="form-control live" required="required" placeholder="" data-class=".live-title" />
						</div>
					</div>
					<!-- start Description-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center ">Description</label>
						<div class="col-sm-10 col-md-8">
							<input pattern=".{10,}" title="This Field Require at Least 10 Characters" type="text" name="description" class="form-control live" required="required" placeholder="" data-class=".live-desc"/>
						</div>
					</div>
			       <!-- start price-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center ">Price</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="price" class="form-control live" required="required" placeholder="" data-class=".live-price"/>
						</div>
					</div>
					<!-- start country-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center ">Country</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="country" class="form-control" required="required" placeholder=""/>
						</div>
					</div>
					<!-- start Status-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center ">Status</label>
						<div class="col-sm-10 col-md-8">
							<select  name="status" required>
								<option value="0">...</option>
								<option value="1">New</option>
								<option value="2">Like New</option>
								<option value="3">Used</option>
								<option value="4">Very Old</option>
							</select>
						</div>
					</div>
					
					
					<!-- start Category-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center ">Category</label>
						<div class="col-sm-10 col-md-8">
							<select  name="category" required>
								<option value="0">...</option>
								<?php
								$cats = getAllFrom('categories','ID');
								
								foreach($cats as $cat){
									echo "<option value='" . $cat['ID'] ."'>" .$cat['Name'] . "</option>";
								}



								 ?>
							</select>
						</div>
					</div>
							<!-- start tags-->
					<div class="form-group row">
						<label class="col-sm-2 control-label">Tags</label>
						<div class="col-sm-10 col-md-8">
							<input type="text" name="tags" class="form-control" placeholder=""/>
						</div>
					</div>
		
					<!-- start submit-->
					<div class="form-group row">
						
						<div class="offset-sm-2 col-sm-10">
							<input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
						</div>
					</div>

				</form>
					</div>
					<div class="col-md-4">
							<div class="img-thumbnail item-box live-preview">
						    <span class ="price-tag ">$<span class="live-price"></span></span>
						    <img class= "img-fluid"src="1.jpg" alt="" />
						    <div class="caption">
						       <h3 class="live-title"> Title</h3>
						       <p class="live-desc">Description </p>
						    </div>
						  </div>
						</div>
					</div>
				<!--start looping through errors-->
				<?php
                 if(! empty($formErrors)){
                 	foreach($formErrors as $error){
                 		echo '<div class="alert alert-danger">' . $error . '</div>';
                 	}
                 }
                 	if(isset($succesMsg)){
 		              echo '<div class="alert alert-success">' . $succesMsg . '</div>';
 	               }


				?>
				<!--end looping through errors-->


			</div>
		</div>
	</div>
</div>


<?php
} else{
	header('Location: login.php');
	exit();
}
include $tpl .'footer.php'; 

?>