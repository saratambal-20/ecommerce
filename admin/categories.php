<?php
ob_start();
session_start();
$pageTitle='Categories';

if(isset($_SESSION['Username'])){
	
	include 'init.php' ;
$do = isset($_GET['do']) ? $_GET['do'] :'Manage' ;	

if ($do== 'Manage'){
	$Sort = 'ASC';
	$Sort_array = array('ASC','DESC' );
	if(isset($_GET['sort'])&& in_array($_GET['sort'], $Sort_array)){
		$Sort= $_GET['sort'];

	}
$stmt2= $con->prepare("SELECT * FROM categories WHERE parent=0 ORDER BY Ordering $Sort");
$stmt2->execute();
$cats = $stmt2->fetchALL();
?>
<h1 class="text-center">Manage Categories</h1>

<div class="container categories">
	<div class="card">
		<div class="card-header"><i class='fa fa-edit'></i> Manage Categories
			<div class="option pull-right"><i class='fa fa-sort'></i> Ordering:[ 
				<a class="<?php if($Sort=='ASC'){echo 'active';}?>" href="?sort=ASC">ASC</a> |
				<a class="<?php if($Sort=='DESC'){echo 'active';}?>" href="?sort=DESC">DESC</a> ]
				<i class='fa fa-eye'></i> View:[ <span class="active" data-view="full">Full</span > | <span data-view="classic">Classic</span> ]
			</div>
				
			</div>
		<div class="card-body">
			<?php
             foreach ($cats as $cat) {
             	echo "<div class='cat'>";
             	echo "<div class='hidden-buttons'>";
             	echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class ='btn btn-xs btn-primary'><i class='fa fa-edit'></i>Edit</a>";
             	echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] ."' class ='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i>Delete</a>";
             	echo "</div>";
             	echo "<h3>" . $cat['Name'] . "</h3>" ;
             	echo "<div class='full-view'>";
             	echo "<p>";
             	 if( $cat['Description']==''){echo 'This is empty';} else {echo $cat['Description'];}
             	echo"</p>";
             	 if( $cat['Visibility']==1){echo"<span class='visiblity'><i class='fa fa-eye'></i> Hidden</span>";}
             	 if( $cat['Allow_Comment']==1){echo"<span class='commenting'><i class='fa fa-close'></i> Comment Disable</span>";}
             	 if( $cat['Allow_Ads']==1){echo"<span class='advertises'><i class='fa fa-close'></i> Ads Disable</span>";}
             	 echo "</div>";


                $childCats= getAllFro("*", "categories", "WHERE parent = {$cat['ID']}", "", "ID", "ASC");
                if(! empty($childCats)){
                echo "<h4 class='child-head'>Child Categories</h4>";
                echo "<ul class='list-unstyled child-cats'>";
                foreach ($childCats as $c) {
                  echo "<li class='child-link'><a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a> 
                  <a href='categories.php?do=Delete&catid=" . $c['ID'] ."' class ='show-delete confirm'>Delete</a></li>";

                 }
                 echo"</ul>";


             } 
             	 echo "</div>";
               
                 echo "<hr>";
             }
			 ?>
		</div>
	</div>
	<a class="add-category btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus"></i> Add New Category</a>
</div>
<?php
	 } elseif($do== 'Add'){?>
	 	<h1 class="text-center">Add New Category</h1>
<div class="container">
	<form class="form-horizontal" action ="?do=Insert" method="POST">
		
		<!-- start Name-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Name</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder=""/>
			</div>
		</div>
        <!-- start description-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Description</label>
			<div class="col-sm-10 col-md-4">
				
				<input type="text" name="description" class="form-control" placeholder="" />
				
			</div>
		</div>
         <!-- start ordering field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Ordering</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="ordering" class="form-control" placeholder=""/>
			</div>
		</div>
		 <!-- start category type field-->
		 <div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Parent?</label>
			<div class="col-sm-10 col-md-4">
				<select name="parent">
					<option value="0">None</option>
					<?php 
					$allCats =  getAllFro("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
					foreach ($allCats as $cat) {
						echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
					}

					?>
				</select>
			</div>
		</div>
		<!-- start visiblity field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Visible</label>
			<div class="col-sm-10 col-md-4">
				<div>
					<input id= "vis-yes"type="radio" name="visibility"  value="0" checked/>
					<label for="vis-yes"> Yes</label>
				</div>
				<div>
					<input id= "vis-no" type="radio" name="visibility"  value="1" />
					<label for="vis-no"> No </label>
				</div>
			</div>
		</div>
		<!-- start commenting field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Allow Commenting</label>
			<div class="col-sm-10 col-md-4">
				<div>
					<input id= "com-yes"type="radio" name="commenting"  value="0" checked/>
					<label for="com-yes"> Yes</label>
				</div>
				<div>
					<input id= "com-no" type="radio" name="commenting"  value="1" />
					<label for="com-no"> No </label>
				</div>
			</div>
		</div>
		<!-- start Ads field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Allow Ads</label>
			<div class="col-sm-10 col-md-4">
				<div>
					<input id= "ads-yes"type="radio" name="ads"  value="0" checked/>
					<label for="ads-yes"> Yes</label>
				</div>
				<div>
					<input id= "ads-no" type="radio" name="ads"  value="1" />
					<label for="ads-no"> No </label>
				</div>
			</div>
		</div>
		<!-- start submit-->
		<div class="form-group row">
			
			<div class="offset-sm-4 col-sm-10">
				<input type="submit" value="Add Category" class="btn btn-primary"/>
			</div>
		</div>

	</form>
</div>

<?php }
elseif( $do=='Insert'){
	if($_SERVER['REQUEST_METHOD']=='POST'){
		echo "<h1 class ='text-center'>Insert Category</h1>";
		echo "<div class='container'>";
			
			$name=$_POST['name'];
			$desc=$_POST['description'];
			$parent=$_POST['parent'];
			$order=$_POST['ordering'];
			$visible=$_POST['visibility'];
           $comment= $_POST['commenting'];
            $ads= $_POST['ads'];


     
			// validate the form
			
     
          
	$check= checkItem("Name", "categories",$name);
	if($check==1){
		$theMsg= '<div class="alert alert-danger"> sorry this Category is exist</div>'; 
		 redirectHome($theMsg,'back');

	}else{
          $stmt=$con->prepare("INSERT INTO categories(Name,Description,parent,Ordering,Visibility,Allow_Comment,Allow_Ads) VALUES(:zname,:zdesc,:zparent,:zorder,:zvisible, :zcomment,:zads)");
          $stmt->execute(array(
            'zname' =>  $name,
            'zdesc' =>  $desc,
            'zparent' => $parent,
            'zorder' =>  $order,
            'zvisible' =>  $visible,
            'zcomment' =>  $comment,
            'zads' =>  $ads));




          
     	
         $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record inserted </div>';
          redirectHome($theMsg,'back');

     }
        

		} else{
			echo '<div class= "container">';

			$theMsg = '<div class="alert alert-danger">sorry you can not browse this page directly</div>'; 
			redirectHome($theMsg,'back');
			echo '</div>';
		}
		echo "</div>";	
}


		
			

elseif ( $do=='Edit')
{ $catid= isset($_GET['catid'])&&is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;


$stmt = $con-> prepare("SELECT * FROM categories WHERE ID = ?" );
$stmt->execute(array($catid));
$cat= $stmt->fetch();
$count =$stmt->rowCount();
if($count >0){?>
	
<h1 class="text-center">Edit Category</h1>
<div class="container">
	<form class="form-horizontal" action ="?do=Update" method="POST">
		<input type ="hidden"name ="catid" value="<?php echo $catid ?>"/>
		
		<!-- start Name-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Name</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="name" class="form-control" value="<?php echo $cat['Name'] ?>"required="required" placeholder=""/>
			</div>
		</div>
        <!-- start description-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Description</label>
			<div class="col-sm-10 col-md-4">
				
				<input type="text" name="description" class="form-control" placeholder="" value="<?php echo $cat['Description'] ?>" />
				
			</div>
		</div>
         <!-- start ordering field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Ordering</label>
			<div class="col-sm-10 col-md-4">
				<input type="text" name="ordering" class="form-control" value="<?php echo $cat['Ordering'] ?>"placeholder=""/>
			</div>
		</div>
		<!-- start category type field-->
		 <div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Parent?</label>
			<div class="col-sm-10 col-md-4">
				<select name="parent">
					<option value="0">None</option>
					<?php 
					$allCats =  getAllFro("*", "categories", "WHERE parent = 0", "", "ID", "ASC");
					foreach ($allCats as $c) {
						echo "<option value='" . $c['ID'] . "'";
						if ($cat['parent']== $c['ID']){echo 'selected';}
						echo ">" . $c['Name'] . "</option>";
					}

					?>
				</select>
			</div>
		</div>
		<!-- start visiblity field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Visible</label>
			<div class="col-sm-10 col-md-4">
				<div>
					<input id= "vis-yes"type="radio" name="visibility"  value="0"  <?php if($cat['Visibility']== 0){ echo 'checked';}?> />
					<label for="vis-yes"> Yes</label>
				</div>
				<div>
					<input id= "vis-no" type="radio" name="visibility"  value="1"  <?php if($cat['Visibility']== 1){ echo 'checked';}?> />
					<label for="vis-no"> No </label>
				</div>
			</div>
		</div>
		<!-- start commenting field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Allow Commenting</label>
			<div class="col-sm-10 col-md-4">
				<div>
					<input id= "com-yes"type="radio" name="commenting"  value="0" <?php if($cat['Allow_Comment']== 0){ echo 'checked';}?>/>
					<label for="com-yes"> Yes</label>
				</div>
				<div>
					<input id= "com-no" type="radio" name="commenting"  value="1" <?php if($cat['Allow_Comment']== 1){ echo 'checked';}?>/>
					<label for="com-no"> No </label>
				</div>
			</div>
		</div>
		<!-- start Ads field-->
		<div class="form-group row">
			<label class="col-sm-2 control-label text-center offset-sm-2">Allow Ads</label>
			<div class="col-sm-10 col-md-4">
				<div>
					<input id= "ads-yes"type="radio" name="ads"  value="0" <?php if($cat['Allow_Ads']== 0){ echo 'checked';}?> />
					<label for="ads-yes"> Yes</label>
				</div>
				<div>
					<input id= "ads-no" type="radio" name="ads"  value="1" <?php if($cat['Allow_Ads']== 1){ echo 'checked';}?>/>
					<label for="ads-no"> No </label>
				</div>
			</div>
		</div>
		<!-- start submit-->
		<div class="form-group row">
			
			<div class="offset-sm-4 col-sm-10">
				<input type="submit" value="Save" class="btn btn-primary"/>
			</div>
		</div>

	</form>
</div>
	
<?php }	else{
	echo '<div class"container">';
	$theMsg = '<div class="alert alert-danger">There is no such ID</div>';
	 redirectHome($theMsg);
	echo '</div';
}

	 	

	}elseif ($do=='Update') {

	echo "<h1 class ='text-center'>Update Category</h1>";
		echo "<div class='container'>";
		if($_SERVER['REQUEST_METHOD']=='POST'){
			$id=$_POST['catid'];
			$name=$_POST['name'];
			$desc=$_POST['description'];

			$order=$_POST['ordering'];
			$parent=$_POST['parent'];
            $visible=$_POST['visibility'];
            $comment=$_POST['commenting'];
            $ads=$_POST['ads'];



     	 $stmt=$con->prepare("UPDATE categories SET Name=?, Description=?, Ordering=? ,parent = ?, Visibility=? ,Allow_Comment=? ,Allow_Ads=?
     	     WHERE ID=?");
         $stmt->execute(array($name,$desc,$order,$parent,$visible,$comment,$ads, $id));
         
         $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Updated </div>';
         redirectHome($theMsg,'back');

     
        

		}else{
			$theMsg = '<div class="alert alert-danger">sorry you can not browse this page directly</div>'; 
			 redirectHome($theMsg);
		}
		echo "</div>";	
	}

	elseif ($do == 'Delete'){
      echo "<h1 class ='text-center'>Delete Category</h1>";
		echo "<div class='container'>";

		$catid= isset($_GET['catid'])&&is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        $check= checkItem("ID", "categories", $catid);
     
        if($check > 0) {

        	$stmt=$con->prepare("DELETE FROM categories WHERE ID = :zid");
        	$stmt->bindParam(":zid", $catid);
        	$stmt->execute();
        	 $theMsg = '<div class="alert alert-success">' . $stmt->rowCount() . 'Record Deleted </div>';
        	 redirectHome($theMsg,'back');

        	}
             


        	else{$theMsg ='<div class="alert alert-danger">This ID is not exist</div>';
        	redirectHome($theMsg);
        }

        	echo "</div>";

		} 

	include $tpl .'footer.php';
} else{
	
	header('Location:index.php');
	exit();
}

ob_end_flush();

?>