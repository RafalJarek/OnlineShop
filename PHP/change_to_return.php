<?php
session_start();
require_once("db.php");

$login=$_GET["login"];
$date=$_GET["date"];
$id_product=$_GET["id"];

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
      $sql="SELECT purit.quantity, pro.total_amount, pur.id_status
                FROM purchase as pur
                inner join purchase_item as purit on pur.id_purchase = purit.id_purchase
                inner join product as pro on pro.id_product = purit.id_product
                inner join status as s on s.id_status = pur.id_status
                inner join user as u on u.id_user = pur.id_user where s.id_status='5' AND u.id_user='$login' AND pur.order_date='$date' AND pro.id_product='$id_product'";

      $quantity=array();
      $amount=array();

      foreach ($connect->query($sql) as $row) 
      {
        array_push($quantity,$row['quantity']);
        array_push($amount,$row['total_amount']);
      }
      $max=count($amount);
      for($i=0;$i<$max;$i++)
      {
        $new_quantity[$i]=$amount[$i] + $quantity[$i];
        if($connect->query("UPDATE product SET total_amount='$new_quantity[0]' WHERE id_product='$id_product'"))
        {
          if($connect->query("UPDATE purchase as p inner join purchase_item as purit on p.id_purchase=purit.id_purchase SET id_status='7' WHERE purit.id_product='$id_product' and p.order_date= '$date'"))
          {
            ?>
            <script type="text/javascript">
            window.location.href = 'https://inzynier.000webhostapp.com/view/admin.php';
            </script>
            <?php
          }
          else
          {
            $connect->query("UPDATE product SET total_amount='$amount[0]' WHERE id_product='$id_product'");
            throw new Exception($connect->error);  
          }
        }
        else
        {
          throw new Exception($connect->error);
        }
      }
}   
}
catch(Exception $e)
{
  echo'<div class="error">Błąd serwera przepraszamy</div>'.'<br />';
  echo'Informacja deweloperska: '.$e;
}
?>
      