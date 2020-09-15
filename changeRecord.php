<?php
if(isset($_POST['name']) && isset($_POST['size']) && isset($_POST['color']) && isset($_POST['id']) && isset($_POST['brand']) && isset($_POST['price']) && isset($_POST['oldPhoto']))
{

	$photo =$_POST['oldPhoto'];
    if($_FILES['userfile']['tmp_name'])
    {
		$uploadfile = "Files/".basename($_FILES['userfile']['name']);
		if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
		{
            if(getimagesize($uploadfile))
            {
                $photo = $uploadfile; // новое фото
            }
            else
            {
                $photo = $_POST['oldPhoto'];
                unlink($uploadfile);
            }
		}
    }
	$name = $_POST['name'];
	$size = $_POST['size'];
	$color = $_POST['color'];
    $id = $_POST['id'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
	$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
	$stmt = $mysqli->prepare("UPDATE shoes SET name=?, size=?, color=?, brand=?, price=?, photo=? WHERE ID=?");
	$stmt->bind_param('sissisi',$name,$size,$color,$brand,$price,$photo,$id);
	$stmt->execute();
}
include 'catalogShoes.php';
?>