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
        date_default_timezone_set('Europe/Warsaw');
        $current_date = date('Y/m/d');
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
        $id_product = $_POST['add_observed'];
        
        $sql = "SELECT id_product from observed where id_product='$id_product'";
        foreach ($connect->query($sql) as $row) 
        {
            $getid_product = $row['id_product'];
        }
        if($getid_product>0)
        {
          $_SESSION['e_observed_already']="Produkt już wcześniej dodany do obserwowanych";
          header('Location: ../view/index.php');
          exit();
        }

        $sql = "SELECT id_user from user where login='$login'";
        foreach ($connect->query($sql) as $row) 
        {
            $id_user = $row['id_user'];
        }

        if($connect->query("INSERT INTO observed Values ('null', '$id_product', '$id_user', '$current_date')"))
        {
          $_SESSION['observedsuccess']=true;
          header('Location: ../view/observed.php');
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