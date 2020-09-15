<?php
	global $admin;
	$admin = false;
	global $login;
	$login =null;

session_start();
if(isset($_SESSION['userid']))
{
	$userid = $_SESSION['userid'];
	$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
	$res = $mysqli->query("SELECT login, admin From users WHERE id='$userid'");
	$user = $res->fetch_object();
	$login = $user->login;
	$adm = $user->admin;
	if($adm =='yes')
		$admin=true;
	else
		$admin=false;			
}


if(isset($_POST['id']) && isset($_POST['photo']))
{
	$id = $_POST['id'];
	$photo = $_POST['photo'];
	if($photo!="")
		unlink($photo);
	$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
	$stmt = $mysqli->prepare("DELETE FROM shoes WHERE ID=?");
	$stmt->bind_param('i',$id);
    $stmt->execute();
    

    /// удаление из корзины
    $stmt2 = $mysqli->prepare("DELETE FROM basket WHERE idShoe=?");
	$stmt2->bind_param('i',$id);
    $stmt2->execute();

}

$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
$res = $mysqli->query('SELECT * From shoes');
echo "<table><tr><th>Фото</th><th>Название</th><th>Размер</th><th>Цвет</th><th>Фирма</th><th>Цена</th>";
    if($admin)
    {
        echo"<th>Редактировать</th><th>Удалить</th>";
    }
    else
    {
        if($login!=null)
            echo"<th>Купить</th>";
    }
    echo '</tr>';
    while ($shoe =  $res->fetch_object())
    {
        $id = $shoe->ID;
        $photo = $shoe->photo;
        echo "<tr>";
        if($photo=="")
        {
            echo "<td><img src='Files/noPhoto.jpg' alt = 'нет фото' width='200' height='200'></img></td>";
        }
        else
            echo "<td><img src='$photo' alt = 'обувь' width='200' height='200'></img></td>";
        $photo_str = json_encode($photo);
        echo "<td>$shoe->name</td>
        <td>$shoe->size</td>
        <td>$shoe->color</td>
        <td>$shoe->brand</td>
        <td>$shoe->price руб</td>";
        if($admin)
        {
        echo"<td> <a id='buttonA' href='loadChangeRecord.php?id=$id'></a> </td>
        <td><button onclick='ajaxRequestDeleteRecord($id,$photo_str)'></button></td>";
        }
        else
        {
            if($login!=null)
                echo"<td><button onclick='ajaxRequestDeleteRecord($id,$photo_str)'>Купить</button></td>";
        }
    }
    echo "</tr></table>";
?>