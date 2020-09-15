<?php

if(isset($_GET['idUser']) && isset($_GET['idShoe']))
{
    $userid = $_GET['idUser'];
    $shoeid = $_GET['idShoe'];
    $mysqli = new mysqli('localhost','root','12345678','Shoe_store');

    $res = $mysqli->query("SELECT * From basket WHERE idShoe=$shoeid AND idUser=$userid");
    $shoe = $res->fetch_object();
    if($shoe==null)
    {
        $stmt = $mysqli->prepare("INSERT INTO basket(idShoe,idUser) VALUES (?,?)"); 
        $stmt->bind_param('ii',$shoeid,$userid);
        $stmt->execute();
        echo '<div style="color: green;"> Товар добавлен в корзину.</div><hr>';
    }
    else
    {
        echo '<div style="color: red;"> Вы уже добавили этот товар в корзину.</div><hr>';
    }
}
include 'catalogShoes.php';

?>