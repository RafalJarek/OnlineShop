<?php
  session_start();
  if(isset($_SESSION['loggedin']))
{
try
    {
        require_once("../PHP/db.php");

        $login = $_SESSION['login'];

        $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        
        $sql = "SELECT purit.price FROM purchase as pur
                inner join purchase_item as purit on pur.id_purchase = purit.id_purchase
                inner join user as u on u.id_user = pur.id_user where pur.id_status='1' AND u.login='$login'";

        $price=array();

        foreach ($conn->query($sql) as $row) 
        {
                array_push($price,$row['price']);
        }
        $total = array_sum($price);
        $_SESSION['basket_value']=$total;
    }
    catch(PDOException  $e )
    {
    echo "Error: ".$e;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-git.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Lisia Hurtownia Obuwia</title>
</head>

<body>

<?php 

if(isset($_SESSION['e_observed_already']))
{
        echo'<div class="error" style="text-align:center">'.$_SESSION['e_observed_already'].'</div>'.'<br />';
        unset($_SESSION['e_observed_already']);
}

if(isset($_SESSION['e_amount0']))
{
        echo'<div class="error" style="text-align:center">'.$_SESSION['e_amount0'].'</div>'.'<br />';
        unset($_SESSION['e_amount0']);
}

if(isset($_SESSION['e_amount<']))
{
        echo'<div class="error" style="text-align:center">'.$_SESSION['e_amount<'].'</div>'.'<br />';
        unset($_SESSION['e_amount<']);
}

include_once "header.php";
unset($_SESSION['category'])?>

<?php include_once "menuwrapper.php"?>

<div class="pictures">
        <div class="limg" id="p1">
                <a target="_self" href="catalog.php?value=Szpilki">
                        <img id="limg_pic" src="../Img/szpilki/szpilki_category.png" alt="Szpilki" title="Szpilki">
                </a>
        </div>
        
        <div class="pimg" id="p2">
                <a target="_self" href="catalog.php?value=Baletki">
                        <img id="pimg_pic" src="../Img/baletki/baletki_category.png" alt="Baletki" title="Baletki">
                </a>
        </div>

        <div class="cimg" id="p3">
                <a target="_self" href="catalog.php?value=Botki">
                        <img id="cimg_pic" src="../Img/botki/botki_category.png" alt="Botki" title="Botki">
                </a>
        </div>

        <div class="limg" id="p4">
                <a target="_self" href="catalog.php?value=Trampki">
                        <img id="limg_pic" src="../Img/trampki/trampki_category.png" alt="Trampki" title="Trampki">
                </a>
        </div>
        
        <div class="pimg" id="p5">
                <a target="_self" href="catalog.php?value=Timberki">
                        <img id="pimg_pic" src="../Img/timberki/timberki_category.png" alt="Timberki" title="Timberki">
                </a>
        </div>        
</div>

<div class="social">
        <div id="facebook">
                <a href="https://www.facebook.com/lisiahurtownia">
                        <i class="fa fa-facebook-square fa-4x"  aria-hidden="true"></i>
                </a>
        </div>

        <div id="instagram">
                <a href="google+ lisiahurtownia">
                        <i class="fa fa-instagram fa-4x"  aria-hidden="true"></i>
                </a>
        </div>
</div>

<?php include_once "footer.php"?>

</body>
</html>