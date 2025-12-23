<?php
error_reporting(E_ALL);
#error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("session.cookie_domain", ".mankecura.com");
session_start();
require('lib/lib.inc');
$connect = db();
$anio = date('Y');
$ip = getUserIpAddr();
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MANKECURA - Strategic Assets Management</title>
    <meta name="Description" content="Mankecura es un proyecto de asistencia en la administracion y seguimiento de activos referenciados por tecnologias GIS.">
    <meta name="Keywords" content="Assets, Activos, Estrategico, Strategic, Agua, Water, Propiedad Minera, Mining Property, Los colegios Manquecura no me gustan">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">



</head>

<body class="gray-bg">
    <!-- Mainly scripts -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
    
    <script src="js/bootstrap.min.js"></script>
    <script src="js/login-process.js"></script>

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">Bienvenido</h2>

                <p align="center"><img src="img/mankecura_logo1_Fotor.png" width="250" alt="Mankecura Logo"></p>
                <p align="justify">
                    Mankecura es un proyecto de asistencia en la administraci&oacute;n y seguimiento de activos referenciados por tecnolog&iacute;as GIS.
                </p>

                <p align="justify">
                    <small>Esta versi&oacute;n es para pruebas y contiene datos ficticios. Pronto estaremos disponible con la informaci&oacute;n relevante para su negocio.</small>
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form id="login-form" class="form-signin" method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Username" required="" id="Username" name="Username">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" required="" id="Password" name="Password">
                        </div>
                        <div id="error">
                          <!-- error will be shown here -->
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b" id="btn-login" name="btn-login">Login</button>
<!--
                        <button type="button" class="btn btn-success block full-width m-b" id="btn-linkedin"><i class="fa fa-linkedin-square"></i> Login</button>
-->

                        <a href="#">
                            <small>Forgot password?</small>
                        </a>
                    </form>
                    <p class="m-t">
                        <small>Mankecura from <a href="https://www.loopbackpro.com" target="_blank">Loopback SpA</a> &copy; 2015-<?php echo date('Y'); ?></small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Loopback SpA
            </div>
            <div class="col-md-6 text-right">
               <small>&copy; 2015-<?php echo date('Y'); ?></small>
            </div>
        </div>
    </div>

</body>

</html>

<?php
#require('lib/oauth_client.php');
#require('lib/http.php');
#if ($_SESSION["login"]) {
#   $usuario = $_SESSION["unom"];
#   #header("location:dashboard.php");
#}
 
#$client = new oauth_client_class;
#$client->debug = false;
#$client->debug_http = true;
#$client->redirect_uri = REDIRECT_URL;
 
#$client->client_id = API_KEY;
#$application_line = __LINE__;
#$client->client_secret = SECRET_KEY;
 
#if (strlen($client->client_id) == 0 || strlen($client->client_secret) == 0)
#  die('Please go to LinkedIn Apps page');
 
#$client->scope = "r_basicprofile r_emailaddress";
#if (($success = $client->Initialize())) {
#  if (($success = $client->Process())) {
#    if (strlen($client->authorization_error)) {
#      $client->error = $client->authorization_error;
#      $success = false;
#    } elseif (strlen($client->access_token)) {
#      $success = $client->CallAPI(
#					'http://api.linkedin.com/v1/people/~:(id,email-address,first-name,last-name,picture-url,public-profile-url,formatted-name,positions)', 
#					'POST', array(
#						'format'=>'json'
#					), array('FailOnAccessError'=>true), $user);
#    }
#  }
#  $success = $client->Finalize($success);
#}
#if ($client->exit) exit;
#
#if ($success) {
#   print_r($user);
#   $result = '<h1>LinkedIn Profile Details </h1>';
#   $result .= '<img src="'.$user->pictureUrl.'">';
#   $result .= '<br/>LinkedIn ID : ' . $user->id;
#   $result .= '<br/>Email  : ' . $user->emailAddress;
#   $result .= '<br/>Name : ' . $user->firstName.' '.$user->lastName;
#   $result .= '<br/>Location : ' . $user->positions->values[0]->location->name;
#   $result .= '<br/>Positions : ' . $user->positions->values[0]->company->name;
#   $result .= '<br/>Logout from <a href="logout.php">LinkedIn</a>';
#   echo '<div>'.$result.'</div>';
#}
?>
