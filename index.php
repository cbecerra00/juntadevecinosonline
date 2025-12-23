<?php
error_reporting(E_ALL);
#error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("session.cookie_domain", ".juntadevecinosvirtual.cl");
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

    <title>Junta de Vecinos Virtual - Creando Comunidad</title>
    <meta name="Description" content="Junta de Vecinos Virtual es un proyecto de asistencia a las Juntas de Vecinos y a su gesti&oacute;n en apoyo de las comunidades locales.">
    <meta name="Keywords" content="Junta, Vecinos, Comunidad, Chile, Barrio, Ayuda, Tecnologia">

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

                <p align="center"><img src="img/mankecura_logo1_Fotor.png" width="250" alt="Junta de Vecinos Virtual Logo"></p>
                <p align="justify">
                    Junta de Vecinos Virtual es un proyecto de asistencia en la administraci&oacute;n de las juntas de vecinos y su importante labor en los territorios y barrios.
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

                        <a href="#">
                            <small>Forgot password?</small>
                        </a>
                    </form>
                    <p class="m-t">
                        <small>Junta de Vecinos Virtual from <a href="https://www.loopbackpro.com" target="_blank">Loopback SpA</a> &copy; 2015-<?php echo date('Y'); ?></small>
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
