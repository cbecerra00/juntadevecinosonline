<?php
namespace PHPCoord;
require __DIR__ . '/vendor/autoload.php';

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
   $latitude = $pun->getLatitude()->getValue();
   $longitude = $pun->getLongitude()->getValue();
   return [$longitude,$latitude];
}

include_once "lib/lib.inc";
ini_set("session.cookie_domain", ".mankecura.com");
session_start();
is_logeado();
top();
$connect = db();
$anio=date('Y');
?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2>Detalles del pol&iacute;gono</h2>
                    <ol class="breadcrumb">
<?php
$c = $_GET["c"];
$p = $_GET["p"];
$query_c = "SELECT T.Titu_Nombre,P.Proy_Nombre FROM Titular T, Proyecto P WHERE T.Titu_ID=P.Titular_Titu_ID AND T.Titu_ID=$c AND P.Proy_ID=$p;";
#print "$query_c\n";
$result_c = mysqli_query($connect, $query_c);
while($row_c = mysqli_fetch_array($result_c)) {
   $com = $row_c[0];
   $prj = $row_c[1];
}
?>
                        <li>
                            <a href="dashboard.php?c=<?php echo $c; ?>"><?php echo $com; ?></a>
                        </li>
                        <li>
                            <a href="proj.php?p=<?php echo $p; ?>&c=<?php echo $c; ?>"><?php echo $prj; ?></a>
                        </li>
                        <li class="active">
                            <strong>Detalles del Pol&iacute;gono</strong>
                        </li>
                    </ol>
                </div>
            </div>
<?php
$idp = $_GET["id_p"];
$query_ficha = "SELECT P.Pert_ID, P.Pert_Nombre, P.Pert_NumExp, J.Juzg_Nombre, P.Pert_Lugar, P.Pert_Zona, P.Pert_Datum, P.Pert_HojaIGM, P.Pert_Norte_PIPM, P.Pert_Este_PIPM, P.Pert_N_S, P.Pert_E_W, P.Pert_SupTotal, P.Pert_Nro_Lados, P.Pert_Rol_Minero, P.Pert_Memo, P.Pert_Franco, P.Provincia_Prov_ID, P.Ciudad_Ciud_ID,T.Titu_Nombre, R.Regi_Nombre, Pro.Prov_Nombre FROM Pertenencia P, Juzgado J, Titular T, Proyecto Pr, Provincia Pro, Region R, Ciudad C WHERE P.Ciudad_Ciud_ID=C.Ciud_ID and C.Provincia_Prov_ID=Pro.Prov_ID and Pro.Region_Regi_ID=R.Regi_ID and Pr.Proy_ID=P.Proyecto_Proy_ID and Pr.Titular_Titu_ID=T.Titu_ID and P.Juzgado_Juzg_ID=J.Juzg_ID and P.Pert_ID=$idp;";
#print "$query_ficha\n";
$result_ficha = mysqli_query($connect, $query_ficha);
$cont=0;
while($row_f = mysqli_fetch_array($result_ficha)) {
   $pm=punto($row_f[9],$row_f[8],19);
?>
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInUp">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        <button type="button" class="btn btn-white btn-xs pull-right" data-toggle="modal" data-target="#edit-pertenencia">Editar pol&iacute;gono</button>
				<h2><strong><?php echo $row_f[1]; ?></strong></h2>
                                    </div>
                                    <dl class="dl-horizontal">
                                        <dt>Estado:</dt> <dd><span class="label label-primary">Activo</span></dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-7">
                                    <dl class="dl-horizontal">

                                        <dt>Titular:</dt> <dd><?php echo $row_f[19]; ?></dd>
                                        <dt>Regi&oacute;n:</dt> <dd><?php echo $row_f[20]; ?> </dd>
                                        <dt>Provincia:</dt> <dd><?php echo $row_f[21]; ?></dd>
                                        <dt>Juzgado:</dt> <dd><?php echo $row_f[3]; ?></dd>
                                        <!-- <dt>Nro Expediente:</dt> <dd><a href="#" class="text-navy"><?php echo $row_f[2]; ?></a> </dd> -->
                                        <dt>Nro Expediente:</dt> <dd><?php echo $row_f[2]; ?> </dd>
                                        <dt>Superficie Total:</dt> <dd> 	<?php echo $row_f[12]; ?> Has.</dd>
                                    </dl>
                                </div>
                                <div class="col-lg-5" id="cluster_info">
                                    <dl class="dl-horizontal" >

                                        <dt>POI Norte:</dt> <dd><?php echo $row_f[8]; ?></dd>
                                        <dt>POI Este:</dt> <dd><?php echo $row_f[9]; ?></dd>
                                        <dt>Norte/Sur:</dt> <dd><?php echo $row_f[10]; ?></dd>
                                        <dt>Este/Oeste:</dt> <dd><?php echo $row_f[11]; ?></dd>
                                        <dt>Nro Lados:</dt> <dd><?php echo $row_f[13]; ?></dd>
                                        <dt>Participantes:</dt>
                                        <dd class="project-people">
                                        <a href=""><img alt="image" class="img-circle" src="img/a3.jpg"></a>
                                        <a href=""><img alt="image" class="img-circle" src="img/a1.jpg"></a>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <dl class="dl-horizontal">
                                        <dt>Estado de avance:</dt>
                                        <dd>
                                            <div class="progress progress-striped active m-b-sm">
                                                <div style="width: 60%;" class="progress-bar"></div>
                                            </div>
                                            <small>El estado de avance de la tramitaci&oacute;n es <strong>60%</strong>. Los plazos est&aacute;n siendo monitoreados.</small>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
<?php
}
?>
                            <div class="row">
                                <div class="col-lg-12">
                                    <dl class="dl-horizontal">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>Estado</th>
                                            <th>Tr&aacute;mite</th>
                                            <th>Presentaci&oacute;n</th>
                                            <th>Comentario</th>
                                        </tr>
                                        </thead>
                                        <tbody>
