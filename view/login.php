<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Zaloguj się</title>
</head>

<?php include_once "header.php"?>

<?php 
include_once "menuwrapper.php";

if(isset($_SESSION['e_basket_already']))
{
        echo'<div class="error" style="text-align:center">'.$_SESSION['e_basket_already'].'</div>'.'<br />';
        unset($_SESSION['e_basket_already']);
}
?>

<div class="login_page">
        <div id="login_page_signup">
                <h2>Rejestracja</h2>
                <p>Aby móc w pełni korzystać z naszego sklepu, musisz się najpierw zarejestrować</p>

                <form action="signup.php">
                <button class="button_login" type="submit">Zarejestruj</button>
                </form>
        </div>
        
        <div id="login_page_login">
                <h2>Logowanie</h2>
                <form action="../PHP/zaloguj.php" method="post">
                <h4 style="margin:-10px 0 0 0">Login</h4>     
                <input type="text" placeholder="Username" id="username" style="width:100%;color:#30aca2" name="login"/>     
                <h4 style="margin:10px 0px 0px 0px">Hasło</h4>
                <input type="password" placeholder="Password" id="password" style="width:100%; margin-bottom:10px;color:#30aca2" name="haslo"/> 
                </br>   
                <button type="submit" class="button_login" value="Zaloguj się">Zaloguj</button>
                </br></br>
                </form>
                <?php
                if(isset($_SESSION['errorloginempty']))  
                {
                  echo $_SESSION['errorloginempty'];
                }
                if(isset($_SESSION['errorlogin']))  
                {
                  echo $_SESSION['errorlogin'];
                }
                ?>
                <!--<a href="" target="_self" style="text-decoration:underline; margin-top:20px">Zapomniałeś hasło?</a>-->
                
        </div>
</div>

<?php include_once "footer.php"?>

</body>
</html> 