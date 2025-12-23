<?php
function top() {
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="initial-scale=1,maximum-scale=1,user-scalable=no">

    <title>MANKECURA | Dashboard</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!--
    <link href="font-awesome/css/fontawesome.css" rel="stylesheet">
    <link href="font-awesome/css/bands.css" rel="stylesheet">
    -->
    <script src="https://kit.fontawesome.com/37b4494dd2.js" crossorigin="anonymous"></script>
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <link href="https://api.mapbox.com/mapbox-gl-js/v3.15.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.15.0/mapbox-gl.js"></script>

<!--
    <script src="js/jquery-2.1.1.js"></script>
-->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        
    <style>
    #mapboxgl-popup-content { background:#f8ac59; }
    #map {
        height: 400px;
        width: 90%;
    }
    #map2 {
        height: 400px;
        width: 90%;
    }
    #map2 img {
        max-width: none;
        min-width: 0px;
        height: auto;
        margin: auto;
    }
    </style>

</head>

<body>
    <div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="img/a4_small.jpg" />
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php $_SESSION["unom"]; ?></strong>
                             </span> <span class="text-muted text-xs block"><?php $_SESSION["cargo"]; ?> <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="profile.php?c=<?php $_GET["c"]; ?>">Profile</a></li>
                            <li class="divider"></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                    </div>
                </li>
<?php
$connect = db();
$query_c = "SELECT T.Titu_ID,T.Titu_Nombre FROM User_Titular UT, Titular T WHERE T.Titu_ID=UT.Titular_Titu_ID AND UT.USer_User_ID=".$_SESSION["uid"]." ORDER BY T.Titu_Nombre ASC;";
#print $query_c."\n";
$result_c = mysqli_query($connect, $query_c);
if (mysqli_num_rows($result_c) > 1) {
?>
                <li>
                  <div class="btn-group">
                    <button data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Selecciona Titular <span class="caret"></span></button>
                    <ul class="dropdown-menu">
<?php
   while($row_c = mysqli_fetch_array($result_c)) {
      if ($row_c[0] == $_GET["c"]) $tbold = ' class="font-bold"'; else $tbold = '';
?>
                      <li><a href="dashboard.php?c=<?php echo $row_c[0]; ?>"<?php echo $tbold; ?>><?php echo $row_c[1]; ?></a></li>
<?php
   }
?>
                    </ul>
                  </div>
                </li>
<?php
}
?>
                <li>
                    <a href="dashboard.php?c=<?php echo $_GET["c"]; ?>"><i class="fa fa-home"></i> <span class="nav-label">Home</span></a>
                </li>
                <li class="active">
                    <a href="#"><i class="fa fa-th-large"></i> <span class="nav-label">Proyectos</span> <span class="fa arrow"></span></a>
