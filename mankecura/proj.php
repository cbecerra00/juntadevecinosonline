<?php
namespace PHPCoord;
require __DIR__ . '/vendor/autoload.php';

include_once "lib/lib.inc";
ini_set("session.cookie_domain", ".mankecura.com");
session_start();
is_logeado();
top();
$connect = db();
$anio=ucfirst(strftime("%Y"));
?>
            <div class="wrapper wrapper-content animated fadeIn">

<?php
$p = $_GET["p"];
$c = $_GET["c"];
$query="SELECT T.Titu_Nombre,Pr.Proy_Nombre FROM Titular T, Proyecto Pr WHERE T.Titu_ID=Pr.Titular_Titu_ID AND Pr.Proy_ID=$p AND T.Titu_ID=$c;";
#print "$query\n";
$result = mysqli_query($connect, $query);
while($row = mysqli_fetch_array($result)) {
   $cn = $row[0];
   $pn = $row[1];
}
$query_np="SELECT count(distinct P.Pert_ID),SUM(P.Pert_SupTotal) FROM User_Titular U, Titular T, Proyecto Pr, Pertenencia P where Pr.Titular_Titu_ID=T.Titu_ID and P.Proyecto_Proy_ID=Pr.Proy_ID and U.User_User_ID=".$_SESSION["uid"]." and T.Titu_ID=U.Titular_Titu_ID and T.Titu_ID=$c AND Pr.Proy_ID=$p GROUP BY P.Proyecto_Proy_ID;";
#print "$query_np\n";
$result_np = mysqli_query($connect, $query_np);
while($row_np = mysqli_fetch_array($result_np)) {
   $np = $row_np[0];
   $nh = $row_np[1];
}
?>

            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2>Detalles del proyecto</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="dashboard.php?c=<?php echo $c; ?>"><?php echo $cn; ?></a>
                        </li>
                        <li class="active">
                            <strong><?php echo $pn; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>


        <div class="row">

            <div class="col-lg-3">
                <div class="widget style1 lazur-bg">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <i class="fa fa-crosshairs fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <h3> Total Propiedades </h3>
                                <h1 class="font-bold"><?php echo number_format($np,0, ',', '.'); ?></h1>
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="widget style1 navy-bg">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <i class="fa fa-compass fa-5x"></i>
                            </div>
                            <div class="col-xs-8 text-right">
                                <h3> Total Hect&aacute;reas </h3>
                                <h1 class="font-bold"><?php echo number_format($nh,0, ',', '.'); ?></h1>
                            </div>
                        </div>
                </div>
            </div>
<?php
$query_det="SELECT TP.Pertenencia_Pert_ID, TP.TramPert_ID, MAX(TP.TramPert_Fecha), TP.Juzgado_Juzg_ID, TP.Tramite_Tram_Codigo, T.Tram_Nombre, TP.SubTramite_SubTram_ID FROM Tramite T, TramPert TP WHERE T.Tram_Codigo=TP.Tramite_Tram_Codigo AND TP.Pertenencia_Pert_ID IN (SELECT P.Pert_ID FROM Pertenencia P WHERE P.Proyecto_Proy_ID=$p) GROUP BY TP.Pertenencia_Pert_ID;";
#print "$query_det\n";
$nman=0;
$nsmn=0;
$nsxr=0;
$nped=0;
$nsxl=0;
$npro=0;
$result_det = mysqli_query($connect, $query_det);
while($row_det = mysqli_fetch_array($result_det)) {
   if ($row_det[4] == 10) { $nman++; }
   if ($row_det[4] == 20) { $nsmn++; }
   if ($row_det[4] == 30) { $nsxr++; }
   if ($row_det[4] == 40) { $nped++; }
   if ($row_det[4] == 50) { $nsxl++; }
   if ($row_det[4] == 60) { $npro++; }
}
$ttram=$nman+$nsmn+$nsxr+$nped+$nsxl+$npro;
$csen=$nsxr+$nsxl;
?>


                       <div class="col-sm-6">

                            <div class="row m-t-xs">
                                <div class="col-xs-6">
                                    <h5 class="m-b-xs">Propiedades con sentencia</h5>
                                    <h1 class="no-margins"><?php echo $csen; ?></h1>
                                    <div class="font-bold text-navy"><?php echo ($ttram > 0) ? number_format(($csen / $ttram) * 100, 2, ',', '.') : '0,00'; ?>% <i class="fa fa-bolt"></i></div>
                                </div>
                                <div class="col-xs-6">
                                    <h5 class="m-b-xs">Sales current year (legacy)</h5>
                                    <h1 class="no-margins">42,120</h1>
                                    <div class="font-bold text-navy">98% <i class="fa fa-bolt"></i></div>
                                </div>
                            </div>


                            <table class="table small m-t-sm">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong><?php echo $nman; ?></strong> Manifestaciones
                                    </td>
                                    <td>
                                        <strong>61</strong> Comments
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo $nped; ?></strong> Pedimentos
                                    </td>
                                    <td>
                                        <strong>54</strong> Articles
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>



                    <div class="row">
                        <!--
                        <div class="col-lg-12">
                            <div class="table-responsive">
                               <div>&nbsp;</div>
                               <div id="map" class="col-md-8">
                               <div>&nbsp;</div>
                               </div>
                            </div>
                        </div>
                        -->
                        <div id="map"></div>
                    </div>

