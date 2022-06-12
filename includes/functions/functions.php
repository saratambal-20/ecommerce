<?php

function getAllFro($field, $table,$where=Null,$and=NULL,$orderfield,$ordering = "DESC"){
  global $con;
	
	$getAll= $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;
}

function getAllFrom($tableName,$orderBy,$where=NULL){
	global $con;
	if($where == NULL){
		$sql= NULL;
	} else {
		$sql= $where;
	}
	$getAll= $con->prepare("SELECT * FROM $tableName $sql ORDER BY $orderBy DESC");
    $getAll->execute();
    $all = $getAll->fetchAll();
    return $all;

}


function getCat(){
	global $con;
	$getCat = $con->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY ID ASC");
    $getCat->execute();
    $cats = $getCat->fetchAll();
    return $cats;

}

function getItems($Where,$value,$approve = NULL){
	global $con;
	if($approve == NULL){
		$sql= 'AND Approve = 1';
	} else {
		$sql= NULL;
	}
	$getItems = $con->prepare("SELECT * FROM items WHERE $Where = ? $sql ORDER BY item_ID DESC");
    $getItems->execute(array($value));
    $items = $getItems->fetchAll();
    return $items;

}
function chechUserStatus($user){
	global $con;
	$stmtX = $con-> prepare("SELECT Username,RegStatus From users WHERE Username = ? AND RegStatus =0");
	$stmtX->execute(array($user));
	$status =$stmtX->rowCount();
	return $status;
}

function getTitle(){
	global $pageTitle;
	if(isset($pageTitle)){
		echo $pageTitle;
	}else{
		echo 'Default';
	}
}
 function redirectHome($theMsg, $url = null, $seconds =3) {
 	if($url === null) {
 		$url = 'index.php';
 		$link = 'Homepage';
 	} else{

 		if(isset($_SERVER['HTTP_REFERER'])&& $_SERVER['HTTP_REFERER'] !==''){
 			$url= $_SERVER['HTTP_REFERER'];
 			$link = 'Previous page';
 		}else{
 			$url='index.php';
 			$link = 'Homepage';
 		}
 		
 	}
 	echo $theMsg;
 	echo "<div class='alert alert-info'>you will be redirected to $link after $seconds seconds.</div>";
 	header("refresh:$seconds;url=$url");
 	exit();
 }

 function checkItem( $select, $from, $value){
 	global $con;
 	$statement=$con->prepare("SELECT $select FROM $from WHERE $select= ?");
    $statement->execute(array($value));
    $count=$statement->rowcount();
    return $count;

 }
function countItems($item, $table){
	global $con;
	$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
	$stmt2->execute();
	return $stmt2->fetchColumn();
}
function getLatest($select,  $table, $order, $limit=5){
	global $con;
	$getStmt = $con->prepare("SELECT $select FROM  $table ORDER BY $order DESC LIMIT $limit");
    $getStmt->execute();
    $rows = $getStmt->fetchAll();
    return $rows;

}