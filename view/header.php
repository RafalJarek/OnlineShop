<div class="header">

<?php
if(isset($_SESSION['loggedin']))
{
        echo   '<div id="login_icon">
                <a href="user_page.php"><i class="fa fa-user-circle-o fa-3x" aria-hidden="true" style="padding-top:18px;"></i></a>
                </div>
                <p id="signup_info_hello">Witaj '.$_SESSION['login'].'!</p>
                <div id="signup_info_logout"><a href="../PHP/logout.php" style="color:black ; text-decoration:none">Wyloguj się!</a></div>';
                if($_SESSION['login']=="Karolina")
                {
                        echo '<a id="admin_panel" href="../view/admin.php" style="color:black ; text-decoration:none">Panel <br>Administratora</a>';
                }
}
else
{
        echo   '<div id="login">
                <a href="../view/login.php">zaloguj się</a>
                </div>
                <div id="signup">
                <a href="../view/signup.php">rejestracja</a>
                </div>';
}
?>
    
        
        <img id="logo" src="../Logo/logo.png"></img>
        <img id="logo_500" src="../Logo/logo_small.png"></img>
        
        
        <div id="shipping">
                <i class="fa fa-truck fa-2x" aria-hidden="true"></i>
        </div>
        
        <div id="favorite">
                <a href="../view/observed.php"><i class="fa fa-heart-o fa-2x" aria-hidden="true"></i></a>
        </div>
        
        <div id="basket">
                <a href="../view/basket.php"><i class="fa fa-shopping-basket fa-2x" aria-hidden="true"></i></a>
        </div>

        <?php
        $url = $_SERVER["REQUEST_URI"]; 
        if(isset($_SESSION['loggedin']))
        {
                if(isset($_SESSION['basket_value']))
                {
                        $total=$_SESSION['basket_value'];
                }
                else
                {
                        $total=0;
                }
                if($url != '/INZ/view/to_overview.php')
                {
                $total = number_format($total, 2, '.', '');
                echo "<a href='../view/basket.php' id='index_basket_price'>$total zł</a>";
                }
        }        
        ?>
        <input id="search" type="text" name="text" placeholder="Czego szukasz?" role="textbox">      
</div>
<div id="display"></div>
