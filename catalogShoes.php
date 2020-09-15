<?php session_start();
	global $admin;
	$admin = false;
	global $login;
	$login =null;

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8">
	<title> Магазин обуви </title>
	<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
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
				$res = $mysqli->query("SELECT login, admin From users WHERE id='$userid'");
				$user = $res->fetch_object();
				$login = $user->login;
				$adm = $user->admin;
				if($adm =='yes')
					$admin=true;
				else
					$admin=false;
				echo "<li><a href='basket.php'>Вы вошли как $login </a></li>
				<li><a href='exit.php'>Выход</a></li>"; /// НАДО УБРАТЬ ЕСЛИ КОРЗИНУ
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
	<div class='formSearch'>
	Поиск по сайту
	<input type='text' id='search'>
	<button onclick="delSelect(document.body)"> Очистить </button>
	<button onclick="addSelect(document.body,document.getElementById('search').value)"> Найти </button>
	</div>
	</div>
	<div class="content">
	<div class="formInput">
		<p>Укажите максимальную цену <input type="text" id="maxPrice" value=""></p>
		<p>Укажите размер <input type="text" id="size" value=""></p>
		<button onclick="ajaxRequest('viewRecordsWithLessPrice.php',document.getElementById('maxPrice').value,document.getElementById('size').value)">Показать записи</button>
	</div>
	<?php
	if(isset($_GET['id']) && isset($_GET['name'])&& isset($_GET['size']) && isset($_GET['color']) && isset($_GET['brand']) && isset($_GET['price']) && isset($_GET['photo']))
	{
		$id = $_GET['id'];
		$name = $_GET['name'];
		$size = $_GET['size'];
		$color = $_GET['color'];
		$brand = $_GET['brand'];
		$price = $_GET['price'];
		$oldPhoto = $_GET['photo'];
		echo "<h2> Изменить товар </h2><div class= formInput>
		<form action='changeRecord.php' method ='post' enctype='multipart/form-data'> 
		<p>Заменить изображение<input type='file' class='chooseFile' name='userfile' accept='image/*' value=''/></p>
		<input type='hidden' name ='id' value=$id>
		<input type='hidden' name ='oldPhoto' value=$oldPhoto>
		<p>Название <input type='text' name ='name' value=$name></p>
		<p>Размер <input type='text' name ='size' value=$size></p>
		<p>Цвет <input type='text' name ='color' value=$color></p>
		<p>Фирма <input type='text' name ='brand' value=$brand></p>
		<p>Цена <input type='text' name ='price' value=$price></p>
		<button> Изменить запись </button></div>
		</form>";
	}
	?>
	<h2> Сейчас на складе </h2>
	<?php
        ////////////////////////// Вывод таблицы товаров ///////////////////////////////////////////////////////////////////
		$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
        $res = $mysqli->query('SELECT * From shoes');
        echo "<div id=ajaxDiv>";
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
			echo"<td><a id='buttonA' href='loadChangeRecord.php?id=$id'></a></td>
			<td><button onclick='ajaxRequestDeleteRecord($id,$photo_str)'></button></td>"; // <a id='buttonA' href='deleteRecord.php?id=$id&photo=$photo'> Удалить </a>
			}
			else
			{
				if($login!=null)
				{
					echo "<td><a id='buttonA' href='addShoeToBasket.php?idUser=$userid&idShoe=$id'></a></td>"; //"<td><button onclick='ajaxRequestDeleteRecord($id,$photo_str)'>Купить</button></td>";
				}
			}
		}
        echo "</tr></table></div>";
	 ?>
	 <?php
	 if($admin)
	 {
	echo "<h2> Добавить товар </h2>
    <div class= formInput>
        <form id='upload-container' action='addRecord.php' method ='post' enctype='multipart/form-data'> 
		<p>Выберите фото<input type='file' class='chooseFile' name='userfile' accept='image/*' /></p>
        <p>Название <input type='text' name ='name'></p>
        <p>Размер <input type='text' name ='size'></p>
        <p>Цвет <input type='text' name ='color'></p>
        <p>Фирма <input type='text' name ='brand'></p>
        <p>Цена <input type='text' name ='price'></p>
        <button> Добавить товар </button>
		</form>
	</div>";
	 }
	?>
	</div>
	<div class="footer">
	8(960)319-71-39 г. Заречный Пензенская область<br>
	Горячая линия
	</div>
</body>


<script>

	function ajaxRequest(url,price,size)
	{
		var httpRequest = new XMLHttpRequest();
		httpRequest.onreadystatechange = function()
		{
			alertResponse(httpRequest);
		};
		httpRequest.open('POST', url, true);
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); //тип передаваемых данных закодирован.
		let value = "price=" + price +"&size=" + size;
		httpRequest.send(value);
	}
	
	function alertResponse(httpRequest)
	{
		if(httpRequest.readyState == 4)
		{
			if(httpRequest.status==200)
			{
				var responseDiv = document.getElementById('ajaxDiv');
				responseDiv.innerHTML = httpRequest.responseText;
			}
			else
			{
				alert('Возникли проблемы с получением ответа от сервера.');
			}
		}
	}

	let i=0;

	function addSelect(node,text)
	{
		delSelect(node) // очищаем предыдущее выделение
		i=0;
		if(text!="")
		{
			enumChildNodes(node,text);
			if(i==0)
				alert('Совпадений нет');
		}
		else
		{
			alert("Вы не ввели слово для поиска");
		}
	}

	function enumChildNodes(node, text)
	{
		if(1==node.nodeType)
		{
			var child = node.firstChild;
			while(child)
			{
				var nextChild = child.nextSibling;
				if(1==child.nodeType)
				{
					enumChildNodes(child,text);
				}
				else if(3==child.nodeType && child.nodeValue.trim()== text)
				{
					i++;
					var newSpan = document.createElement('span');
					newSpan.className = 'selection';
					newSpan.innerHTML = child.nodeValue;
					node.replaceChild(newSpan,child);
					selected =true;
				}
				child = nextChild;
			}
		}
	}

	function delSelect(node)
	{
		if(1==node.nodeType)
		{
			var child = node.firstChild;
			while(child)
			{
				var nextChild = child.nextSibling;
				if(1==child.nodeType)
				{
					delSelect(child);
				}
				else
				{
					if(node.className == 'selection')
					{
						var textNode = node.firstChild; //текст
        				var parentN = node.parentNode; //внешний тэг node
						parentN.replaceChild(textNode, node);
						//node.remove();
						console.log(node);
						console.log(parentN);
						console.log(textNode);
					}
				}
				child = nextChild;
			}
		}
	}

	function ajaxRequestDeleteRecord(id, photo)
	{
		var httpRequest = new XMLHttpRequest();
		httpRequest.onreadystatechange = function()
		{
			alertResponse(httpRequest);
		};
		httpRequest.open('POST', 'deleteRecord.php', true);
		httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		let value = "id="+id+"&photo="+photo;
		httpRequest.send(value);
    }
    

	function alertResponse(httpRequest)
	{
		if(httpRequest.readyState == 4)
		{
			if(httpRequest.status==200)
			{
				var responseDiv = document.getElementById('ajaxDiv');
				responseDiv.innerHTML = httpRequest.responseText;
			}
			else
			{
				alert('Возникли проблемы с получением ответа от сервера.');
			}
		}
	}
</script>
</html>