<?php
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
        //czy email już istnieje
        $result = $connect->query("SELECT id_user FROM user WHERE email='$email'");

        if($result==false) throw new Exception($connect->error);

        $email_quantity=$result->num_rows;
        if($email_quantity>0)
        {
          $all_good=false;
          $_SESSION['e_email_already']="Mamy już użytkownika o takim adresie email";
        }
        
        //czy nip już istnieje
        $result = $connect->query("SELECT nip FROM user_detail WHERE nip='$nipreplace'");

        if($result==false) throw new Exception($connect->error);

        $nip_quantity=$result->num_rows;
        if($nip_quantity>0)
        {
          $all_good=false;
          $_SESSION['e_nip_already']="Mamy już użytkownika o takim numerze nip";
        }
        
        //czy login już istnieje
        $result = $connect->query("SELECT id_user FROM user WHERE login='$login'");

        if($result==false) throw new Exception($connect->error);

        $login_quantity=$result->num_rows;
        if($login_quantity>0)
        {
          $all_good=false;
          $_SESSION['e_login_already']="Mamy już użytkownika o takim loginie. Wybierz inny.";
        }

        if ($all_good==true)
        {
          //hurra,wszystkie testu przeszły, dodajemy użytkowniaka do bazy
          
          $iduserquery = "SELECT id_user from user order by id_user desc Limit 1";
          $iduserresult=mysqli_query($connect, $iduserquery);
          if ($iduserresult === FALSE) { die(mysql_error()); }
          while($getlastiduser = mysqli_fetch_array($iduserresult))
          {
            $id = $getlastiduser['id_user']+1;
          }
          if($connect->query("INSERT INTO address Values ('$id','$country', '$city','$postcode','$street','$buildingnumber','$localnumber')"))
          {
            if($connect->query("INSERT INTO user_detail Values ('$id','$firstname', '$surname','$companyname','$nipreplace','$phone','$id')"))
            {
              if($connect->query("INSERT INTO user Values ('$id', '$login','$email','$haslo_hash','$salt','$id','1')"))
              {
                $_SESSION['udanarejestracja']=true;
                ?>
                <script type="text/javascript">
                window.location.href = 'https://inzynier.000webhostapp.com/view/witaj.php';
                </script>
                <?php
              }
              else
              {
                $connect->query("DELETE FROM address WHERE id_address= '$id'");
                $connect->query("DELETE FROM user_detail WHERE id_user_detail= '$id'");
                throw new Exception($connect->error);
              }
            }
            else
            {
              $connect->query("DELETE FROM address WHERE id_address= '$id'");
              throw new Exception($connect->error);
            }
          }
          else
          {
            throw new Exception($connect->error);
          }
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