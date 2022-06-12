<?php
ob_start();
session_start();
$pageTitle='Items';

if(isset($_SESSION['Username'])){
	
	include 'init.php' ;
$do = isset($_GET['do']) ? $_GET['do'] :'Manage' ;	

if ($do== 'Manage'){

	
     $stmt = $con-> prepare( "SELECT items.*,
                                     categories.Name AS category_name, 
                                     users.Username 
                                     FROM
                                       items
									INNER JOIN
									   categories 
									 ON 
									 categories.ID = items.Cat_ID
									INNER JOIN 
									   users 
									ON 
									users.UserID = items.Member_ID
									 ORDER BY item_ID DESC");
     $stmt->execute();
     $items=$stmt->fetchAll();
     if(! empty($items)){
	?>
     
    
    <h1 class="text-center">Manage Items</h1>
    <div class="container">
    	<div class="table-responsive">
    		<table class="main-table table text-center table-bordered">
    			<tr>
    				<td>#ID</td>
    				<td>Name</td>
    				<td>Description</td>
    				<td>Price</td>
    				<td>Adding Date</td>
    				<td>Category</td>
    				<td>Username</td>
    				<td>Control</td>
    			</tr>
    			<?php
    			foreach ($items as $item) {
    				echo "<tr>";
    				echo "<td>". $item['item_ID'] . "</td>";
    				echo "<td>". $item['Name'] . "</td>";
    				echo "<td>". $item['Description'] . "</td>";
    				echo "<td>". $item['Price'] . "</td>";
    				echo "<td>" . $item['Add_Date'] . "</td>";
    				echo "<td>" . $item['category_name'] . "</td>";
    				echo "<td>" . $item['Username'] . "</td>";
    				echo "<td><a href='items.php?do=Edit&itemid=" . $item['item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
    					<a href='items.php?do=Delete&itemid=" . $item['item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
    					if($item['Approve'] == 0){
    						echo "<a 
    						href='items.php?do=Approve&itemid=" . $item['item_ID'] . "'class='btn btn-info ml-2 activated'>
    						<i class='fa fa-check'></i>Approve</a>";

    					}
    					
    					echo "</td>";
    				echo "</tr>";
    			}
    			?>
    			
    		</table>
    	</div>
    	<a href="items.php?do=Add" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> New item</a>
</div>
 <?php }
 else {
 	echo '<div class="container">';
 	echo '<div class="nice-message"> There is no Items to show</div>';
 	echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> New item</a>';
 	echo '</div';
 } ?>
<?php

	 } elseif($do== 'Add'){?>
		    <h1 class="text-center">Add New Items</h1>
			<div class="container">
				<form class="form-horizontal" action ="?do=Insert" method="POST">
					
					<!-- start Name-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Name</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="name" class="form-control" required="required" placeholder=""/>
						</div>
					</div>
					<!-- start Description-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Description</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="description" class="form-control" required="required" placeholder=""/>
						</div>
					</div>
			       <!-- start price-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Price</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="price" class="form-control" required="required" placeholder=""/>
						</div>
					</div>
					<!-- start country-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Country</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="country" class="form-control" required="required" placeholder=""/>
						</div>
					</div>
					<!-- start Status-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Status</label>
						<div class="col-sm-10 col-md-4">
							<select  name="status">
								<option value="0">...</option>
								<option value="1">New</option>
								<option value="2">Like New</option>
								<option value="3">Used</option>
								<option value="4">Very Old</option>
							</select>
						</div>
					</div>
					<!-- start Member-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Member</label>
						<div class="col-sm-10 col-md-4">
							<select  name="member">
								<option value="0">...</option>
								<?php
								$allMembers =  getAllFro("*", "users","","","UserID");
								
								foreach($allMembers  as $user){
									echo "<option value='" . $user['UserID'] ."'>" .$user['Username'] . "</option>";
								}



								 ?>
							</select>
						</div>
					</div>
					<!-- start Category-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Category</label>
						<div class="col-sm-10 col-md-4">
							<select  name="category">
								<option value="0">...</option>
								<?php
								$allCats =  getAllFro("*", "categories","where parent = 0","","ID");
							
								foreach($allCats as $cat){
									echo "<option value='" . $cat['ID'] ."'>" .$cat['Name'] . "</option>";
									$childCats =  getAllFro("*", "categories","where parent = {$cat['ID']}","","ID");
									foreach($childCats as $child){
										echo "<option value='" . $child['ID'] ."'> --- " .$child['Name'] . "</option>";
									}
								}



								 ?>
							</select>
						</div>
					</div>
					<!-- start tags-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Tags</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="tags" class="form-control" placeholder=""/>
						</div>
					</div>
		
					<!-- start submit-->
					<div class="form-group row">
						
						<div class="offset-sm-4 col-sm-10">
							<input type="submit" value="Add Item" class="btn btn-primary btn-sm"/>
						</div>
					</div>

				</form>
		</div>

<?php }
elseif( $do=='Insert'){
//echo $_POST['username'] . $_POST['password'] . $_POST['email'] . $_POST['full'];
	if($_SERVER['REQUEST_METHOD']=='POST'){
		echo "<h1 class ='text-center'>Insert Item</h1>";
		echo "<div class='container'>";
			
			$name=$_POST['name'];
			$desc=$_POST['description'];
			$price=$_POST['price'];
			$country=$_POST['country'];
            $status=$_POST['status'];
            $member=$_POST['member'];
            $cat=$_POST['category'];
            $tags=$_POST['tags'];
            
			// validate the form
			$formErrors = array();
			if(empty($name)){
				$formErrors[] = '<div class="alert alert-danger">Name can\'t be empty</div>';
			}
			if(empty ($desc)){
				$formErrors[] = '<div class="alert alert-danger"> Description can\'t be empty </div>';
			}
			if(empty ($price)){
				$formErrors[] = '<div class="alert alert-danger"> Price can\'t be empty </div>';
			}
			if(empty ($country)){
				$formErrors[] = '<div class="alert alert-danger"> Country can\'t be empty </div>';
			}
			if($status== 0){
				$formErrors[] = '<div class="alert alert-danger"> you must choose the status </div>';
			}
			if($member== 0){
				$formErrors[] = '<div class="alert alert-danger"> you must choose the member </div>';
			}
			if($cat== 0){
				$formErrors[] = '<div class="alert alert-danger"> you must choose the category </div>';
			}
			foreach($formErrors as $error){
				echo $error . '<br/>';
			}
     if(empty($formErrors)){ 
          
	
          $stmt=$con->prepare("INSERT INTO items(Name,Description,Price,Country_Made,Status,Add_Date,Cat_ID,Member_ID,tags) VALUES(:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember,:ztags)");
          $stmt->execute(array(
            'zname' =>  $name,
            'zdesc' =>  $desc,
            'zprice' =>  $price,
            'zcountry' =>  $country,
            'zstatus' =>  $status,
            'zcat' =>  $cat,
            'zmember' =>  $member,
            'ztags' => $tags
        ));


          
     	
         $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record inserted </div>';
          redirectHome($theMsg,'back');

     
        }

		}else{
			echo '<div class= "container">';

			$theMsg = '<div class="alert alert-danger">sorry you can not browse this page directly</div>'; 
			redirectHome($theMsg);
			echo '</div>';
		}
		echo "</div>";	
		
			
}
elseif ( $do=='Edit')
{ 
	 $itemid= isset($_GET['itemid'])&&is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;


$stmt = $con-> prepare("SELECT * FROM items WHERE item_ID = ? " );
$stmt->execute(array($itemid));

$item= $stmt->fetch();

$count =$stmt->rowCount();

if($count >0){?>

	 <h1 class="text-center">Edit Items</h1>
			<div class="container">
				<form class="form-horizontal" action ="?do=Update" method="POST">
					<input type ="hidden"name ="itemid" value="<?php echo $itemid ?>"/>
					
					<!-- start Name-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Name</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="name" class="form-control" required="required" value="<?php echo $item['Name']?>"placeholder=""/>
						</div>
					</div>
					<!-- start Description-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Description</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="description" class="form-control" required="required" value="<?php echo $item['Description']?>"placeholder=""/>
						</div>
					</div>
			       <!-- start price-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Price</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="price" class="form-control" required="required" value="<?php echo $item['Price']?>" placeholder=""/>
						</div>
					</div>
					<!-- start country-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Country</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="country" class="form-control" required="required" value="<?php echo $item['Country_Made']?>" placeholder=""/>
						</div>
					</div>
					<!-- start Status-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Status</label>
						<div class="col-sm-10 col-md-4">
							<select  name="status">
								<option value="0">...</option>
								<option value="1" <?php if($item['Status']==1){echo'selected';}?> >New</option>
								<option value="2"<?php if($item['Status']==2){echo'selected';}?>>Like New</option>
								<option value="3"<?php if($item['Status']==3){echo'selected';}?>>Used</option>
								<option value="4"<?php if($item['Status']==4){echo'selected';}?>>Very Old</option>
							</select>
						</div>
					</div>
					<!-- start Member-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Member</label>
						<div class="col-sm-10 col-md-4">
							<select  name="member">
								<option value="0">...</option>
								<?php
								$stmt=$con->prepare("SELECT * FROM users");
								$stmt->execute();
								$users= $stmt->fetchAll();
								foreach($users as $user){
									echo "<option value='" . $user['UserID'] ."'"; 
									if($item['Member_ID']==$user['UserID']){echo'selected';} 
									echo ">" .$user['Username'] . "</option>";
								}



								 ?>
							</select>
						</div>
					</div>
					<!-- start Category-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Category</label>
						<div class="col-sm-10 col-md-4">
							<select  name="category">
								<option value="0">...</option>
								<?php
								$stmt2=$con->prepare("SELECT * FROM categories");
								$stmt2->execute();
								$cats= $stmt2->fetchAll();
								foreach($cats as $cat){
									echo "<option value='" . $cat['ID'] ."'";
									if($item['Cat_ID']==$cat['ID']){echo'selected';} 
									echo">" .$cat['Name'] . "</option>";
								}



								 ?>
							</select>
						</div>
					</div>
					<!-- start tags-->
					<div class="form-group row">
						<label class="col-sm-2 control-label text-center offset-sm-2">Tags</label>
						<div class="col-sm-10 col-md-4">
							<input type="text" name="tags" class="form-control" value="<?php echo $item['tags']?>"  placeholder=""/>
						</div>
					</div>
		
					<!-- start submit-->
					<div class="form-group row">
						
						<div class="offset-sm-4 col-sm-10">
							<input type="submit" value="Save Item" class="btn btn-primary btn-sm"/>
						</div>
					</div>

				</form>
				<?php
				$stmt = $con-> prepare( "SELECT comments.*, users.Username AS Member FROM comments 
     	
     	INNER JOIN 
     	users 
     	ON users.UserID = comments.user_id WHERE item_id = ? ");
     $stmt->execute(array($itemid));
     $rows=$stmt->fetchAll();

      if(! empty($rows)){
	?>
     
    
        <h1 class="text-center">Manage [<?php echo $item['Name']?>] Comments</h1>
   
    	<div class="table-responsive">
    		<table class="main-table table text-center table-bordered">
    			<tr>
    			
    				<td>Comment</td>
    				
    				<td>User Name</td>
    				<td>Added Date</td>
    				<td>Control</td>
    			</tr>
    			<?php
    			foreach ($rows as $row) {
    				echo "<tr>";
    				
    				echo "<td>". $row['comment'] . "</td>";
    				
    				echo "<td>". $row['Member'] . "</td>";
    				echo "<td>" .$row['comment_date'] . "</td>";
    				echo "<td><a href='comments.php?do=Edit&comid=" . $row['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
    					<a href='comments.php?do=Delete&comid=" . $row['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>";
    					if($row['status'] == 0){
    						echo "<a href='comments.php?do=Approve&comid=" . $row['c_id'] . "'class='btn btn-info ml-2 activated'><i class='fa fa-check'></i>Approve</a>";

    					}
    					echo "</td>";
    				echo "</tr>";
    			}
    			?>
    			
    		</table>
    	</div>
    <?php } ?>
    	


		</div>


	
<?php
 }	else{
	echo '<div class"container">';
	$theMsg = '<div class="alert alert-danger">There is no such ID</div>';
	 redirectHome($theMsg);
	echo '</div>';
}

	}elseif ($do=='Update') {
echo "<h1 class ='text-center'>Update Item</h1>";
		echo "<div class='container'>";
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$id=$_POST['itemid'];
			$name=$_POST['name'];
			$desc=$_POST['description'];
			$price=$_POST['price'];
			$country=$_POST['country'];
			$status=$_POST['status'];
			$cat=$_POST['category'];
			$member=$_POST['member'];
			 $tags=$_POST['tags'];


          
			// validate the form
			$formErrors = array();
			if(empty($name)){
				$formErrors[] = '<div class="alert alert-danger">Name can\'t be empty</div>';
			}
			if(empty ($desc)){
				$formErrors[] = '<div class="alert alert-danger"> Description can\'t be empty </div>';
			}
			if(empty ($price)){
				$formErrors[] = '<div class="alert alert-danger"> Price can\'t be empty </div>';
			}
			if(empty ($country)){
				$formErrors[] = '<div class="alert alert-danger"> Country can\'t be empty </div>';
			}
			if($status== 0){
				$formErrors[] = '<div class="alert alert-danger"> you must choose the status </div>';
			}
			if($member== 0){
				$formErrors[] = '<div class="alert alert-danger"> you must choose the member </div>';
			}
			if($cat== 0){
				$formErrors[] = '<div class="alert alert-danger"> you must choose the category </div>';
			}
			foreach($formErrors as $error){
				echo $error . '<br/>';
			}
     if(empty($formErrors)){ 

     	 $stmt=$con->prepare("UPDATE items SET Name=?, Description=?, Price=? ,Country_Made=? ,Status=? ,Cat_ID=?,Member_ID=?, tags =? WHERE item_ID=?");
         $stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$tags,$id));
         
         $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Updated </div>';
         redirectHome($theMsg,'back');

     }
        

		}else{
			$theMsg = '<div class="alert alert-danger">sorry you can not browse this page directly</div>'; 
			 redirectHome($theMsg);
		}
		echo "</div>";
		
	}

	elseif ($do == 'Delete'){

	echo "<h1 class ='text-center'>Delete Item</h1>";
		echo "<div class='container'>";

		$itemid= isset($_GET['itemid'])&&is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check= checkItem("item_ID", "items", $itemid);
     
        if($check > 0) {

        	$stmt=$con->prepare("DELETE FROM items WHERE item_ID = :zitem");
        	$stmt->bindParam(":zitem", $itemid);
        	$stmt->execute();
        	 $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Deleted </div>';
        	 redirectHome($theMsg,'back');

        	}
             


        	else{$theMsg ='<div class="alert alert-danger">This ID is not exist</div>';
        	redirectHome($theMsg);
        }

        	echo "</div>";
	

	} elseif ($do == "Approve") {

		echo "<h1 class ='text-center'>Approve Item</h1>";
		echo "<div class='container'>";

		$itemid= isset($_GET['itemid'])&&is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check= checkItem("item_ID", "items", $itemid);
     
        if($check > 0) {

        	$stmt=$con->prepare("UPDATE items SET Approve = 1 WHERE item_ID = ?");
        	
        	$stmt->execute(array($itemid));
        	 $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Approved</div>';
        	 redirectHome($theMsg,'back');

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