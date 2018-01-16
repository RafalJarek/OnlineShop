<?php

  session_start();

  if(!isset($_SESSION['loggedin']))
  {
    header('Location: index.php');
    exit();
  }

  if(isset($_POST['email']))
  {
    require_once("../PHP/validationsignup.php");
    require_once "../PHP/db.php";
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
            $sql="SELECT id_user, ud.id_user_detail, a.id_address FROM user as u
            inner join user_detail as ud on u.id_user_detail=ud.id_user_detail
            inner join address as a on a.id_address=ud.id_address
            where login='$login'";
            
            foreach ($connect->query($sql) as $row) 
            {
                $iduser=$row['id_user'];
                $iduserdetail=$row['id_user_detail'];
                $idaddress=$row['id_address'];
            }
           

          if($connect->query("UPDATE address SET locality='$city', postcode='$postcode', street='$street', home_number='$buildingnumber', local_number='$localnumber' WHERE id_address='$idaddress'"))
          {
            if($connect->query("UPDATE user_detail SET first_name='$firstname', last_name='$surname', company_name='$companyname', nip='$nipreplace', phone='$phone', id_address='$idaddress' WHERE id_user_detail='$iduserdetail'"))
            {
                header('Location: user_page.php');
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
  }
?>
  
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Profil użytkownika</title>
</head>

<?php include_once "../view/header.php"?>

<?php include_once "../view/menuwrapper.php"?>

<form method="post">
<div class="signup_page">

<?php
if(isset($_SESSION['loggedin']))
{
        echo   '<div id="user_name">
                Witaj '.$_SESSION['login'].'!
                </div>';
}
try
{
    require_once("../PHP/db.php");
    $thislogin = $_SESSION['login'];
    $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
    
    $sql="SELECT * FROM user_detail as ud
        inner join user as u on u.id_user=ud.id_user_detail
        inner join address as a on a.id_address=ud.id_user_detail 
        where u.login='$thislogin'";
    
    foreach ($conn->query($sql) as $row) 
    {
        $login = $row['login'];
        $email = $row['email'];
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $company_name = $row['company_name'];
        $nip = $row['nip'];
        $phone = $row['phone'];
        $country = $row['country'];
        $locality = $row['locality'];
        $postcode = $row['postcode'];
        $street = $row['street'];
        $home_number = $row['home_number'];
        $local_number = $row['local_number'];
    }
    if($local_number==0)
    {
        $local_number=null;
    }

    $sql="SELECT order_date, pro.price as price, purit.price as sum, quantity, name, file_name 
		FROM purchase as pur
        inner join purchase_item as purit on pur.id_purchase = purit.id_purchase
        inner join product as pro on pro.id_product = purit.id_product
        inner join image as i on i.id_image = pro.id_image
        inner join user as u on u.id_user = pur.id_user
        where u.login='$thislogin' order by order_date DESC";

    $his_date=array();
    $his_price=array();
    $his_sum=array();
    $his_quantity=array();
    $his_name=array();
    $his_file_name=array();
    
    foreach ($conn->query($sql) as $row) 
    {
        array_push($his_date,$row['order_date']);
        $row['price'] = number_format($row['price'], 2, '.', '');
        array_push($his_price,$row['price']);
        $row['sum'] = number_format($row['sum'], 2, '.', '');
        array_push($his_sum,$row['sum']);
        array_push($his_quantity,$row['quantity']);
        array_push($his_name,$row['name']);
        array_push($his_file_name,$row['file_name']);
    }
    $max=count($his_name);
}
catch(PDOException  $e )
{
echo "Error: ".$e;
}
?>

<div id="contact_details">
        <h2 style="text-align: left">Dane kontaktowe</h2>
        <div>
            Login:<input id="change" type="text" name="login" readonly="readonly" value="<?php
                if(isset($_SESSION['a_login']))
                {
                echo $_SESSION['a_login'];
                unset($_SESSION['a_login']);
                }
                else
                {
                echo $login;
                }
                ?>"><br />
                
                <?php
                if(isset($_SESSION['e_login_strlen']))
                {
                echo'<div class="error">'.$_SESSION['e_login_strlen'].'</div>'.'<br />';
                unset($_SESSION['e_login_strlen']);
                }
                if(isset($_SESSION['e_namematch']))
                {
                echo'<div class="error">'.$_SESSION['e_namematch'].'</div>'.'<br />';
                unset($_SESSION['e_namematch']);
                }
                if(isset($_SESSION['e_login_already']))
                {
                echo'<div class="error">'.$_SESSION['e_login_already'].'</div>'.'<br />';
                unset($_SESSION['e_login_already']);
                }
                ?> 
        </div>

        <div>
            E-mail:<input id="change" type="text" name="email" readonly="readonly" value="<?php
                                if(isset($_SESSION['a_email']))
                                {
                                echo $_SESSION['a_email'];
                                unset($_SESSION['a_email']);
                                }
                                else
                                {
                                    echo $email;
                                }
                                ?>">
                                </div>
                                <?php
                                if(isset($_SESSION['e_email']))
                                {
                                        echo'<div class="error">'.$_SESSION['e_email'].'</div>'.'<br />';
                                        unset($_SESSION['e_email']);
                                }
                                if(isset($_SESSION['e_email_already']))
                                {
                                        echo'<div class="error">'.$_SESSION['e_email_already'].'</div>'.'<br />';
                                        unset($_SESSION['e_email_already']);
                                }
                                ?> 
        <div>
            Imię:<input id="change" type="text" name="firstname" readonly="readonly" value="<?php
                                if(isset($_SESSION['a_firstname']))
                                {
                                        echo $_SESSION['a_firstname'];
                                        unset($_SESSION['a_firstname']);
                                }
                                else
                                {
                                    echo $first_name;
                                }
                                ?>">  
                        </div>
                                <?php
                                if(isset($_SESSION['e_firstname']))
                                {
                                echo'<div class="error">'.$_SESSION['e_firstname'].'</div>'.'<br />';
                                unset($_SESSION['e_firstname']);
                                }
                                ?>        
        <div>
            Nazwisko:<input id="change" type="text" name="surname" readonly="readonly" value="<?php
                                if(isset($_SESSION['a_surname']))
                                {
                                        echo $_SESSION['a_surname'];
                                        unset($_SESSION['a_surname']);
                                }
                                else
                                {
                                    echo $last_name;
                                }
                                ?>"> 
                        </div>
                                <?php
                                if(isset($_SESSION['e_surname']))
                                {     
                                echo'<div class="error">'.$_SESSION['e_surname'].'</div>'.'<br />';
                                unset($_SESSION['e_surname']);
                                }
                                ?>
        <div>
            Nazwa firmy:<input type="text" name="companyname" readonly="readonly" value="<?php
                                if(isset($_SESSION['a_companyname']))
                                {
                                        echo $_SESSION['a_companyname'];
                                        unset($_SESSION['a_companyname']);
                                }
                                else
                                {
                                    echo $company_name;
                                }
                                ?>"> 
                        </div>
                                <?php
                                if(isset($_SESSION['e_companyname']))
                                {
                                echo'<div class="error">'.$_SESSION['e_companyname'].'</div>'.'<br />';
                                unset($_SESSION['e_companyname']);
                                }
                                ?>
        <div>
            Nip:<input type="text" name="nip" readonly="readonly" value="<?php
                                if(isset($_SESSION['a_nip']))
                                {
                                        echo $_SESSION['a_nip'];
                                        unset($_SESSION['a_nip']);
                                }
                                else
                                {
                                    echo $nip;
                                }
                                ?>"> 
                        </div>
                                <?php
                                if(isset($_SESSION['e_nip']))
                                {
                                echo'<div class="error">'.$_SESSION['e_nip'].'</div>'.'<br />';
                                unset($_SESSION['e_nip']);
                                }
                                if(isset($_SESSION['e_nip_already']))
                                {
                                        echo'<div class="error">'.$_SESSION['e_nip_already'].'</div>'.'<br />';
                                        unset($_SESSION['e_nip_already']);
                                }
                                ?>
        <div>
            Telefon:<input type="text" name="phone" readonly="readonly" value="<?php
                                if(isset($_SESSION['a_phone']))
                                {
                                        echo $_SESSION['a_phone'];
                                        unset($_SESSION['a_phone']);
                                }
                                else
                                {
                                    echo $phone;
                                }
                                ?>"> 
                        </div>
                                <?php
                                if(isset($_SESSION['e_phone']))
                                {
                                echo'<div class="error">'.$_SESSION['e_phone'].'</div>'.'<br />';
                                unset($_SESSION['e_phone']);
                                }
                                ?>           

    </div>
    
    <div id="delivery_address_user_page">
        <h2 style="text-align: left">Adres dostawy</h2>
        <div>
            Kraj:<input id="change" type="text" name="country" readonly="readonly" value="<?php echo $country;?>">
        </div>
                                
        
        <div>
            Miejscowość:<input id="change" type="text" name="city" readonly="readonly" value="<?php
            if(isset($_SESSION['a_city']))
            {
                    echo $_SESSION['a_city'];
                    unset($_SESSION['a_city']);
            }
            else
            {
                echo $locality;
            }
            ?>"> 
    </div>
            <?php
            if(isset($_SESSION['e_city']))
            {
            echo'<div class="error">'.$_SESSION['e_city'].'</div>'.'<br />';
            unset($_SESSION['e_city']);
            }
            ?>
        
        <div>
            Kod pocztowy:<input type="text" name="postcode" readonly="readonly" value="<?php
            if(isset($_SESSION['a_postcode']))
            {
                    echo $_SESSION['a_postcode'];
                    unset($_SESSION['a_postcode']);
            }
            else
            {
                echo $postcode;
            }
            ?>"> 
    </div>
            <?php
            if(isset($_SESSION['e_postcode']))
            {
            echo'<div class="error">'.$_SESSION['e_postcode'].'</div>'.'<br />';
            unset($_SESSION['e_postcode']);
            }
    ?> 

        <div>
            Ulica:<input id="change" type="text" name="street" readonly="readonly" value="<?php
                                if(isset($_SESSION['a_street']))
                                {
                                        echo $_SESSION['a_street'];
                                        unset($_SESSION['a_street']);
                                }
                                else
                                {
                                    echo $street;
                                }
                                ?>"> 
                        </div>
                                <?php
                                if(isset($_SESSION['e_street']))
                                {
                                echo'<div class="error">'.$_SESSION['e_street'].'</div>'.'<br />';
                                unset($_SESSION['e_street']);
                                }
                        ?>
        
        <div>
            Numer budynku:<input type="text" name="buildingnumber" readonly="readonly" value="<?php
            if(isset($_SESSION['a_buildingnumber']))
            {
                    echo $_SESSION['a_buildingnumber'];
                    unset($_SESSION['a_buildingnumber']);
            }
            else
            {
                echo $home_number;
            }
            ?>"> 
     </div>
            <?php
            if(isset($_SESSION['e_buildingnumber']))
            {
            echo'<div class="error">'.$_SESSION['e_buildingnumber'].'</div>'.'<br />';
            unset($_SESSION['e_buildingnumber']);
            }
             ?>
        
        <div>
            Numer lokalu:<input type="text" name="localnumber" readonly="readonly" value="<?php
            if(isset($_SESSION['a_localnumber']))
            {
                    echo $_SESSION['a_localnumber'];
                    unset($_SESSION['a_localnumber']);
            }
            else
            {
                echo $local_number;
            }
            ?>"> 
    </div>
            <?php
            if(isset($_SESSION['e_localnumber']))
            {
            echo'<div class="error">'.$_SESSION['e_localnumber'].'</div>'.'<br />';
            unset($_SESSION['e_localnumber']);
            }
    ?> 
    </div>
    <div id="change_your_data">
        <button id="confirm_change_your_data_button">Potwierdź zmiany</button>
        <button type="button" id="change_your_data_button">Zmień swoje dane</button>
    </div>
    </form>
</div>
<script>
var change = document.getElementById('change_your_data_button');
var confirm = document.getElementById('confirm_change_your_data_button');

change.onclick = function() 
{
$('input[readonly="readonly"]').removeAttr("readonly");
$('input[name="login"]').attr("readonly",true);
$('input[name="email"]').attr("readonly",true);
$('input[name="country"]').attr("readonly",true);
$('input[name="nip"]').attr("readonly",true);
$('input[name="login"]').css("background-color","#c3c3c3");
$('input[name="email"]').css("background-color","#c3c3c3");
$('input[name="country"]').css("background-color","#c3c3c3");
$('input[name="nip"]').css("background-color","#c3c3c3");
confirm.style.display = "inline";
}

</script>
<?php
//header
if($max==0)
{
    echo '<div id="order_history" style="font-weight:bold;font-size:30px">Nie zamawiałeś u nas jeszcze produktów</div>';
    echo '<div id="order_history" style="font-size:20px">Historia zamówień będzie dostępna po tym jak zamówisz u nas towar</div>';
    
}
else
{
    echo '
        <div id="order_history" style="grid-row:3">
            <h1 style="font-size: 30px">Historia zamówień</h1>
            
            <div class="history_container">    
                <div class="history_date_header">
                    <p>Data</p>
                </div>
                    
                <div class="history_image_header">
                    <p>Obraz</p>
                </div>
            
                <div class="history_name_header">
                    <p>Nazwa</p>
                </div>    
                
                <div class="history_price_header">
                    <p>Cena</p>
                </div>
                    
                <div class="history_quantity_header">
                    <p>Ilość</p>
                </div>
                    
                <div class="history_sum_header">
                    <p>Wartość</p>
                </div>                   
            </div>
        </div>';

    if(isset($his_date[0]))
    {
        $first = '
        <div class="history_product">    
            <div class="history_date">
            <p>'.$his_date[0].'</p>
            </div>

            <div class="history_image">
                <img id="history_img" src='.$his_file_name[0].'>
            </div>

            <div class="history_name">
                <p>'.$his_name[0].'</p>
            </div>

            <div class="history_price">
                <p>'.$his_price[0].'<span> zł</span></p>
            </div>
        
            <div class="history_quantity">
                <p>'.$his_quantity[0].'</p>
            </div>

            <div class="history_sum">
            <p>'.$his_sum[0].'<span> zł</span></p>
            </div>
        </div>';
        echo $first;

        $over_sum=$his_sum[0];
        $over_sum = number_format($over_sum, 2, '.', '');

        if(isset($his_date[1]))
        {
            if($his_date[0]!=$his_date[1] && isset($first)) 
            {
                echo'
                <div class="history_product_overview">    
                    <div class="history_product_overview_sum">
                        <span>'.$over_sum.'zł</span>
                    </div>
                </div>';
                $over_sum=0; 
            }
        }
        elseif(isset($first))
        {
            echo'
            <div class="history_product_overview">    
                <div class="history_product_overview_sum">
                    <span>'.$over_sum.'zł</span>
                </div>
            </div>';
            $over_sum=0; 
        }

    }
    else
    {
        echo "<div id='order_history' style='margin-top:30px;font-size:30px;font-weight:bold'>Nie zamówiono jeszcze żadnego produktu</div>";
    }
    //pierwszy produkt
    for($i=1;$i<=$max-1;$i++)
    {
        $over_sum += $his_sum[$i];
        if($his_date[$i]==$his_date[$i-1])
        {
            $date = "<br>";
        }
        else
        {
            $date = $his_date[$i];
        }
        echo '
            <div class="history_product">    
                <div class="history_date">
                <p>'.$date.'</p>
                </div>

                <div class="history_image">
                    <img id="history_img" src='.$his_file_name[$i].'>
                </div>

                <div class="history_name">
                    <p>'.$his_name[$i].'</p>
                </div>

                <div class="history_price">
                    <p>'.$his_price[$i].'<span> zł</span></p>
                </div>
            
                <div class="history_quantity">
                    <p>'.$his_quantity[$i].'</p>
                </div>

                <div class="history_sum">
                <p>'.$his_sum[$i].'<span> zł</span></p>
                </div>
            </div>';
            
            if(array_key_exists(($i+1),$his_date))
            {
                if($his_date[$i]==$his_date[$i+1])
                {
                }
                else
                {
                    $over_sum = number_format($over_sum, 2, '.', '');
                    echo'
                    <div class="history_product_overview">    
                        <div class="history_product_overview_sum">
                            <span>'.$over_sum.'zł</span>
                        </div>
                    </div>';
                    $over_sum=0; 
                }
            }
            else
            {
                $over_sum = number_format($over_sum, 2, '.', '');
                echo'
                <div class="history_product_overview">    
                    <div class="history_product_overview_sum">
                        <span>'.$over_sum.'zł</span>
                    </div>
                </div>';
                $over_sum=0; 
            }
    }
}

include_once "../view/footer.php"?>

</body>
</html> 