<?php
$connect = db();
$c = $_GET["c"];
$query_p = "SELECT Proy_ID,Proy_Nombre FROM Proyecto WHERE Titular_Titu_ID=$c ORDER BY Proy_Nombre ASC;";
#print $query_p."\n";
$result_p = mysqli_query($connect, $query_p);
while($row_p = mysqli_fetch_array($result_p)) {
   $act="";
   if(!isset($_GET["p"])) {$p = 0;} else {$p = $_GET["p"];}
   if ($p == $row_p[0]) { $act = " class=\"active\""; }
?>
                    <ul class="nav nav-second-level">
                        <li<?php $act; ?>>
                            <a href="proj.php?c=<?php echo $_GET["c"] ;?>&p=<?php echo $row_p[0]; ?>"><?php echo $row_p[1]; ?></a>
                        </li>
                    </ul>
<?php
}
?>
                </li>
                <li>
                    <a href="#"><i class="fa-solid fa-chart-line"></i> <span class="nav-label">Finanzas</span><span class="fa-regular fa-arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="graph_flot.html">Flot Charts</a></li>
                        <li><a href="graph_morris.html">Morris.js Charts</a></li>
                        <li><a href="graph_rickshaw.html">Rickshaw Charts</a></li>
                    </ul>
                </li>
                <li>
                    <a href="mailbox.html"><i class="fa fa-envelope"></i> <span class="nav-label">Mailbox </span><span class="label label-warning pull-right">16/24</span></a>
                    <ul class="nav nav-second-level collapse">
                        <li><a href="mailbox.html">Inbox</a></li>
                        <li><a href="mail_detail.html">Email view</a></li>
                        <li><a href="mail_compose.html">Compose email</a></li>
                        <li><a href="email_template.html">Email templates</a></li>
                    </ul>
                </li>
            </ul>

        </div>
    </nav>

        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
        <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
            <form role="search" class="navbar-form-custom" action="search_results.html">
                <div class="form-group">
                    <input type="text" placeholder="Buscar..." class="form-control" name="top-search" id="top-search">
                </div>
            </form>
        </div>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span class="m-r-sm text-muted welcome-message">Sistema de gesti&oacute;n de activos Mankecura.</span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope"></i>  <span class="label label-warning">16</span>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a7.jpg">
                                </a>
                                <div>
                                    <small class="pull-right">46h ago</small>
                                    <strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/a4.jpg">
                                </a>
                                <div>
                                    <small class="pull-right text-navy">5h ago</small>
                                    <strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>. <br>
                                    <small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="dropdown-messages-box">
                                <a href="profile.html" class="pull-left">
                                    <img alt="image" class="img-circle" src="img/profile.jpg">
                                </a>
                                <div>
                                    <small class="pull-right">23h ago</small>
                                    <strong>Monica Smith</strong> love <strong>Kim Smith</strong>. <br>
                                    <small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
                                </div>
                            </div>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="mailbox.html">
                                    <i class="fa fa-envelope"></i> <strong>Read All Messages</strong>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="mailbox.html">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="profile.html">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="grid_options.html">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <div class="text-center link-block">
                                <a href="notifications.html">
                                    <strong>See All Alerts</strong>
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>


                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> Log out
                    </a>
                </li>
            </ul>

        </nav>
        </div>
<?php
   if (!(isset($_SESSION["login"]))) {
   }
}

function fecha() {
?>
  <tr bgcolor="#FF6600">
    <td colspan="3" bgcolor="#333333" class="titulo">
<?php
#setlocale(LC_ALL, 'spanish');
$dia=date('A');
$mes=date('B');
$anio=date('Y');
echo "$dia ".date(d)." de $mes de ".date(Y);
?>
     </td>
   </tr>
<?php
}

function abajo() {
?>
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
	    <strong>Copyright</strong> <a href="http://www.loopbackpro.com" target="_blank" class="btn-link">Loopback SpA</a> &copy; 2015-<?php echo date('Y'); ?></div>
        </div>
<?php
}

function tabla_arriba($tit,$ancho) {
?>
        <table width="<?php $ancho; ?>" align="center"/><tr><td>
        <fieldset class="contenido">
                <legend class="contenido"><b><?php $tit; ?></b></legend>
<?php
}

function tabla_abajo() {
?>
      </fieldset></td></tr></table>
<?php
}

function is_logeado() {
   if (!(isset($_SESSION["login"]))) {
      header("location:index.php");
#      exit;
   }
}

function db() {
   $connect=mysqli_connect("localhost","mankecur_admin","I94ySixcS6UF","mankecur_web");
   if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
      exit();
   }
   $connect->query("SET NAMES 'utf8'");
   return $connect;
}

function db2() {
   $connect2=mysqli_connect("localhost","mankecur_admin","I94ySixcS6UF","mankecur_web");
   if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
   }
   #$connect2->query("SET NAMES 'utf8'");
   return $connect2;
}

