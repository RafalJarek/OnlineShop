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
<title>Podziękowania</title>
</head>

<?php 
$login=$_SESSION['login'];

if(isset($_POST['id_kurier']))
{
  $id_delivery=$_POST['id_kurier'];
}
else
{
  header("location ../view/to_payment.php");
  exit();
}
unset($_SESSION['basket_value']);

include_once "../view/header.php";

include_once "../view/menuwrapper.php";

require_once "../PHP/dane_firmy.php";
require_once("../PHP/db.php");
try
{
  $connect = new mysqli($host, $db_user, $db_password, $db_name);
  mysqli_set_charset($connect, "utf8"); 
  if($connect->connect_errno!=0)
  {
    throw new Exception(mysqli_connect_errno());
  }
  else
  {
        $sql = "SELECT id_user from user where login='$login'";
        foreach ($connect->query($sql) as $row) 
        {
          $id_user = $row['id_user'];
        }
        
        if($connect->query("UPDATE purchase SET id_delivery= '$id_delivery', id_status='2' WHERE id_status='1' AND id_user='$id_user'"))
        {
        }
        else
        {
          $_SESSION['basketfailed']="basketfailed'";
          throw new Exception($connect->error);
        }
      $connect->close();
    }
  }  
  catch(Exception $e)
  {
    echo'<div class="error">Błąd serwera przepraszamy</div>'.'<br />';
    echo'Informacja deweloperska: '.$e;
  }
?>

  <div class="header">       
    <p id="to_overview_header">Dziękujemy za dokonanie transakcji, oczekujemy<br> teraz na płatność</p>
  </div>

    <?php
//header( "refresh:3;url=../view/index.php" );
?>

<?php include_once "../view/footer.php"?>


</body>
</html> 
