<?php
session_start();

$login=$_SESSION['login'];


$current_date = date('Y-m-d H:i:s');
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
        
        $sql="SELECT order_date FROM inzynier.purchase where id_user='$id_user' order by order_date desc limit 1;";
        
          foreach ($connect->query($sql) as $row) 
          {
            $order_date=$row['order_date'];      
          }
        
          $sql="SELECT pro.id_product, purit.quantity, pro.total_amount, purit.price
                FROM purchase as pur
                inner join purchase_item as purit on pur.id_purchase = purit.id_purchase
                inner join product as pro on pro.id_product = purit.id_product
                inner join status as s on s.id_status = pur.id_status
                inner join user as u on u.id_user = pur.id_user where pur.order_date='$order_date' AND u.id_user='$id_user'";
          $id_product=array();
          $quantity=array();
          $amount=array();
          $price=array();

          foreach ($connect->query($sql) as $row) 
          {
              array_push($id_product,$row['id_product']);
              array_push($quantity,$row['quantity']);
              array_push($amount,$row['total_amount']);
              array_push($price,$row['price']);
          }
          $max=count($quantity);
          $sum=array_sum($price);
          $_SESSION['basket_value'] = $sum;

          for($i=0;$i<$max;$i++)
          {
            $new_quantity[$i]=$amount[$i] + $quantity[$i];
            if($connect->query("UPDATE product SET total_amount='$new_quantity[0]' WHERE id_product='$id_product[0]'"))
           {
            ?>
            <script type="text/javascript">
            window.location.href = 'https://inzynier.000webhostapp.com/view/basket.php';
            </script>
            <?php
           }
           else
           {
              throw new Exception($connect->error);
           }
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
