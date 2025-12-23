<?php
session_start();
require_once('lib/lib.inc');
$connect = db();
$ip = $_SERVER['HTTP_CLIENT_IP']?$_SERVER['HTTP_CLIENT_IP']:($_SERVER['HTTP_X_FORWARDED_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']);
if (isset($_SESSION)) {
   $query2 = "INSERT INTO UserLog (UL_IP, UL_User, UL_Accion) VALUES ('$ip','$_SESSION[email]','LOGOUT OK');";
   #print $query2;
   $result2 = mysqli_query($connect, $query2);

   $sid = session_id();
   unset($_SESSION);
   session_unset();
   session_destroy();
   header("location:index.php");
   #echo $sid;
}
?>
