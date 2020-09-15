<?php
session_start();
$errors = array();
if(isset($_SESSION['userid']))
{
	//аутентификация из сессии
	$userid=$_SESSION['userid'];
	Header("Location:index.php");
}
else if(isset($_COOKIE['token']))
{
	//аутентификация из cookie
	$token = $_COOKIE['token'];	
	ConnectDB();
	$res = $mysqli->query("SELECT * FROM users WHERE token = '$token'");
	if($res->num_rows==1)
	{
		$user = $res->fetch_object();
		LogIn($user->id);		
		Header("Location:index.php");
	}
}
else if(isset($_POST['login']) && isset($_POST['password']))
{
		//аутентификация из формы
		$login = $_POST['login'];
		$pwd = $_POST['password'];
		ConnectDB();
		$res = $mysqli->query("SELECT * FROM users WHERE login = '$login'");
		if($res->num_rows == 1)
		{
			$user = $res->fetch_object();
			$random = $user->rnd;
			$pwdHash = sha1($pwd.$random);
			if($pwdHash == $user->password)
			{
				LogIn($user->id);
				Header("Location:catalogShoes.php");
			}
			else
			{
				$errors[] = "Неправильный пароль.";
			}
		}
		else
		{
			$errors[] = "Пользователя с таким логином не существует.";
		}
		if(!empty($errors))
		{
			$error = array_shift($errors);
			Header("Location:authForm.php?error=$error");
		}
}
?>

<?php 
function ConnectDB()
{
	global $mysqli;
	$mysqli = new mysqli('localhost','root','12345678','Shoe_store');
}

function LogIn($userid)
{
	global $mysqli;
	$_SESSION['userid'] = $userid;
	$token = sprintf('%08x%08x%08x%08x', rand(), rand(), rand(), rand());
	setcookie('token',$token,time()+3600*24*30);
	$res = $mysqli->query("UPDATE users SET token = '$token' WHERE id = '$userid'");
}
?>