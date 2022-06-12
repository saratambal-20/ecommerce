<?php

$do='';
if(isset($_GET['do'])){
	$do =$_GET['do'];
}else{
	$do='Manage';
}
if($do=='Manage'){
	echo 'welcome to your page';
	echo '<a href="page.php?do=Add">add new category + </a>';
}
elseif($do=='Add'){
	echo 'you are in add';
}

elseif($do=='Insert'){
	echo 'you are in Insert page';
}


else{
	echo 'error';
}