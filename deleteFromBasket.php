<?php
$userid="";
if(isset($_GET['idUser']) && isset($_GET['idShoe']))
{
    $userid = $_GET['idUser'];
    $shoeid = $_GET['idShoe'];
    $mysqli = new mysqli('localhost','root','12345678','Shoe_store');

        $stmt = $mysqli->prepare("DELETE FROM basket WHERE idShoe=? AND idUser=?"); 
        $stmt->bind_param('ii',$shoeid,$userid);
        $stmt->execute();
        echo '<div style="color: green;"> Товар удален из корзины.</div><hr>';
}
include 'basket.php';
?>