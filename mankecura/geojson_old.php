<?
include_once "lib/lib.inc";
$connect = db();
include_once "lib/phpcoord-2.3.php";
function UTMtoLL($ec, $nc, $zlc, $znc) {
   $utm = new UTMRef($ec, $nc, $zlc, $znc);
   $ll = $utm->toLatLng();
   return $ll;
}
$sqlclause="ID=$_GET[t]";
$query_gjson = "SELECT P.Pert_NumExp, J.Juzg_Nombre, P.Pert_Datum, P.Pert_Norte_PIPM, P.Pert_Este_PIPM, P.Pert_N_S, P.Pert_E_W, P.Pert_SupTotal, P.Pert_Nro_Lados, P.Pert_Nombre, T.Titu_Nombre, P.Pert_Zona FROM Pertenencia P, Juzgado J, Titular T, Proyecto Pr WHERE P.Juzgado_Juzg_ID=J.Juzg_ID and Pr.Proy_ID=P.Proyecto_Proy_ID and Pr.Titular_Titu_ID=T.Titu_ID and P.Pert_ID=$_GET[t];";
#print "$query_gjson\n";
$result_gjson = mysqli_query($connect, $query_gjson);
$geojson = array( 'type' => 'FeatureCollection', 'features' => array());
while($row = mysqli_fetch_array($result_gjson)) {
   $norte=$row[3]-($row[5]/2);
   $sur=$row[3]+($row[5]/2);
   $este=$row[4]+($row[6]/2);
   $weste=$row[4]-($row[6]/2);
   $pm=UTMtoLL($row[4],$row[3],'J',$row[11]);
   $v1=UTMtoLL($weste,$norte,'J',$row[11]);
   $v2=UTMtoLL($este,$norte,'J',$row[11]);
   $v3=UTMtoLL($este,$sur,'J',$row[11]);
   $v4=UTMtoLL($weste,$sur,'J',$row[11]);
   if ($row[11] == 50) {
      $color = '#8c7b70';
      $tt = "SENT. EXPLORACION";
   } elseif ($row[11] == 10) {
      $color = '#f07f09';
      $tt = "MANIFESTACION";
   } elseif ($row[11] == 40) {
      $color = '#9f2936';
      $tt = "PEDIMENTO";
   } elseif ($row[11] == 20) {
      $color = '#1b587c';
      $tt = "SOLIC. MENSURA";
   } elseif ($row[11] == 30) {
      $color = '#4c8540';
      $tt = "SENT. EXPLOTACION";
   } elseif ($row[11] == 60) {
      $color = '#ccb400';
      $tt = "PRORROGA";
   } else {
      $color = '#604878';
      $tt = "OTRO";
   }
   #$InfoWindowContent="<div class=\"widget style1 yellow-bg\"><i class=\"fa fa-map-marker\"></i> ".$row[9]."<small><ul class=\"list-unstyled m-t-md\"><li><label>Punto Inter&eacute;s Norte: </label> ".$row[3]."</li><li><label>Punto Inter&eacute;s Este: </label> ".$row[4]."</li><li><label>Superficie Total: </label> ".$row[7]." H&aacute;s.</li><li><label>Titular: </label> ".$row[10]."</li><li><label>Tipo: </label> ".$tt."</li></small></div>";
   $InfoWindowContent="<div class=\"widget style1 yellow-bg\"><i class=\"fa fa-map-marker\"></i> ".$row[9]."</div>";

   $marker = array(
                'type' => 'Feature',
                'features' => array(
                    'type' => 'Feature',
                    'properties' => array(
                        'title' => "<div class=\"widget yellow-bg p-lg\"><i class=\"fa fa-map-marker\"></i> ".$row[9]."</div>",
                        'fill' => "".$color."",
                        'fill-opacity' => 0.5,
                        'stroke' => "".$color."",
                        'stroke-opacity' => 1,
                        'stroke-width' => 1,
                        'description' => "".$InfoWindowContent."",
                        ),
                    "geometry" => array(
                        'type' => 'Polygon',
                        'coordinates' => array([[$v1[1], $v1[0]],
                                        [$v2[1], $v2[0]],
                                        [$v3[1], $v3[0]],
                                        [$v4[1], $v4[0]],
                                        [$v1[1], $v1[0]]]
                        )
                    )
                )
    );
   array_push($geojson['features'], $marker['features']);
   $tt="";
}
//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST');
//header('Access-Control-Allow-Headers: X-Requested-With');
header('Content-type: application/json');
echo json_encode($geojson, JSON_NUMERIC_CHECK);
?>
