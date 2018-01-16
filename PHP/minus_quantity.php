<?php
session_start();
$id=$_GET['name'];
$id_product=$_GET['id'];
$il=$_GET['il'];
$quant=$_GET['quan'];
$quant=$quant-1;

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
      $sql="SELECT p.price, c.quantity as pairs FROM product as p 
        inner join carton as c on c.id_carton=p.id_carton where p.id_product='$id_product' limit 1";

      foreach ($connect->query($sql) as $row) 
      {
          $price = $row['price'];
          $pairs = $row['pairs'];
      }
      $sum=$price*$pairs*$quant;
      $sum1=$price*$pairs*1;

    if($connect->query("UPDATE purchase_item SET quantity=$quant WHERE id_purchase=$id"))  
        {
          if($connect->query("UPDATE purchase_item SET price=$sum WHERE id_purchase=$id"))  
          {
            $_SESSION['basket_value'] -= $sum1;
            header('Location: ../view/basket.php#basket_pair_quantity'.$il.'');
          }
        }
        else
        {
        throw new Exception($connect->error);
        }
    }
    $connect->close();
}

catch(Exception $e)
{
  echo'<div class="error">Błąd serwera przepraszamy</div>'.'<br />';
  echo'Informacja deweloperska: '.$e;
}

?>