<?php
$query_pt = "SELECT DISTINCT T.Tram_Nombre,TP.TramPert_Fecha as Fecha, TP.TramPert_Memo FROM TramPert TP, Tramite T WHERE T.Tram_Codigo=TP.Tramite_Tram_Codigo AND TP.Pertenencia_Pert_ID=$_GET[id_p] UNION SELECT DISTINCT 'PUBLICACIÓN BOM',TP.TramPert_BoleFec as Fecha, CONCAT('BOLETÍN NRO. ',TP.TramPert_BoleNro) FROM TramPert TP, Tramite T WHERE T.Tram_Codigo=TP.Tramite_Tram_Codigo AND TP.Pertenencia_Pert_ID=$_GET[id_p] ORDER BY Fecha ASC;";
#print "$query_pt\n";
$result_pt = mysqli_query($connect, $query_pt);
if (mysqli_num_rows($result_pt) == 0) {
?>
                                        <tr>
                                            <td colspan="4"><button type="button" class="btn btn-outline btn-danger">Sin registros</button></td>
                                        </tr>
<?php
}
while($row_pt = mysqli_fetch_array($result_pt)) {
?>
                                        <tr>
                                            <td><span class="label label-primary"><i class="fa fa-check"></i> Completado</span></td>
                                            <td><?php echo rtrim($row_pt[0]); ?></td>
                                            <td><?php echo $row_pt[1]; ?></td>
                                            <td><?php echo $row_pt[2]; ?></td>
                                        </tr>
<?php
}
?>
                                        </tbody>
                                    </table>

                                   </dl>
                                </div>
                             </div>
                            <div class="row">

