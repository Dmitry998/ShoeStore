<?php
if(isset($_POST['name']) && isset($_POST['size']) && isset($_POST['color']) && isset($_POST['price']) && isset($_POST['brand']))
{
    $photo ="";
    if($_FILES['userfile']['tmp_name'])
    {
		$uploadfile = "Files/".basename($_FILES['userfile']['name']);
		if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{
            if(getimagesize($uploadfile))
            {
                $photo = $uploadfile;
            }
            else
            {
                $photo ="";
                unlink($uploadfile);
            }
		}
    }
	$name = $_POST['name'];
	$size = $_POST['size'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $brand = $_POST['brand'];
	$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
	$stmt = $mysqli->prepare("INSERT INTO shoes(name,size,color,price,brand,photo) VALUES (?,?,?,?,?,?)"); 
	$stmt->bind_param('sisiss',$name,$size,$color,$price,$brand,$photo);
	$stmt->execute();
}
include 'catalogShoes.php';
?>