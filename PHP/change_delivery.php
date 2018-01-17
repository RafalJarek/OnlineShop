<?php
session_start();
require_once("db.php");

$login=$_GET["login"];
$date=$_GET["date"];

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
    if($connect->query("UPDATE purchase SET id_status='4' WHERE id_user='$login' and order_date= '$date'"))
    {
      ?>
      <script type="text/javascript">
      window.location.href = 'https://inzynier.000webhostapp.com/view/admin.php';
      </script>
      <?php
    }
    else
    {
      throw new Exception($connect->error);
    }
}   
}
catch(Exception $e)
{
  echo'<div class="error">Błąd serwera przepraszamy</div>'.'<br />';
  echo'Informacja deweloperska: '.$e;
}
?>
      