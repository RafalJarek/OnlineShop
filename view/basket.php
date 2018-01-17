<?php
  session_start();
  if(isset($_SESSION['basketfailed']))
  {
    echo $_SESSION['basketfailed'];
    unset($_SESSION['basketfailed']);
  } 
?>
<!DOCTYPE html>
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-git.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
<title>Koszyk</title>
</head>
<body>

<?php 
include_once "../view/header.php";
include_once "../view/menuwrapper.php";
if(isset($_SESSION['e_basket_already']))
{
        echo'<div class="error" style="text-align:center">'.$_SESSION['e_basket_already'].'</div>'.'<br />';
        unset($_SESSION['e_basket_already']);
}
if(!isset($_SESSION['login']))
{
    echo "<div style='font-weight:bold;text-align:center;margin-top:30px;margin-bottom:30px'>Nie jesteś zalogowany</div>";
}
else
{
    try
    {
        require_once("../PHP/db.php");

        $login = $_SESSION['login'];

        $conn = new PDO('mysql:host='. $host .';dbname='. $db_name , $db_user,$db_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        
        $sql = "SELECT id_user from user where login='$login'";
        foreach ($conn->query($sql) as $row) 
        {
            $id_user = $row['id_user'];
        }

        $sql="SELECT 
            pu.id_purchase, p.id_product, p.name, p.code, p.price, p.total_amount, cat.name as category_name, col.color, cart.quantity, pi.quantity as cartons, i.file_name, s.size
            FROM product as p
            inner join carton as cart on p.id_carton=cart.id_carton
            inner join color as col on p.id_color=col.id_color 
            inner join size as s on p.id_size=s.id_size
            inner join image as i on p.id_image=i.id_image
            inner join category as cat on p.id_category=cat.id_category
            inner join purchase_item as pi on pi.id_product=p.id_product
            inner join purchase as pu on pi.id_purchase=pu.id_purchase
            where pu.id_user='$id_user' and pu.id_status=1";
        
        $id_purchase=array();
        $id_product=array();
        $name=array();
        $code=array();
        $price=array();
        $color=array();
        $cartons=array();
        $quantity=array();
        $file_name=array();
        $size=array();
        $amount=array();
        $category=array();
        $sum=array();

        foreach ($conn->query($sql) as $row) 
        {
            array_push($id_purchase,$row['id_purchase']);
            array_push($id_product,$row['id_product']);
            array_push($name,$row['name']);
            array_push($code,$row['code']);
            array_push($price,$row['price']);
            array_push($color,$row['color']);
            array_push($cartons,$row['cartons']);
            array_push($quantity,$row['quantity']);
            array_push($file_name,$row['file_name']);
            array_push($size,$row['size']);
            array_push($amount,$row['total_amount']);
            array_push($category,$row['category_name']);
        }
        $max=count($id_product);
    }
    catch(PDOException  $e )
    {
    echo "Error: ".$e;
    }
    {
        if(isset($_SESSION['e_amount<']))
            {
                echo'<div class="error" style="text-align:center"><br>'.$_SESSION['e_amount<'].'</div>';
                unset($_SESSION['e_amount<']);
            }

        if(isset($_SESSION['e_amount0']))
            {
                echo'<div class="error" style="text-align:center">'.$_SESSION['e_amount0'].'</div>'.'<br />';
                unset($_SESSION['e_amount0']);
            }

           if(isset($_SESSION['e_amount<']))
            {
                echo'<div class="error" style="text-align:center">'.$_SESSION['e_amount<'].'</div>'.'<br />';
                unset($_SESSION['e_amount<']);
            }

    ?> 
        <div class="basket_container">
        <h1 style="grid-column:2/8">Produkty w koszyku</h1>     
        
        <div class="basket_image_header">
            <p>Obraz</p>
        </div>
            
        <div class="basket_description_header">
            <p>Opis</p>
        </div>

        <div class="basket_pair_quantity_header">
            <p>Ilość par</p>
        </div>    
        
        <div class="basket_price_header">
            <p>Cena</p>
        </div>
        
        <div class="basket_quantity_header">
            <p>Ilość kartonów</p>
        </div>

        <div class="basket_quantity_header_500">
            <p>Ilość</p>
        </div>
            
        <div class="basket_value_header">
            <p>Wartość</p>
        </div>
            
        <div class="basket_delete_header">
            <p>Usuń</p>
        </div>                   
        </div>
        <?php
        if($max==0)
        {
            echo "<div style='font-weight:bold;text-align:center;margin-top:30px;margin-bottom:30px'>Nie dodałeś jeszcze produktów do koszyka</div>";
        }
        else
        {
            for($i=0;$i<=$max-1;$i++)
            {
                $suma=$quantity[$i]*$cartons[$i]*$price[$i];
                $suma = number_format($suma, 2, '.', '');
                echo "
            <div class='basket_product'>
                <div class='basket_image'>
                    <a href='product.php?name=$name[$i]&category=$category[$i]'><img id='basket_img' src=$file_name[$i]></a>
                </div>
                
                <div class='basket_description'>
                    <div class='basket_image_500'>
                        <a href='product.php?name=$name[$i]&category=$category[$i]'><img id='basket_img' src=$file_name[$i]></a>
                    </div>
                    <a style='font-weight:bold' href='product.php?name=$name[$i]&category=$category[$i]'><p>$name[$i]</p></a> 
                    <p>kod produktu: $code[$i]</p>
                    <p>kolor: $color[$i]</p>
                    <div class='basket_description_price_quantity'>
                        <div class='basket_pair_quantity_land' id='basket_pair_quantity$i'>
                            $quantity[$i] 
                        </div>
                        <span style='color:#50cec4;padding:0 5px 0 5px;font-weight:bold'> x </span>
                        <div class='basket_price_land' id='basket_price$i'>
                            $price[$i] zł
                        </div>  
                    </div>
                    <div class='basket_description_price_quantity_sum'>
                        <div class='basket_pair_quantity_land' id='basket_pair_quantity$i'>
                            $quantity[$i] 
                        </div>
                        <span style='color:#50cec4;padding:0 1px 0 1px;font-weight:bold'> x </span>
                        <div class='basket_price_land' id='basket_price$i'>
                            $price[$i] zł
                        </div>  
                        <span style='color:#50cec4;padding:0 1px 0 1px;font-weight:bold'> = </span>
                        <div class='basket_price_land' id='basket_price$i'>
                            $suma zł
                        </div>  
                    </div>
                </div>

                <div class='basket_pair_quantity' id='basket_pair_quantity$i'>$quantity[$i]
                </div>  

                <div class='basket_price' id='basket_price$i'>$price[$i] zł        
                </div>
                
                <div class='basket_quantity'>";

                array_push($sum,$suma);
            echo"
                    <button class='plus' id='plus$i'>+</button>
                    <input class='class_input_basket_quantity' id='input_basket_quantity$i' type='textbox' readonly value=$cartons[$i] min='1'> 
                    <button class='minus' id='minus$i'>-</button>     
                </div>
                
                <div class='basket_price_overview'><span id='basket_value$i'>Wartość</span> zł
                </div>

                <script>
                
                    document.getElementById('basket_value$i').innerHTML = (parseFloat(document.getElementById('basket_pair_quantity$i').innerHTML) * parseFloat(document.getElementById('input_basket_quantity$i').value) * parseFloat(document.getElementById('basket_price$i').innerHTML)).toFixed(2);
                    $('#plus$i').click(function()
                    {   
                        $('#input_basket_quantity$i').val(function(i, oldval)
                        {
                            if($cartons[$i]<$amount[$i])
                            return ++oldval;
                            else
                            return $cartons[$i];
                        });
                        if($cartons[$i]!==$amount[$i])
                        window.location.href = '../PHP/plus_quantity.php?id=$id_product[$i]&name=$id_purchase[$i]&quan=$cartons[$i]&il=$i';
                        else
                        alert('Nie mamy więcej na stanie');
                        document.getElementById('basket_value$i').innerHTML = (parseFloat(document.getElementById('basket_pair_quantity$i').innerHTML) * parseFloat(document.getElementById('input_basket_quantity$i').value) * parseFloat(document.getElementById('basket_price$i').innerHTML)).toFixed(2);  
                    });
                    $('#minus$i').click(function()
                    {
                        $('#input_basket_quantity$i').val(function(i, oldval)
                        {
                            if($cartons[$i]>1)
                            return --oldval;
                            else
                            return 0
                        });
                        if($cartons[$i]>0)
                        window.location.href = '../PHP/minus_quantity.php?id=$id_product[$i]&name=$id_purchase[$i]&quan=$cartons[$i]&il=$i';
                        document.getElementById('basket_value$i').innerHTML = (parseFloat(document.getElementById('basket_pair_quantity$i').innerHTML) * parseFloat(document.getElementById('input_basket_quantity$i').value) * parseFloat(document.getElementById('basket_price$i').innerHTML)).toFixed(2);  
                    });
                    
                    </script>  

                <div class='basket_delete'>
                    <form action='../PHP/delate_basket.php' target='_self' method='POST'>
                    <button type='submit' name='delete_basket' value=$id_purchase[$i]><i class='fa fa-times fa-2x' aria-hidden='true'></i></button>         
                    </form>
                </div>
                
            </div>";
            }
        }
    }
    $value= array_sum($sum);
    $value = number_format($value, 2, '.', '');
        echo"
        <div class='basket_overview'>
        <div class='basket_value_description'>
            <p>Wartość zamówienia:</p>
        </div>

        <div class='basket_shipment_description'>
            <p>Koszt dostawy od:</p>
        </div>

        <div class='basket_value_total'><span id='basket_value_total'>$value</span> zł
        </div>

        <div class='basket_shipment_value'>+ <span id='basket_shipment_value'>0</span> zł
        </div>

        <div class='basket_value_payment'>
            <p>Do zapłaty:</p>
        </div>

        <div class='basket_value_overview'><span id='basket_value_overview'>$value</span> zł
        </div>
        
        <form class='basket_continue_button' action='index.php'>
            <button type='submit'>kontynuuj zakupy</button>    
        </form>

        <form class='basket_next_button' action='../view/to_payment.php' method='POST'>
            <button name=sum value=$value>Do płatności</button>
            <p style='text-align:left'>Dodaj komentarz do zamówienia:</p>
            <textarea name=comment maxlength=255 style='width:90%;height:60px'></textarea>
        </form>";
        ?>
    
    </div><?php
}
include_once '../view/footer.php';
?>

</body>
</html>