<?php

use PHPCoord\CoordinateReferenceSystem\Geographic2D;
use PHPCoord\CoordinateReferenceSystem\Projected;
use PHPCoord\Point\ProjectedPoint;
use PHPCoord\UnitOfMeasure\Length\Metre;

function punto($est, $nor, $nu) {
   $crs = Geographic2D::fromSRID(Geographic2D::EPSG_PSAD56);
   $point1 = ProjectedPoint::createFromEastingNorthing(
      Projected::fromSRID(Projected::EPSG_PSAD56_UTM_ZONE_19S),
      new Metre($est),
      new Metre($nor)
   );
   $pun = $point1->asGeographicPoint();
   #$pun = $point1->convert($crs);
   $latitude = $pun->getLatitude()->getValue();
   $longitude = $pun->getLongitude()->getValue();
   return [$latitude,$longitude];
}

$cprop=0;
$p_norte=0;
$p_este=0;
$query_pm = "SELECT AVG(P.Pert_Norte_PIPM),AVG(P.Pert_Este_PIPM) FROM Pertenencia P WHERE P.Proyecto_Proy_ID=$p GROUP BY P.Proyecto_Proy_ID;";
#print "$query_pm\n";
$result_pm = mysqli_query($connect, $query_pm);
while($row_pm = mysqli_fetch_array($result_pm)) {
   $pm_aux1=punto($row_pm[1],$row_pm[0],19);
   #print "Punto ".$pm_aux1[0];
}
$json_url="https://www.mankecura.com/geojson_proj.php?t=".$p;
?>

<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiY2JlY2VycmEiLCJhIjoiY2lmbzBjbnc5Z3QwMHJzbTc4NnM4Y2trNSJ9.TmLdONZPGGNYOMlY2V5oZg';
var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/satellite-streets-v9',
    center: [<?php print $pm_aux1[1].", ".$pm_aux1[0]; ?>],
    zoom: 9, 
    attributionControl: false
});

map.on('load', function () {
    map.addSource('poligono', {
        'type': 'geojson',
        'data': '<?php echo $json_url; ?>'
    });

    map.addLayer({
        'id': 'poligono-fill',
        'type': 'fill',
        'source': 'poligono',
        'layout': {},
        'paint': {
            'fill-color': '#9f2936',
            'fill-opacity': 0.5
        }
    });
});

// When a click event occurs near a polygon, open a popup at the location of
// the feature, with description HTML from its properties.
map.on('click', function (e) {
    var features = map.queryRenderedFeatures(e.point, { layers: ['poligono-fill'] });
    if (!features.length) {
        return;
    }
    var feature = features[0];
    var popup = new mapboxgl.Popup()
        //.setLngLat(feature.geometry.coordinates)
        .setLngLat(map.unproject(e.point))
        .setHTML(feature.properties.description)
        .addTo(map);
});

// Use the same approach as above to indicate that the symbols are clickable
// by changing the cursor style to 'pointer'.
map.on('mousemove', function (e) {
    var features = map.queryRenderedFeatures(e.point, { layers: ['poligono-fill'] });
    map.getCanvas().style.cursor = (features.length) ? 'pointer' : '';
});
</script>


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="ibox">



                                <div class="ibox-content">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover dataTables-example">
                   <thead>
                    <tr>
                        <th>Propiedad</th>
                        <th>Juzgado</th>
                        <th>Nro. Expediente</th>
                        <th>Detalles</th>
                    </tr>
                    </thead>

                                            <tbody>
