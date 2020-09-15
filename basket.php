<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title> Магазин обуви </title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php echo "<h2>$login<h2>"?>
	<div class="header">
		<a class="logo" href="index.php"><img src="lily-logo2.png" alt=""></a>
		<div class="top-menu">
		<ul>
			<li><a href="index.php">Главная</a></li>
			<li><a href="catalogShoes.php">Каталог обуви</a></li>
		<div class="auth">
			<?php
			if(isset($_SESSION['userid']))
			{
				$userid = $_SESSION['userid'];
				$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
				
				$res = $mysqli->query("SELECT login From users WHERE id='$userid'");
				$user = $res->fetch_object();
				$login = $user->login;

				echo "<li><a href=''>Вы вошли как $login</a></li>
				<li><a href='exit.php'>Выход</a></li>"; 
			}
			else
			{
				echo"<li><a href='authForm.php'>Вход</a></li>
				<li><a href='registrationForm.php'>Регистрация</a></li>";
			}
			?>
		</ul>
		</div>
		</div>
	</div>
	<div class="content">
    <?php
    
    if(isset($_SESSION['userid']))
    {
        $userid = $_SESSION['userid'];
        $mysqli = new mysqli('localhost','root','12345678','Shoe_store');
        $idShoes.array();
        $idShoes=null;
        $res = $mysqli->query("SELECT * From basket WHERE idUser=$userid");
        while($shoe = $res->fetch_object())
        {
            $idShoes[]=$shoe->idShoe;
        }
        $sumPrice=0;
        if(count($idShoes)>0)
        {
            echo '<h2>Товары в вашей корзине <h2>';
            echo "<div id=ajaxDiv>";
            echo "<table><tr><th>Фото</th><th>Название</th><th>Размер</th><th>Цвет</th><th>Фирма</th><th>Цена</th><th>Удалить</th>";
            echo "<tr>";
            foreach($idShoes as $idSh)
            {

                $res = $mysqli->query("SELECT * From shoes WHERE ID=$idSh");
                $shoe = $res->fetch_object();
                if($shoe!=null)
                {
                    $id = $shoe->ID;
                    $photo = $shoe->photo;
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
                    <td>$shoe->price руб</td>
                    <td><a id='buttonA' href='deleteFromBasket.php?idUser=$userid&idShoe=$id'></a></td>";
                    $sumPrice+=$shoe->price;
                    echo "</tr>";
                }

            }   
            echo "</table></div>";
            echo "Общая сумма составила $sumPrice рублей ";
            $i=0;
            $str="array[]=";
            foreach($idShoes as $id)
            {
                if($i==0)
                    $str.=$id;
                else
                {
                    $idstr = "&array[]=$id";
                    $str.= $idstr;
                }
                $i++;
            }
            echo "<a href='buy.php?$str'> Оплатить </a>";
        }
        else
            echo '<h2>Корзина пока пуста</h2>';
    }

    ?>
	</div>
	<div class="footer">
			8(960)319-71-39 г. Заречный Пензенская область<br>
			Горячая линия
	</div>
</body>
</html>