<?php
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
	$res = $mysqli->query("SELECT * From shoes WHERE ID=$id");
	while ($shoe = $res->fetch_object())
	{
	$id = $shoe->ID;
	$name = $shoe->name;
	$size = $shoe->size;
	$color = $shoe->color;	
	$brand = $shoe->brand;	
	$price = $shoe->price;	
	$photo =$shoe->photo;
	}
	Header("Location:catalogShoes.php?id=$id&name=$name&size=$size&color=$color&brand=$brand&price=$price&photo=$photo");
}
else
{
	include 'catalogShoes.php';
}
?>