<?php
$query_ficha = "SELECT P.Pert_ID, P.Pert_Nombre, P.Pert_NumExp, J.Juzg_Nombre, P.Pert_Lugar, P.Pert_Zona, P.Pert_Datum, P.Pert_HojaIGM, P.Pert_Norte_PIPM, P.Pert_Este_PIPM, P.Pert_N_S, P.Pert_E_W, P.Pert_SupTotal, P.Pert_Nro_Lados, P.Pert_Rol_Minero, P.Pert_Memo, P.Pert_Franco, P.Provincia_Prov_ID, P.Ciudad_Ciud_ID FROM Pertenencia P, Juzgado J WHERE P.Juzgado_Juzg_ID=J.Juzg_ID and P.Proyecto_Proy_ID=$p;";
#print "$query_ficha\n";
$result_ficha = mysqli_query($connect, $query_ficha);
$cont=0;
while($row_f = mysqli_fetch_array($result_ficha)) {
   $cons = $row_f[1];
   $juzg = $row_f[3];
   $num_exp = $row_f[2];
   $n_pipm = $row_f[8];
   $e_pipm = $row_f[9];
   $ns = $row_f[10];
   $ew = $row_f[11];
   $supt = $row_f[12];
   $nlados = $row_f[13];
   $f_manped= $row_f[6];
   $f_sentexp= $row_f[8];
?>
                                            <tr>
                                                <td><?php echo $cons; ?></td>
                                                <td><?php echo $juzg; ?></td>
                                                <td><?php echo $num_exp; ?></td>
                                                <td><a href="pert.php?id_p=<?php echo $row_f[0] ;?>&c=<?php echo $c; ?>&p=<?php echo $p; ?>"><i class="fa fa-search text-navy"></i></a></td>
                                            </tr>
<?php
}
?>
                                            </tbody>
                    <tfoot>
                    <tr>
                        <th>Propiedad</th>
                        <th>Juzgado</th>
                        <th>Nro. Expediente</th>
                        <th>Detalles</th>
                    </tr>
                    </tfoot>

                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
<?php
abajo();
?>
        </div>
        <div id="right-sidebar">
            <div class="sidebar-container">

                <ul class="nav nav-tabs navs-3">

                    <li class="active"><a data-toggle="tab" href="#tab-1">
                        Notes
                    </a></li>
                    <li><a data-toggle="tab" href="#tab-2">
                        Projects
                    </a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3">
                        <i class="fa fa-gear"></i>
                    </a></li>
                </ul>

                <div class="tab-content">


                    <div id="tab-1" class="tab-pane active">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-comments-o"></i> Latest Notes</h3>
                            <small><i class="fa fa-tim"></i> You have 10 new message.</small>
                        </div>

                        <div>

                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a1.jpg">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">

                                        There are many variations of passages of Lorem Ipsum available.
                                        <br>
                                        <small class="text-muted">Today 4:21 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a2.jpg">
                                    </div>
                                    <div class="media-body">
                                        The point of using Lorem Ipsum is that it has a more-or-less normal.
                                        <br>
                                        <small class="text-muted">Yesterday 2:45 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a3.jpg">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        Mevolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                        <br>
                                        <small class="text-muted">Yesterday 1:10 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a4.jpg">
                                    </div>

                                    <div class="media-body">
                                        Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the
                                        <br>
                                        <small class="text-muted">Monday 8:37 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a8.jpg">
                                    </div>
                                    <div class="media-body">

                                        All the Lorem Ipsum generators on the Internet tend to repeat.
                                        <br>
                                        <small class="text-muted">Today 4:21 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a7.jpg">
                                    </div>
                                    <div class="media-body">
                                        Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
                                        <br>
                                        <small class="text-muted">Yesterday 2:45 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a3.jpg">

                                        <div class="m-t-xs">
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                            <i class="fa fa-star text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body">
                                        The standard chunk of Lorem Ipsum used since the 1500s is reproduced below.
                                        <br>
                                        <small class="text-muted">Yesterday 1:10 pm</small>
                                    </div>
                                </a>
                            </div>
                            <div class="sidebar-message">
                                <a href="#">
                                    <div class="pull-left text-center">
                                        <img alt="image" class="img-circle message-avatar" src="img/a4.jpg">
                                    </div>
                                    <div class="media-body">
                                        Uncover many web sites still in their infancy. Various versions have.
                                        <br>
                                        <small class="text-muted">Monday 8:37 pm</small>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                    <div id="tab-2" class="tab-pane">

                        <div class="sidebar-title">
                            <h3> <i class="fa fa-cube"></i> Latest projects</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>

                        <ul class="sidebar-list">
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Business valuation</h4>
                                    It is a long established fact that a reader will be distracted.

                                    <div class="small">Completion with: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Contract with Company </h4>
                                    Many desktop publishing packages and web page editors.

                                    <div class="small">Completion with: 48%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Meeting</h4>
                                    By the readable content of a page when looking at its layout.

                                    <div class="small">Completion with: 14%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label label-primary pull-right">NEW</span>
                                    <h4>The generated</h4>
                                    <!--<div class="small pull-right m-t-xs">9 hours ago</div>-->
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="small">Completion with: 22%</div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Business valuation</h4>
                                    It is a long established fact that a reader will be distracted.

                                    <div class="small">Completion with: 22%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
                                    </div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Contract with Company </h4>
                                    Many desktop publishing packages and web page editors.

                                    <div class="small">Completion with: 48%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 48%;" class="progress-bar"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <div class="small pull-right m-t-xs">9 hours ago</div>
                                    <h4>Meeting</h4>
                                    By the readable content of a page when looking at its layout.

                                    <div class="small">Completion with: 14%</div>
                                    <div class="progress progress-mini">
                                        <div style="width: 14%;" class="progress-bar progress-bar-info"></div>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="label label-primary pull-right">NEW</span>
                                    <h4>The generated</h4>
                                    <!--<div class="small pull-right m-t-xs">9 hours ago</div>-->
                                    There are many variations of passages of Lorem Ipsum available.
                                    <div class="small">Completion with: 22%</div>
                                    <div class="small text-muted m-t-xs">Project end: 4:00 pm - 12.06.2014</div>
                                </a>
                            </li>

                        </ul>

                    </div>

                    <div id="tab-3" class="tab-pane">

                        <div class="sidebar-title">
                            <h3><i class="fa fa-gears"></i> Settings</h3>
                            <small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
                        </div>
