<?php
session_start();
if(isset($_SESSION['login']))
{
  $login = $_SESSION['login'];
}
else
{
  $_SESSION['e_basket_already']="Aby dodać produkt do koszyka najpierw się zaloguj";
  header('Location: ../view/login.php');
  exit();
}
require_once("db.php");
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
    date_default_timezone_set('Europe/Warsaw');
    $current_date = date('Y-m-d H:i:s');

    if(isset($_POST['quant']))
    {
      $quant = $_POST['quant'];
    }
    else
    {
      $quant = 1;
    }
    $id_product = $_POST['add_basket'];  
    $sql = "SELECT id_product from purchase_item as purit
    inner join purchase as pur on purit.id_purchase=pur.id_purchase where id_product='$id_product' and id_status ='1'";
    foreach ($connect->query($sql) as $row1) 
    {
      $getid_product = $row1['id_product'];
    }
    
    if(isset($getid_product))
    {
      $_SESSION['e_basket_already']="Produkt już wcześniej dodany do koszyka";
      ?>
      <script type="text/javascript">
      window.location.href = 'https://inzynier.000webhostapp.com/view/basket.php';
      </script>
      <?php
      exit();
    }

    //znajdź id usera dla danego loginu

    $sql = "SELECT id_user from user where login='$login'";
    foreach ($connect->query($sql) as $row2) 
    {
      $id_user = $row2['id_user'];
    }    

    //znajdź id adresu dla danego id usera

    $sql = "SELECT id_address from user_detail as ud
        inner join user as u on u.id_user_detail=ud.id_user_detail 
        where u.login='$login' limit 1";
    foreach ($connect->query($sql) as $row3) 
    {
      $id_address = $row3['id_address'];
    }
    
    //sprawdź ilość kartonów na stanie

    $sql = "SELECT total_amount FROM product where id_product='$id_product'";
    foreach ($connect->query($sql) as $row4) 
    {
      $amount = $row4['total_amount'];
    }
    
    if($amount<$quant)
    {
      $quant=$amount;
      $_SESSION['e_amount<']="Nie mamy tyle kartonów na stanie";
      ?>
      <script type="text/javascript">
      window.location.href = 'https://inzynier.000webhostapp.com/view/basket.php';
      </script>
      <?php
    }

    //znajdź id usera dla danego loginu

    $sql = "SELECT id_purchase from purchase order by id_purchase desc limit 1";
    foreach ($connect->query($sql) as $row4) 
    {
      $id_purchase = $row4['id_purchase'];
    }
    if($id_purchase>0)
    {
      $id_purchase=$id_purchase+1;
    }
    else
    {
      $id_purchase=1;
    }

    //wartość
    
    $sql = "SELECT p.price, c.quantity as pairs FROM product as p 
    inner join carton as c on c.id_carton=p.id_carton where p.id_product='$id_product' limit 1";
    $price=array();
    $pairs=array();
    
    foreach ($connect->query($sql) as $row) 
      {
          array_push($price,$row['price']);
          array_push($pairs,$row['pairs']);
      }
    $sum=$price[0] * $pairs[0] * $quant;
    $_SESSION['basket_value'] += $sum;

    if($connect->query("INSERT INTO purchase Values ('$id_purchase', '$id_user', '$current_date', 'null', '1', 'null', '$id_address', '1')"))
    {
      if($connect->query("INSERT INTO purchase_item Values ('null', '$id_purchase', '$id_product', '$quant', '$sum')"))  
      {
        $_SESSION['basketsuccess']=true;
        ?>
        <script type="text/javascript">
        window.location.href = 'https://inzynier.000webhostapp.com/view/basket.php';
        </script>
        <?php
      }
      else
      {
        $connect->query("DELETE FROM purchase WHERE id_purchase= '$id_purchase'");
        throw new Exception($connect->error);
      }
    }
    else
    {
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