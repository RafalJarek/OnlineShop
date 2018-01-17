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
        $id_product = $_POST['delete_observed'];

        if($connect->query("DELETE FROM observed WHERE id_product='$id_product'")===true)
        {
          ?>
          <script type="text/javascript">
          window.location.href = 'https://inzynier.000webhostapp.com/view/observed.php';
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
    
    catch(Exception $e)
    {
      echo'<div class="error">Błąd serwera przepraszamy</div>'.'<br />';
      echo'Informacja deweloperska: '.$e;
    }

?>