d
                        <div class="setings-item">
                    <span>
                        Show notifications
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
                                    <label class="onoffswitch-label" for="example">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Disable Chat
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
                                    <label class="onoffswitch-label" for="example2">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Enable history
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
                                    <label class="onoffswitch-label" for="example3">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Show charts
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
                                    <label class="onoffswitch-label" for="example4">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Offline users
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
                                    <label class="onoffswitch-label" for="example5">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Global search
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
                                    <label class="onoffswitch-label" for="example6">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="setings-item">
                    <span>
                        Update everyday
                    </span>
                            <div class="switch">
                                <div class="onoffswitch">
                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
                                    <label class="onoffswitch-label" for="example7">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-content">
                            <h4>Settings</h4>
                            <div class="small">
                                I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                And typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                                Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                            </div>
                        </div>

                    </div>
                </div>

            </div>



        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="js/plugins/dataTables/datatables.min.js"></script>

    <!-- Flot -->
<!--
    <script src="js/plugins/flot/jquery.flot.js"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="js/plugins/flot/jquery.flot.time.js"></script>
-->
    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>


    <script>
        $(document).ready(function() {

            var sparklineCharts = function(){
                $("#sparkline1").sparkline([34, 43, 43, 35, 44, 32, 44, 52], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1ab394',
                    fillColor: "transparent"
                });

                $("#sparkline2").sparkline([32, 11, 25, 37, 41, 32, 34, 42], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1ab394',
                    fillColor: "transparent"
                });

                $("#sparkline3").sparkline([34, 22, 24, 41, 10, 18, 16,8], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1C84C6',
                    fillColor: "transparent"
                });
            };

            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();




            var data1 = [
                [0,4],[1,8],[2,5],[3,10],[4,4],[5,16],[6,5],[7,11],[8,6],[9,11],[10,20],[11,10],[12,13],[13,4],[14,7],[15,8],[16,12]
            ];
            var data2 = [
                [0,0],[1,2],[2,7],[3,4],[4,11],[5,4],[6,2],[7,5],[8,11],[9,5],[10,4],[11,1],[12,5],[13,2],[14,5],[15,2],[16,0]
            ];
            $("#flot-dashboard5-chart").length && $.plot($("#flot-dashboard5-chart"), [
                        data1,  data2
                    ],
                    {
                        series: {
                            lines: {
                                show: false,
                                fill: true
                            },
                            splines: {
                                show: true,
                                tension: 0.4,
                                lineWidth: 1,
                                fill: 0.4
                            },
                            points: {
                                radius: 0,
                                show: true
                            },
                            shadowSize: 2
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,

                            borderWidth: 2,
                            color: 'transparent'
                        },
                        colors: ["#1ab394", "#1C84C6"],
                        xaxis:{
                        },
                        yaxis: {
                        },
                        tooltip: false
                    }
            );

        });

            $('.dataTables-example').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]
            });

    </script>
</body>
</html>
