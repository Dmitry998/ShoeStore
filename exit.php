<?php
session_start();
unset($_SESSION['userid']);
setcookie('token','');
Header("Location:authForm.php");
?>