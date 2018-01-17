<?php
session_start();
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
        $id_purchase = $_POST['delete_basket'];
        $sql="SELECT price FROM purchase_item where id_purchase='$id_purchase'";

        foreach ($connect->query($sql) as $row) 
        {
            $price = $row['price'];
        }
        $sum=$price;
        $_SESSION['basket_value'] -= $sum;
        if($connect->query("DELETE FROM purchase_item WHERE id_purchase='$id_purchase'")===true)
        {
          if($connect->query("DELETE FROM purchase WHERE id_purchase='$id_purchase'")===true)
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