function traduce_mes($t_mes) {
   $t_mes = str_replace("JANUARY", "ENERO", $t_mes);
   $t_mes = str_replace("FEBRUARY", "FEBRERO", $t_mes);
   $t_mes = str_replace("MARCH", "MARZO", $t_mes);
   $t_mes = str_replace("APRIL", "ABRIL", $t_mes);
   $t_mes = str_replace("MAY", "MAYO", $t_mes);
   $t_mes = str_replace("JUNE", "JUNIO", $t_mes);
   $t_mes = str_replace("JULY", "JULIO", $t_mes);
   $t_mes = str_replace("AUGUST", "AGOSTO", $t_mes);
   $t_mes = str_replace("SEPTEMBER", "SEPTIEMBRE", $t_mes);
   $t_mes = str_replace("OCTOBER", "OCTUBRE", $t_mes);
   $t_mes = str_replace("NOVEMBER", "NOVIEMBRE", $t_mes);
   $t_mes = str_replace("DECEMBER", "DICIEMBRE", $t_mes);
   return($t_mes);
}

function arreglo_asis($s_v,$a_v,$j) {
   for ($k=0; $k < ($j-1); $k++) {
      $s_v = $s_v.array_shift($a_v).", ";
   }
   $s_v = $s_v.array_shift($a_v);
   return($s_v);
}

function combo_localidades($l) {
   $connect = mssql();
   $query_l = "SELECT LOC_CODIGO, LOC_NOMBRE FROM IMP_LOCALIDAD ORDER BY LOC_NOMBRE";
   #print "$query_l\n";
   $result_l = mysqli_query($connect, $query_l);
   while($row_a = mysqli_fetch_array($result_l)) {
      $id_l = $row_a[0];
      $nom_l = $row_a[1];
      if ($l == $id_l) {
         $s = " selected ";
      } else {
         $s = " ";
      }
      $opciones .= "               <option value=\"".$id_l."\"".$s."class=\"contenido\">".strtoupper($nom_l)."</option>\n";
   }
   return $opciones;
}

function combo_profesiones($l) {
   $connect = mssql();
   $query_l = "SELECT PRF_CODIGO, PRF_NOMBRE FROM IMP_PROFESION ORDER BY PRF_NOMBRE";
   #print "$query_l\n";
   $result_l = mysqli_query($connect, $query_l);
   while($row_a = mysqli_fetch_array($result_l)) {
      $id_l = $row_a[0];
      $nom_l = $row_a[1];
      if ($l == $id_l) {
         $s = " selected ";
      } else {
         $s = " ";
      }
      $opciones .= "               <option value=\"".$id_l."\"".$s."class=\"contenido\">".strtoupper($nom_l)."</option>\n";
   }
   return $opciones;
}

function combo_estados($l) {
   $connect = mssql();
   $query_l = "SELECT EST_CODIGO,EST_NOMBRE from IMP_ESTADO WHERE EST_TIPO='MIE' ORDER BY EST_NOMBRE";
   #print "$query_l\n";
   $result_l = mysqli_query($connect, $query_l);
   while($row_a = mysqli_fetch_array($result_l)) {
      $id_l = $row_a[0];
      $nom_l = $row_a[1];
      if ($l == $id_l) {
         $s = " selected ";
      } else {
         $s = " ";
      }
      $opciones .= "               <option value=\"".$id_l."\"".$s."class=\"contenido\">".strtoupper($nom_l)."</option>\n";
   }
   return $opciones;
}

function combo_grados($l) {
   $connect = mssql();
   $query_l = "SELECT GRD_CODIGO,GRD_NOMBRE from IMP_GRADO ORDER BY GRD_NOMBRE";
   #print "$query_l\n";
   $result_l = mysqli_query($connect, $query_l);
   while($row_a = mysqli_fetch_array($result_l)) {
      $id_l = $row_a[0];
      $nom_l = $row_a[1];
      if ($l == $id_l) {
         $s = " selected ";
      } else {
         $s = " ";
      }
      $opciones .= "               <option value=\"".$id_l."\"".$s."class=\"contenido\">".strtoupper($nom_l)."</option>\n";
   }
   return $opciones;
}

function getUserIpAddr(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
