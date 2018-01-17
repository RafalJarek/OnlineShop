<?php
session_start();
header( "refresh:2;url=login.php" );


if(!isset($_SESSION['udanarejestracja']))
{
  header('Location: index.php');
  exit();
}
else
{
unset($_SESSION['udanarejestracja']);
}

//usuwamy zmienne zapamiętane w razie błędnej walidacji
if(isset($_SESSION['a_login'])) unset($_SESSION['a_login']);
if(isset($_SESSION['a_email'])) unset($_SESSION['a_email']);
if(isset($_SESSION['a_haslo1'])) unset($_SESSION['a_haslo1']);
if(isset($_SESSION['a_haslo2'])) unset($_SESSION['a_haslo2']);
if(isset($_SESSION['a_regulamin'])) unset($_SESSION['a_regulamin']);

//usuwanie błędów rejestracji
if(isset($_SESSION['e_login_strlen'])) unset($_SESSION['e_login_strlen']);
if(isset($_SESSION['e_login_alnum'])) unset($_SESSION['e_login_alnum']);
if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
if(isset($_SESSION['e_haslo_thesame'])) unset($_SESSION['e_haslo_thesame']);
if(isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);
if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);
if(isset($_SESSION['e_email_already'])) unset($_SESSION['e_email_already']);
if(isset($_SESSION['e_login_already'])) unset($_SESSION['e_login_already']);
if(isset($_SESSION['e_login_strlen'])) unset($_SESSION['e_login_strlen']);
if(isset($_SESSION['e_login_strlen'])) unset($_SESSION['e_login_strlen']);
if(isset($_SESSION['e_login_strlen'])) unset($_SESSION['e_login_strlen']);
if(isset($_SESSION['e_login_strlen'])) unset($_SESSION['e_login_strlen']);
if(isset($_SESSION['e_login_strlen'])) unset($_SESSION['e_login_strlen']);
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<title>Witaj</title>
</head>

<div class="header">       
        <img id="logo" src="../Logo/logo.png"></img>
        <img id="logo_500" src="../Logo/logo_small.png"></img>
</div>

<div class="header">       
        <img id="hello" src="..\Logo\hello.png"></img>
        <img id="hello_500" src="..\Logo\hello_small.png"></img>

        <p id="info_info">Za chwilkę zostaniesz przeniesiony na stronę główną </p>
</div>

</body>
</html> 