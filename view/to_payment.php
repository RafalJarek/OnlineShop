<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Podsumowanie</title>
</head>

<?php 
$sum=$_POST['sum'];
$login=$_SESSION['login'];

if(isset($_POST['comment']))
{
  $comment =  $_POST['comment'];
}

include_once "../view/header.php";

include_once "../view/menuwrapper.php";

require_once "../PHP/dane_firmy.php";
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
        
        if($connect->query("UPDATE purchase SET comment='$comment', order_date='$current_date' WHERE id_status='1' AND id_user='$id_user'"))
        {
          $sql="SELECT pro.id_product, purit.id_purchase, purit.quantity, pro.total_amount, pur.id_status
                FROM purchase as pur
                inner join purchase_item as purit on pur.id_purchase = purit.id_purchase
                inner join product as pro on pro.id_product = purit.id_product
                inner join status as s on s.id_status = pur.id_status
                inner join user as u on u.id_user = pur.id_user where s.id_status='1' AND u.id_user='$id_user'";
          $id_product=array();
          $id_purchase=array();
          $quantity=array();
          $amount=array();

          foreach ($connect->query($sql) as $row) 
          {
              array_push($id_product,$row['id_product']);
              array_push($id_purchase,$row['id_purchase']);
              array_push($quantity,$row['quantity']);
              array_push($amount,$row['total_amount']);
          }
          $max=count($quantity);

          for($i=0;$i<$max;$i++)
          {
            $new_quantity[$i]=$amount[$i] - $quantity[$i];
            if($connect->query("UPDATE product SET total_amount='$new_quantity[0]' WHERE id_product='$id_product[0]'"))
           {
            if($quantity[$i]==0)
            {
              $connect->query("DELETE FROM purchase_item WHERE id_purchase=$id_purchase[$i]");
              $connect->query("DELETE FROM purchase WHERE id_purchase=$id_purchase[$i]");
            }
           }
           else
           {
              throw new Exception($connect->error);
           }
          }
        }
        else
        {
          $_SESSION['basketfailed']="dupa";
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
<div class="to_payment">
  <div id="to_payment_courier">
    <h1>Wybierz sposób dostawy</h1>
    <button type="button" class="kurier" id="k6" value="6"><img src="../Logo/TNT.jpg"></button>
    <button type="button" class="kurier" id="k2" value="2"><img src="../Logo/DHL.jpg"></button>
    <button type="button" class="kurier" id="k3" value="3"><img src="../Logo/DPD.png"></button>
    <button type="button" class="kurier" id="k4" value="4"><img src="../Logo/UPS.png"></button>
    <button type="button" class="kurier" id="k5" value="5"><img src="../Logo/INPOST.png"></button>
    <button type="button" class="kurier" id="k1" value="1"><img src="../Logo/osobisty.png"></button>
    <form method='post' id='delivery' action='../view/to_overview.php'>
    <input hidden name="id_kurier" id="kurier_text" value="a"></input>
    </form>
  </div>

<script>
    $("#k1").click(function(){
        $('#kurier_text').val("1");
        $('#do_podsumowania').css("display","block");
    });
    $("#k2").click(function(){
        $('#kurier_text').val("2");
        $('#do_podsumowania').css("display","block");
    });
    $("#k3").click(function(){
        $('#kurier_text').val("3");
        $('#do_podsumowania').css("display","block");
    });
    $("#k4").click(function(){
        $('#kurier_text').val("4");
        $('#do_podsumowania').css("display","block");
    });
    $("#k5").click(function(){
        $('#kurier_text').val("5");
        $('#do_podsumowania').css("display","block");
    });
    $("#k6").click(function(){
        $('#kurier_text').val("6");
        $('#do_podsumowania').css("display","block");
    });

    $( "button[class^='kurier']" ).click(function() {
      $( "button[class^='kurier']" ).css("border","solid 1px black");

      $(this).css("border","solid 1px #50cec4");
    });

    $( "button[class^='kurier']" ).click(function() {
      $( "#to_payment_address").css("display","none");
    });

    $( "#k1" ).click(function() {
      $( "#to_payment_address").css("display","block");
    });
  
</script>

<!--
</div>

<div class="to_payment">
-->
        <div id="to_payment_address" style="display:none">
                <h2>Odbiór osobisty</h2>
                <p>Ulica: <span><?php echo $ulica ?></span></p>
                <p>Miejscowosc: <span><?php echo $miejscowosc ?></span></p>
                <p>Godziny otwarcia: <span><?php echo $godziny ?></span></p>
        </div>
        <div id="to_payment_address_button">
                <form class='basket_continue_button_to_payment' action='../view/to_payment_back.php'>
                  <button type='submit'>kontynuuj zakupy</button>    
                </form>
        </div>
        
        <div id="to_payment_bank">
          <h1>Wybierz sposób zapłaty</h1>
          <button id="bank"><a target="_blank" href="https://login.aliorbank.pl/"><img src="../Logo/alior.png"></a></button>
          <button id="bank"><a target="_blank" href="https://online.mbank.pl/pl/Login"><img src="../Logo/mbank.jpg"></a></button>
          <button id="bank"><a target="_blank" href="https://login.ingbank.pl/mojeing/app/#login"><img src="../Logo/ing.jpg"></a></button>
          <button id="bank"><a target="_blank" href="https://online.nestbank.pl/bim-webapp/nest/login"><img src="../Logo/nest.jpg"></a></button>
          <button id="bank"><a target="_blank" href="https://m.ipko.pl/#entry?login"><img src="../Logo/pko.png"></a></button>
          <button id="bank"><a target="_blank" href="https://www.ideabank.pl/logowanie"><img src="../Logo/ideabank.jpg"></a></button>
        </div>

        <div id="to_payment_payment">
                <h2>Dane do przelewu</h2>
                <p>Nazwa odbiorcy: <span><?php echo $nazwa ?></span></p>
                <p>Adres odbiorcy: <span><?php echo $ulica.', '.$miejscowosc ?></span></p>
                <p>Numer konta bankowego: <span><?php echo $account_number ?></span></p>
                <p>Tytuł przelewu: <span><?php echo $login.' '.$current_date ?></span></p>
                <p>Należna kwota: <span style="font-weight:bold"><?php echo $sum.' zł' ?></span></p>
        </div>
        <div id="to_payment_payment">
                <form class='basket_next_button_to_payment' style="text-align:left">
                 <button style="display:none" class='basket_next_button_to_payment' id="do_podsumowania" type="submit" form="delivery">Do podsumowania</button>
                </form>
        </div>
</div>

<?php include_once "../view/footer.php"?>

</body>
</html> 