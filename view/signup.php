<?php

  session_start();

  if(isset($_POST['email']))
  {
    require_once("../PHP/validationsignup.php");

    //zapamiętaj wprowadzone juz dane
    $_SESSION['a_login']=$login;
    $_SESSION['a_email']=$email;
    $_SESSION['a_haslo1']=$haslo1;
    $_SESSION['a_haslo2']=$haslo2;
    if(isset($_POST['regulamin'])) $_SESSION['a_regulamin']=true;
    $_SESSION['a_firstname']=$firstname;
    $_SESSION['a_surname']=$surname;
    $_SESSION['a_companyname']=$companyname;
    $_SESSION['a_nip']=$nip;
    $_SESSION['a_phone']=$phone;
    $_SESSION['a_city']=$city;
    $_SESSION['a_postcode']=$postcode;
    $_SESSION['a_street']=$street;
    $_SESSION['a_buildingnumber']=$buildingnumber;
    $_SESSION['a_localnumber']=$localnumber;
    
    

    //connect mysql
    require_once "../PHP/db.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    require_once "../PHP/insert_user.php";
  }
?>

<!DOCTYPE html>  
<html lang="pl">
<meta charset="utf-8"/>
<?php include_once "../PHP/style.php"?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-git.js"></script>
<title>Rejestracja</title>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="../config/changeletters.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="../config/search.js"></script>
</head>
<body>
        
<?php include_once "header.php"?>

<?php include_once "menuwrapper.php"?>

