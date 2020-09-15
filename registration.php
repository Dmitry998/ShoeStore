<?php 
if(isset($_POST['login']) && isset($_POST['password'])&& isset($_POST['password2']))
{
	$login = $_POST['login'];
	$pwd = $_POST['password'];
    $pwd2 = $_POST['password2'];

    if($login=='')
    {
        $error = 'Вы не ввели логин.';	
		Header("Location:registrationForm.php?error=$error");
    }
    else 
    {
        if($pwd==$pwd2)
        {
            if($pwd!='')
            {
                $mysqli = new mysqli('localhost','root','12345678','Shoe_store');
                $answ = $mysqli->query("SELECT * FROM users WHERE login = '$login'");
                if($answ->num_rows >= 1)
                {
                    $error = 'Этот логин уже занят.';	
                    Header("Location:registrationForm.php?error=$error");
                }
                else
                {
                    $rnd =sprintf('%08x%08x%08x%08x', rand(), rand(), rand(), rand());
                    $pwdHash = sha1($pwd.$rnd);
                    $token = 'a';
                    $res = $mysqli->prepare("INSERT INTO users (login, password, rnd, token) VALUES (?,?,?,?)");
                    $res->bind_param('ssss',$login,$pwdHash,$rnd,$token);
                    $res->execute();
                    Header("Location:registrationForm.php?access");
                }
            }
            else
            {
                $error = 'Вы не ввели пароль.';	
		        Header("Location:registrationForm.php?error=$error");
            }
        }
        else
        {
            $error = 'Пароли не совпадают';	
            Header("Location:registrationForm.php?error=$error");
        }
    }
}
?>