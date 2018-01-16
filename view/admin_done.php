<?php

  session_start();

  if($_SESSION['login'] != 'Karolina')
  {
    header('Location: index.php');
    exit();
  }

?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Panel Administratora</title>
</head>
<body>

<?php include_once "../view/header.php"?>

<?php include_once "../view/menuwrapper.php"?>

<?php
try
{
    require_once("../PHP/db.php");
    $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );

    $sql="SELECT u.id_user, u.login, pur.order_date, pro.id_product, pro.name, purit.quantity, purit.price, pro.total_amount, s.status, pur.id_status
		FROM purchase as pur
        inner join purchase_item as purit on pur.id_purchase = purit.id_purchase
        inner join product as pro on pro.id_product = purit.id_product
        inner join status as s on s.id_status = pur.id_status
        inner join user as u on u.id_user = pur.id_user where s.id_status ='5' order by order_date DESC";

    $a_id_user=array();
    $a_login=array();
    $a_order_date=array();
    $a_id_product=array();
    $a_product=array();
    $a_quantity=array();
    $a_price=array();
    $a_id_status=array();
    $a_status=array();
    $a_total_amount=array();
    
    foreach ($conn->query($sql) as $row) 
    {
        array_push($a_id_user,$row['id_user']);
        array_push($a_login,$row['login']);
        array_push($a_order_date,$row['order_date']);
        array_push($a_id_product,$row['id_product']);
        array_push($a_product,$row['name']);
        array_push($a_quantity,$row['quantity']);
        array_push($a_price,$row['price']);
        array_push($a_id_status,$row['id_status']);
        array_push($a_status,$row['status']);
        array_push($a_total_amount,$row['total_amount']);
    }
    $max=count($a_login);
}
catch(PDOException  $e )
{
echo "Error: ".$e;
}