<form method="post">
<div class="signup_page">
                <div id="account_details">
                        <h2 style="text-align: center">Twoje konto</h2>
                        <div>Login<span>*</span>: 
                        <input type="text" value="<?php
                                if(isset($_SESSION['a_login']))
                                {
                                echo $_SESSION['a_login'];
                                unset($_SESSION['a_login']);
                                }
                                ?>" name="login"/><br />
                                
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
                        <div>Adres E-mail<span>*</span>: <input type="text" value="<?php
                                if(isset($_SESSION['a_email']))
                                {
                                echo $_SESSION['a_email'];
                                unset($_SESSION['a_email']);
                                }
                                ?>" name="email"/><br />
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
                        </div>
                        <div>Hasło<span>*</span>: <input type="password" value="<?php
                                if(isset($_SESSION['a_haslo1']))
                                {
                                echo $_SESSION['a_haslo1'];
                                unset($_SESSION['a_haslo1']);
                                }
                                ?>" name="haslo1"/><br />
                                <?php
                                if(isset($_SESSION['e_haslo_strlen']))
                                {
                                        echo'<div class="error">'.$_SESSION['e_haslo_strlen'].'</div>'.'<br />';
                                        unset($_SESSION['e_haslo_strlen']);
                                }
                                ?>
                        </div>
                        <div>Powtórz hasło<span>*</span>: <input type="password" value="<?php
                                if(isset($_SESSION['a_haslo2']))
                                {
                                echo $_SESSION['a_haslo2'];
                                unset($_SESSION['a_haslo2']);
                                }
                                ?>" name="haslo2"/><br />
                                <?php
                                if(isset($_SESSION['e_haslo_thesame']))
                                {
                                echo'<div class="error">'.$_SESSION['e_haslo_thesame'].'</div>'.'<br />';
                                unset($_SESSION['e_haslo_thesame']);
                                }
                                ?>
                        </div>
                </div>
                
                <div id="contact_details">
                        <h2 style="text-align: center">Dane kontaktowe</h2>
                        <div>Imię<span>*</span>: <input id="change" type="text" name="firstname" value="<?php
                                if(isset($_SESSION['a_firstname']))
                                {
                                        echo $_SESSION['a_firstname'];
                                        unset($_SESSION['a_firstname']);
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
                        <div>Nazwisko<span>*</span>: <input id="change" type="text" name="surname" value="<?php
                                if(isset($_SESSION['a_surname']))
                                {
                                        echo $_SESSION['a_surname'];
                                        unset($_SESSION['a_surname']);
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
                        <div>Nazwa firmy<span>*</span>: <input type="text" name="companyname" value="<?php
                                if(isset($_SESSION['a_companyname']))
                                {
                                        echo $_SESSION['a_companyname'];
                                        unset($_SESSION['a_companyname']);
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
                        <div>Nip<span>*</span>: <input type="text" name="nip" value="<?php
                                if(isset($_SESSION['a_nip']))
                                {
                                        echo $_SESSION['a_nip'];
                                        unset($_SESSION['a_nip']);
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
                        <div>Telefon<span>*</span>: <input type="text" name="phone" value="<?php
                                if(isset($_SESSION['a_phone']))
                                {
                                        echo $_SESSION['a_phone'];
                                        unset($_SESSION['a_phone']);
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

                <div id="recaptcha">
                <div class="g-recaptcha" data-sitekey="6Lcy3zwUAAAAAJRrbDVkKXCp-X0Arna0f0vRbmba"></div>
                        <?php
                        if(isset($_SESSION['e_bot']))
                        {
                                echo'<div class="error">'.$_SESSION['e_bot'].'</div>'.'<br />';
                                unset($_SESSION['e_bot']);
                        }
                        ?>
                </div>
                <div id="delivery_address">
                        <h2 style="text-align: center">Adres dostawy</h2>
                        <div>Kraj<span>*</span>: <select name="country"><option value="Polska">Polska</option></select></div>
                        <div>Miejscowość<span>*</span>: <input id="change" type="text" name="city" value="<?php
                                if(isset($_SESSION['a_city']))
                                {
                                        echo $_SESSION['a_city'];
                                        unset($_SESSION['a_city']);
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
                        <div>Kod pocztowy<span>*</span>: <input type="text" name="postcode" value="<?php
                                if(isset($_SESSION['a_postcode']))
                                {
                                        echo $_SESSION['a_postcode'];
                                        unset($_SESSION['a_postcode']);
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
                        <div>Ulica<span>*</span>: <input id="change" type="text" name="street" value="<?php
                                if(isset($_SESSION['a_street']))
                                {
                                        echo $_SESSION['a_street'];
                                        unset($_SESSION['a_street']);
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
                        <div>Numer budynku<span>*</span>: <input type="text" name="buildingnumber" value="<?php
                                if(isset($_SESSION['a_buildingnumber']))
                                {
                                        echo $_SESSION['a_buildingnumber'];
                                        unset($_SESSION['a_buildingnumber']);
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
                        <div>Numer lokalu: <input type="text" name="localnumber" value="<?php
                                if(isset($_SESSION['a_localnumber']))
                                {
                                        echo $_SESSION['a_localnumber'];
                                        unset($_SESSION['a_localnumber']);
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
                
                <div id="rules">
                <label>
                        <input type="checkbox" name="regulamin"
                        <?php
                        if(isset($_SESSION['a_regulamin']))
                        {
                        echo "checked";
                        unset($_SESSION['a_regulamin']);
                        }
                        ?>
                        >Akceptuję warunki 
                        <button style="border:none;background-color:inherit;outline:none;text-decoration:underline" type="button" id="rules_button">regulaminu.</button>
                        <span>*</span>

                        <div id="rules_modal" class="modal">
                                <div class="modal-content">
                                        <span class="modal_close">&times;</span>
                                        <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum a metus quis dui lobortis egestas vitae ac lorem. Ut fringilla posuere mauris, sit amet venenatis neque auctor eget. Praesent ac blandit diam, et feugiat ex. Proin nec hendrerit leo. Mauris id purus pulvinar, ultricies nisi id, egestas lacus. Donec auctor non lorem rhoncus rhoncus. Aenean in nisl sem.
                                        </p>
                                        <p>
                                        Nam tincidunt mauris vel ligula lacinia mattis. Proin eu ullamcorper tellus, non cursus arcu. Suspendisse a felis eget dui sollicitudin venenatis. Praesent sit amet mollis mauris, posuere aliquam urna. Phasellus et tincidunt nisl. Suspendisse quis mollis nunc. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras eu nunc erat. Donec facilisis quam vitae imperdiet finibus. Suspendisse eget erat ullamcorper, commodo justo eu, eleifend mauris. Suspendisse auctor efficitur finibus. Nullam porta nulla a rutrum interdum. Mauris eu nibh risus. Donec malesuada, arcu non lobortis porta, tellus sapien rutrum leo, ut varius elit orci id neque.
                                        </p>
                                        <p>
                                        Duis neque elit, porta eget massa et, volutpat egestas dolor. Sed eget eros est. Ut suscipit, quam vitae molestie pharetra, nibh ipsum varius dolor, a convallis nibh nunc non mi. Mauris faucibus erat vitae suscipit pellentesque. Vestibulum semper mauris in neque posuere hendrerit. Aliquam eu nisl vulputate, viverra ex ut, mattis arcu. Nam maximus, dolor ac ultrices porttitor, nisl sapien hendrerit sem, id mattis massa sapien non ante. Cras pulvinar libero quis mollis convallis. Nunc sed dui lobortis, aliquet ipsum eu, venenatis risus. Nullam interdum, justo sed placerat finibus, orci ante pulvinar nisi, et dignissim velit ligula in ex. Cras eget risus a velit lobortis facilisis vitae consequat nunc.
                                        </p>
                                        <p>
                                        Integer ullamcorper nulla eu vehicula mattis. Fusce vel arcu eu nisi eleifend mattis rutrum sed lectus. Sed ac ligula mi. Morbi elit ex, laoreet ut lorem vel, placerat maximus eros. Sed non ornare est, non porta felis. Donec dui magna, sodales ultrices magna at, suscipit cursus lectus. Praesent eget hendrerit lectus. Morbi odio justo, scelerisque pharetra sem eu, commodo feugiat ante. Nulla facilisi. Quisque volutpat enim orci. Morbi non facilisis urna. Integer convallis libero at turpis mollis, a rhoncus arcu placerat.
                                        </p>
                                        <p>
                                        Nullam finibus arcu eget est vehicula, in auctor diam tristique. Donec vitae magna eget leo tempus vestibulum nec a purus. Sed rutrum, sapien eu hendrerit dignissim, mi dui efficitur diam, in pretium elit nunc non leo. Etiam ut lacus ac purus aliquet rutrum. Integer quis sapien condimentum, volutpat mi non, auctor dui. Nam tincidunt, elit eu rhoncus posuere, eros purus malesuada nulla, at placerat dolor justo eget neque. Phasellus eget tellus varius, porttitor elit nec, venenatis neque. Praesent pulvinar sem et orci bibendum, nec tempus mi pellentesque. Sed nulla leo, vehicula at elementum nec, porta nec ante. Suspendisse gravida nisi leo, sed dignissim orci blandit eu. Quisque mattis tortor vitae mauris tempor pulvinar. Proin velit ex, aliquet eu ligula id, fringilla volutpat quam. Donec aliquet sem vitae neque molestie, sed maximus nunc iaculis. Ut posuere turpis at nibh dignissim, vel accumsan urna tincidunt. Sed sit amet bibendum enim, at consequat enim. Nulla semper urna eget lorem tristique ultricies.
                                        </p>
                                </div>
                        </div>

                        <script>
                        var modal = document.getElementById('rules_modal');

                        var button = document.getElementById("rules_button");

                        var span = document.getElementsByClassName("modal_close")[0];

                        button.onclick = function() {
                        modal.style.display = "block";
                        }

                        span.onclick = function() {
                        modal.style.display = "none";
                        }

                        window.onclick = function(event) {
                        if (event.target == modal) {
                        modal.style.display = "none";
                        }
                        }
                        </script>
                </label>
                <?php
                if(isset($_SESSION['e_regulamin']))
                {
                        echo'<div class="error">'.$_SESSION['e_regulamin'].'</div>'.'<br />';
                        unset($_SESSION['e_regulamin']);
                }
                ?>
                </div>               
                
                <button id="signup_button" type="submit">Zarejestruj konto</button>
                <div id="signup_info"><span>*</span> są wymagane</div>                
</div>
</form>

<?php include_once "footer.php"?>

</body>
</html> 