<div id="map2" class="col-md-8"></div>
<div id="rose"></div>
<?php
$json_url="https://www.mankecura.com/geojson.php?t=".$idp;
$ptomedio=$pm[0].", ".$pm[1];
?>
<!--
<link rel="stylesheet" href="css/L.Control.Rose.css" />
<script src="js/L.Control.Rose.js"></script>
-->
<script>
mapboxgl.accessToken = 'pk.eyJ1IjoiY2JlY2VycmEiLCJhIjoiY2lmbzBjbnc5Z3QwMHJzbTc4NnM4Y2trNSJ9.TmLdONZPGGNYOMlY2V5oZg';
var map = new mapboxgl.Map({
    container: 'map2',
    style: 'mapbox://styles/mapbox/satellite-streets-v9',
    center: [<?php echo $ptomedio; ?>],
    zoom: 11,
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

var scale = new mapboxgl.ScaleControl({
    maxWidth: 80,
    unit: 'metric'
});
map.addControl(scale);
scale.setUnit('metric');

map.addControl(new mapboxgl.NavigationControl());

//var rose = L.control.rose('rose', {position:'bottomleft', icon:'default', iSize:'medium', opacity:0.8});
//rose.addTo(map)

</script>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!--
            <div class="col-lg-3">
                <div class="wrapper wrapper-content project-manager">
                    <h4>Project description</h4>
                    <img src="img/zender_logo.png" class="img-responsive">
                    <p class="small">
                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look
                        even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing
                    </p>
                    <p class="small font-bold">
                        <span><i class="fa fa-circle text-warning"></i> High priority</span>
                    </p>
                    <h5>Project tag</h5>
                    <ul class="tag-list" style="padding: 0">
                        <li><a href=""><i class="fa fa-tag"></i> Zender</a></li>
                        <li><a href=""><i class="fa fa-tag"></i> Lorem ipsum</a></li>
                        <li><a href=""><i class="fa fa-tag"></i> Passages</a></li>
                        <li><a href=""><i class="fa fa-tag"></i> Variations</a></li>
                    </ul>
                    <h5>Project files</h5>
                    <ul class="list-unstyled project-files">
                        <li><a href=""><i class="fa fa-file"></i> Project_document.docx</a></li>
                        <li><a href=""><i class="fa fa-file-picture-o"></i> Logo_zender_company.jpg</a></li>
                        <li><a href=""><i class="fa fa-stack-exchange"></i> Email_from_Alex.mln</a></li>
                        <li><a href=""><i class="fa fa-file"></i> Contract_20_11_2014.docx</a></li>
                    </ul>
                    <div class="text-center m-t-md">
                        <a href="#" class="btn btn-xs btn-primary">Add files</a>
                        <a href="#" class="btn btn-xs btn-primary">Report contact</a>

                    </div>
                </div>
            </div>
        </div>
-->
<?php
abajo();
?>

        </div>
        </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js"></script>
    <script src="js/plugins/pace/pace.min.js"></script>

<!--
    <script>
        $(document).ready(function(){

            $('#loading-example-btn').click(function () {
                btn = $(this);
                simpleLoad(btn, true)

                // Ajax example
//                $.ajax().always(function () {
//                    simpleLoad($(this), false)
//                });

                simpleLoad(btn, false)
            });
        });

        function simpleLoad(btn, state) {
            if (state) {
                btn.children().addClass('fa-spin');
                btn.contents().last().replaceWith(" Loading");
            } else {
                setTimeout(function () {
                    btn.children().removeClass('fa-spin');
                    btn.contents().last().replaceWith(" Refresh");
                }, 2000);
            }
        }
    </script>
-->
<?php
$query_ficha = "SELECT P.Pert_ID, P.Pert_Nombre, P.Pert_NumExp, J.Juzg_Nombre, P.Pert_Lugar, P.Pert_Zona, P.Pert_Datum, P.Pert_HojaIGM, P.Pert_Norte_PIPM, P.Pert_Este_PIPM, P.Pert_N_S, P.Pert_E_W, P.Pert_SupTotal, P.Pert_Nro_Lados, P.Pert_Rol_Minero, P.Pert_Memo, P.Pert_Franco, P.Provincia_Prov_ID, P.Ciudad_Ciud_ID, T.Titu_Nombre, R.Regi_Nombre, R.Regi_CUT, Pro.Prov_Nombre, J.Juzg_ID, R.Regi_ID FROM Pertenencia P, Juzgado J, Titular T, Proyecto Pr, Provincia Pro, Region R, Ciudad C WHERE P.Ciudad_Ciud_ID=C.Ciud_ID and C.Provincia_Prov_ID=Pro.Prov_ID and Pro.Region_Regi_ID=R.Regi_CUT and Pr.Proy_ID=P.Proyecto_Proy_ID and Pr.Titular_Titu_ID=T.Titu_ID and P.Juzgado_Juzg_ID=J.Juzg_ID and P.Pert_ID=$_GET[id_p];";
#print "$query_ficha\n";
$result_ficha = mysqli_query($connect, $query_ficha);
$cont=0;
while($row_f = mysqli_fetch_array($result_ficha)) {
?>
<div id="edit-pertenencia" class="modal fade" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-body">
            <div class="row">
               <div class="col-sm-12 b-r"><h3 class="m-t-none m-b">Edita pol&iacute;gono "<?php echo $row_f[1]; ?>"</h3>
                  <form role="form">
                     <div class="form-group"><label  class="col-sm-4 control-label">Regi&oacute;n: </label>
                        <div class="col-sm-8">
                           <select id="reg" class="reg" name="reg">
                              <option id="" selected="selected">-- Selecciona regi&oacute;n --</option>
<?php
$query_region = "SELECT R.Regi_Nombre, R.Regi_ID FROM Region R ORDER BY R.Regi_CUT ASC;";
#print "$query_region\n"
$result_region = mysqli_query($connect, $query_region);
while($row_r = mysqli_fetch_array($result_region)) {
   $row_r[1] == $row_f[21] ? $sel=" selected=\"selected\"" : $sel="";
?>
                              <option value="<?php echo $row_r[1]; ?>"<?php echo $sel; ?>><?php echo $row_r[0]; ?></option>
<?php
}
?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group"><label  class="col-sm-4 control-label">Provincia: </label>
                        <div class="col-sm-8">
                           <select id="prov" class="prov" name="prov">
<?php
$query_prov = "SELECT P.Prov_Nombre, P.Prov_ID FROM Provincia P WHERE P.Region_Regi_ID=$row_f[24] ORDER BY P.Prov_Nombre ASC;";
#print "$query_prov\n";
$result_prov = mysqli_query($connect, $query_prov);
while($row_p = mysqli_fetch_array($result_prov)) {
   $row_p[1] == $row_f[24] ? $sel=" selected=\"selected\"" : $sel="";
?>
                              <option value="<?php echo $row_p[1]; ?>"<?php echo $sel; ?>><?php echo $row_p[0]; ?></option>
<?php
}
?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group"><label class="col-sm-4 control-label">Juzgado: </label>
                        <div class="col-sm-8">
                           <select id="juz" class="juz" name="juz">
<?php
$query_juz = "SELECT J.Juzg_Nombre, J.Juzg_ID FROM Juzgado J, Ciudad C, Provincia P, Region R WHERE J.Ciudad_Ciud_ID=C.Ciud_ID and C.Provincia_Prov_ID=P.Prov_ID and P.Region_Regi_ID=R.Regi_ID and P.Prov_ID=$row_f[17];";
#print "$query_juz\n";
$result_juz = mysqli_query($connect, $query_juz);
while($row_j = mysqli_fetch_array($result_juz)) {
   $row_j[1] == $row_f[23] ? $sel=" selected=\"selected\"" : $sel="";
?>
                              <option value="<?php echo $row_j[1]; ?>"<?php echo $sel; ?>><?php echo $row_j[0]; ?></option>
<?php
}
?>
                           </select>
                        </div>
                     </div>
                     <div class="form-group"><label class="col-sm-4 control-label">Nro Expediente: </label><div class="col-sm-8"><input type="text" class="form-control" value="<?php echo $row_f[2]; ?>" id="nexp"></div>
                     </div>
                     <div class="form-group"><label class="col-sm-4 control-label">Sup. Total: </label><div class="col-sm-8"><input type="text" class="form-control" value="<?php echo $row_f[12]; ?>" id="st"></div>
                    </div>
                     <div class="form-group"><label class="col-sm-4 control-label">POI Norte: </label><div class="col-sm-8"><input type="text" class="form-control" value="<?php echo $row_f[8]; ?>" id="poin"></div>
                    </div>
                     <div class="form-group"><label class="col-sm-4 control-label">POI Este: </label><div class="col-sm-8"><input type="text" class="form-control" value="<?php echo $row_f[9]; ?>" id="poie"></div>
                    </div>
                     <div class="form-group"><label class="col-sm-4 control-label">Norte/Sur: </label><div class="col-sm-8"><input type="text" class="form-control" value="<?php echo $row_f[10]; ?>" id="ns"></div>
                    </div>
                     <div class="form-group"><label class="col-sm-4 control-label">Este/Oeste: </label><div class="col-sm-8"><input type="text" class="form-control" value="<?php echo $row_f[11]; ?>" id="eo"></div>
                    </div>
                     <div class="form-group"><label class="col-sm-4 control-label">Nro Lados: </label><div class="col-sm-8"><input type="text" class="form-control" value="<?php echo $row_f[13]; ?>" id="nl"></div>
                    </div>
                </div>
             </div>
          </div>
          <div class="modal-footer">
             <button class="btn btn-sm btn-primary m-t-n-xs" type="submit"><strong>Enviar</strong></button>
             <button class="btn btn-sm btn-secondary m-t-n-xs" data-dismiss="modal"><strong>Cerrar</strong></button>
          </div>
          </form>
      </div>
   </div>
</div>
<?php
}
?>

<script type="text/javascript">
$(document).ready(function()
{
$(".reg").change(function()
 {
  var id=$(this).val();
  var dataString = 'id='+ id;
  $(".prov").empty();
 
  $.ajax
  ({
   type: "POST",
   url: "get_provincia.php",
   data: dataString,
   cache: false,
   success: function(html)
   {
    $(".prov").html(html);
   } 
   });
  });

 $(".prov").change(function()
 {
  var id=$(this).val();
  var dataString = 'id='+ id;
 
  $.ajax
  ({
   type: "POST",
   url: "get_juzgado.php",
   data: dataString,
   cache: false,
   success: function(html)
   {
      $(".juz").html(html);
   } 
   });
  });

});
</script>

</body>

</html>
