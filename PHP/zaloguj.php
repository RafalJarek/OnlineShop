<?php

    session_start();

    require_once "db.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try
    {
        $connect = new mysqli($host,$db_user,$db_password,$db_name);
        mysqli_set_charset($connect, "utf8"); 

        if($connect->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        }
        else
        {
            $login = $_POST['login'];
            $haslo = $_POST['haslo'];
            
            $login = htmlentities($login,ENT_QUOTES,"UTF-8");
            
            if($result = @$connect->query(
            sprintf("SELECT * FROM user where login='%s'",
            mysqli_real_escape_string($connect,$login))))
            {
                $users_quantity = $result->num_rows;
                if($users_quantity>0)
                {
                    $assoc = $result->fetch_assoc();
                
                    if(password_verify($haslo,$assoc['password']))
                    {
                        $_SESSION['loggedin']=true;

                        $_SESSION['login'] = $assoc['login'];

                        unset($_SESSION['errorlogin']);
                        $result->free_result();
                        ?>
                        <script type="text/javascript">
                        window.location.href = 'https://inzynier.000webhostapp.com/view/index.php';
                        </script>
                        <?php
                    }
                    else
                    {
                        $_SESSION['errorlogin']= '<span style="color:red">Nieprawidłowy login bądź hasło</span'."<br>";
                        ?>
                        <script type="text/javascript">
                        window.location.href = 'https://inzynier.000webhostapp.com/view/login.php';
                        </script>
                        <?php
                    }
                }
                else
                {
                    $_SESSION['errorlogin']= '<span style="color:red">Nieprawidłowy login bądź hasło</span'."<br>";
                    ?>
                    <script type="text/javascript">
                    window.location.href = 'https://inzynier.000webhostapp.com/view/login.php';
                    </script>
                    <?php 
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

