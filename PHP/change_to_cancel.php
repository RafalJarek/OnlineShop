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
    if($connect->query("UPDATE purchase SET id_status='6' WHERE id_user='$login' and order_date= '$date'"))
    {
      $sql="SELECT pro.id_product, purit.quantity, pro.total_amount, pur.id_status
                FROM purchase as pur
                inner join purchase_item as purit on pur.id_purchase = purit.id_purchase
                inner join product as pro on pro.id_product = purit.id_product
                inner join status as s on s.id_status = pur.id_status
                inner join user as u on u.id_user = pur.id_user where s.id_status='6' AND u.id_user='$login' AND pur.order_date='$date'";

      $quantity=array();
      $amount=array();
      $id_product=array();

      foreach ($connect->query($sql) as $row) 
      {
        array_push($quantity,$row['quantity']);
        array_push($amount,$row['total_amount']);
        array_push($id_product,$row['id_product']);
      }
      $max=count($quantity);

      for($i=0;$i<$max;$i++)
      {
        $new_quantity[$i]=$amount[$i] + $quantity[$i];
        if($connect->query("UPDATE product SET total_amount='$new_quantity[$i]' WHERE id_product='$id_product[$i]'"))
        {
        }
        else
        {
          throw new Exception($connect->error);
        }
      }
      header('Location: ../view/admin.php');
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
      