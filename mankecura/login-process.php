<?php
error_reporting(E_ALL);
ini_set("session.cookie_domain", ".mankecura.com");
session_start();
require_once('lib/lib.inc');
$connect = db();
$resp = array();
$ip = getUserIpAddr();

if (isset($_POST['Username'])) {
   $txt_u = $_POST['Username'];
   $txt_p = $_POST['Password'];
   $query = "SELECT U.User_Password from User U WHERE U.User_Email='$txt_u';";
   #print "$query\n";
   $result = mysqli_query($connect, $query);
   while($row_u = mysqli_fetch_array($result)) {
      if (password_verify($_POST['Password'], $row_u[0])) {
         #print "EXITO!!\n";
         $query = "SELECT U.User_ID,U.User_Nombre,U.User_Apellidos,U.User_Cargo,UT.Titular_Titu_ID,T.Titu_Nombre,U.User_Email from User U, User_Titular UT, Titular T WHERE T.Titu_ID=UT.Titular_Titu_ID AND UT.User_User_ID=U.User_ID AND U.User_Email='$txt_u' ORDER BY T.Titu_Nombre ASC LIMIT 0,1;";
         #print "$query\n";
         $result = mysqli_query($connect, $query);
         while($row_e = mysqli_fetch_array($result)) {
            $_SESSION["login"] = $_POST['Username'];
            $_SESSION["unom"] = $row_e[1]." ".$row_e[2];
            $_SESSION["cargo"] = $row_e[3];
            $_SESSION["uid"] = $row_e[0];
            $_SESSION["cid"] = $row_e[4];
            $_SESSION["email"] = $row_e[6];
            setcookie("Mankecura", $_SESSION["login"], time()+3600);
            $query2 = "INSERT INTO UserLog (UL_IP, UL_User, UL_Accion) VALUES ('$ip','$txt_u','ACCESO OK');";
            #print $query2;
            $result2 = mysqli_query($connect, $query2);
         #   header ("Location: dashboard.php?c=$row_e[4]&" . session_name() . "=" . session_id());
         #   header("location:dashboard.php?c=$row_e[4]");
            $resp['status']=true;
            $resp['url']= "dashboard.php?c=".$row_e[4];
            #$resp['url']= "dashboard.php";
            #echo json_encode($resp);
            echo "1";	
         }
      } else {
         $query2 = "INSERT INTO UserLog (UL_IP, UL_User, UL_Accion) VALUES ('$ip','$txt_u','ERROR \($txt_u/$txt_p\)');";
         $result2 = mysqli_query($connect, $query2);
         #print "ERROR!!\n";
         $resp['status']=false;
         $resp['msg']= "Error. Ingrese las credenciales correctas";
         #echo json_encode($resp);
         echo $resp['msg'];
      }
   }
}
?>
