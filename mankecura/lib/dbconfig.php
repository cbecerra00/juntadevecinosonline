<?php
$db_host = "localhost";
$db_name = "mankecur_web";
$db_user = "mankecur_admin";
$db_pass = "I94ySixcS6UF";

try{
   $db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
   $db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e){
   echo $e->getMessage();
}
?>
