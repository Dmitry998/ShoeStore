<?php
if(isset($_GET['array']))
{
    $mysqli = new mysqli('localhost','root','12345678','Shoe_store');
    $idShoes = $_GET['array'];
    foreach($idShoes as $idSh)
    {

        $stmt = $mysqli->prepare("DELETE FROM shoes WHERE ID=?");
        $stmt->bind_param('i',$idSh);
        $stmt->execute();

        $stmt2 = $mysqli->prepare("DELETE FROM basket WHERE idShoe=?");
        $stmt2->bind_param('i',$idSh);
        $stmt2->execute();  


    }       
}
include 'basket.php';
?>