//header
    echo '
        <div id="order_history" style="grid-row:1">
            <h1 id="info"style="font-size: 30px">Historia zamówień</h1>
            <h1 id="info_500" style="font-size: 20px">Nie dostępna dla urządzeń z szerokością poniżej 800px</h1>
            <div class="admin_buttons">
                <div class="admin_button_1">
                    <a href="admin.php">Niezakończone</a>
                </div>

                <div class="admin_button_2">
                    <a href="admin_done.php">Zakończone</a>
                </div>
                
                <div class="admin_button_3">
                    <a href="admin_canceled.php">Anulowane</a>
                </div>   
            </div>

            <div class="admin_container">    
                <div class="admin_number_header">
                    <p>Login</p>
                </div>
                    
                <div class="admin_date_header">
                    <p>Data</p>
                </div>
            
                <div class="admin_name_header">
                    <p>Nazwa produktu</p>
                </div>    
                
                <div class="admin_quantity_header">
                    <p>Ilość kartonów</p>
                </div>
                    
                <div class="admin_price_header">
                    <p>Wartość</p>
                </div>
                    
                <div class="admin_status_header">
                    <p>Status</p>
                </div> 
            </div>
        </div>';

        if(isset($a_id_status[0]))
        {
            if($a_id_status[0] != 5)
            {

            }
            else
            {
                $first = '
                    <div class="admin_product">    
                        <div class="admin_number">
                            <p>'.$a_login[0].'</p>
                        </div>
                        
                        <div class="admin_date">
                            '.$a_order_date[0].'
                        </div>
                        
                        <div class="admin_name">
                            <p>'.$a_product[0].'</p>
                        </div>

                        <div class="admin_quantity">
                            <p>'.$a_quantity[0].'</p>
                        </div>
                    
                        <div class="admin_price">
                            <p>'.$a_price[0].' zł</p>
                        </div>

                        <div class="admin_status">
                            <p>'.$a_status[0].'</p>
                        </div>

                        <div>
                            <a href="../PHP/change_to_return.php?login='.$a_id_user[0].'&date='.$a_order_date[0].'&id='.$a_id_product[0].'"><img style="margin-top:5px" src="../Logo/back_grey.png"></img></a>
                        </div>
                    </div>';
                    echo $first;
            }
            
            $over_sum=$a_price[0];
            $over_sum = number_format($over_sum, 2, '.', '');

            if($a_order_date[0]!=$a_order_date[1] && $a_id_status[0] == 5 && isset($first))
            {
                echo'
                <div class="admin_product_overview">   
                <div class="admin_change_status_e">
                    <a href="../PHP/change_to_cancel.php?login='.$a_id_user[0].'&date='.$a_order_date[0].'"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>
                </div>

                <div class="admin_product_overview_sum">
                    <span style="padding-top:7px">'.$over_sum.'zł</span>
                </div>
            </div>';
            $over_sum=0; 
            }
        }
        else
        {
            echo "<div id='order_history' style='margin-top:30px;font-size:30px;font-weight:bold'>Nie zakończono jeszcze żadnego zamówienia</div>";
        }
    //pierwszy produkt
    for($i=1;$i<=$max-1;$i++)
    {
        $over_sum += $a_price[$i];
        $over_sum = number_format($over_sum, 2, '.', '');
        if($a_id_status[$i]!=5)
        {

        }
        else
        {
            echo '
                <div class="admin_product">    
                    <div class="admin_number">
                        <p>'.$a_login[$i].'</p>
                    </div>

                    <div class="admin_date">
                        '.$a_order_date[$i].'
                    </div>

                    <div class="admin_name">
                        <p>'.$a_product[$i].'</p>
                    </div>

                    <div class="admin_quantity">
                        <p>'.$a_quantity[$i].'</p>
                    </div>
                
                    <div class="admin_price">
                        <p>'.$a_price[$i].'<span> zł</span></p>
                    </div>

                    <div class="admin_status">
                        <p>'.$a_status[$i].'</p>
                    </div>

                    <div>
                        <a href="../PHP/change_to_return.php?login='.$a_id_user[0].'&date='.$a_order_date[0].'&id='.$a_id_product[$i].'"><img style="margin-top:5px" src="../Logo/back_grey.png"></img></a>
                    </div>
                </div>';
                
                if(array_key_exists(($i+1),$a_order_date))
                {
                    if($a_order_date[$i] == $a_order_date[$i+1] && $a_login[$i] == $a_login[$i+1])
                    {
                    }
                    else
                    {
                        echo'
                        
                        <div class="admin_product_overview">    
                        <div class="admin_change_status_e">
                            <a href="../PHP/change_to_cancel.php?login='.$a_id_user[0].'&date='.$a_order_date[0].'"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>
                        </div>

                            <div class="admin_product_overview_sum">
                                <span style="padding-top:7px">'.$over_sum.'zł</span>
                            </div>
                        </div>';
                        $over_sum=0; 
                    }
                }
                else
                {
                    echo'
                    <div class="admin_product_overview">    
                    <div class="admin_change_status_e">
                        <a href="../PHP/change_to_cancel.php?login='.$a_id_user[0].'&date='.$a_order_date[0].'"><i class="fa fa-trash fa-2x" aria-hidden="true"></i></a>
                    </div>

                        <div class="admin_product_overview_sum">
                            <span style="padding-top:7px">'.$over_sum.'zł</span>
                        </div>
                    </div>';
                    $over_sum=0; 
                }
            }
    }


include_once "../view/footer.php"?>
<script>
    var width=$( window ).width();
        if(width<782)
            {
                $("#info").text("Panel administratora");
                $(".admin_buttons").css("display","none");
                $(".admin_container").css("display","none");
                $(".admin_product").css("display","none");
                $(".admin_product_overview").css("display","none");
        };

    $(document).ready(function(){
        $(window).resize(function(){
            var width=$( window ).width();
            if(width<800)
            {
                $("#info").text("Panel administratora");
                $(".admin_buttons").css("display","none");
                $(".admin_container").css("display","none");
                $(".admin_product").css("display","none");
                $(".admin_product_overview").css("display","none");
            }
            else
            {
                $(".admin_buttons").css("display","grid");
                $(".admin_container").css("display","grid");
                $(".admin_product").css("display","grid");
                $(".admin_product_overview").css("display","grid");
            }
        })
    });
</script>
</body>
</html> 