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
$connect = db();

$t = $_GET["t"];
$query_gjson = "SELECT P.Pert_NumExp, J.Juzg_Nombre, P.Pert_Datum, P.Pert_Norte_PIPM, P.Pert_Este_PIPM, P.Pert_N_S, P.Pert_E_W, P.Pert_SupTotal, P.Pert_Nro_Lados, P.Pert_Nombre, T.Titu_Nombre, P.Pert_ID, T.Titu_ID FROM Pertenencia P, Juzgado J, Titular T, Proyecto Pr WHERE P.Proyecto_Proy_ID=Pr.Proy_ID and Pr.Titular_Titu_ID=T.Titu_ID and P.Juzgado_Juzg_ID=J.Juzg_ID and P.Proyecto_Proy_ID=$t;";
#print "$query_gjson\n";
$result_gjson = mysqli_query($connect, $query_gjson);
$geojson = array( 'type' => 'FeatureCollection', 'features' => array());
while($row = mysqli_fetch_array($result_gjson)) {
   $norte=$row[3]-($row[5]/2);
   $sur=$row[3]+($row[5]/2);
   $este=$row[4]+($row[6]/2);
   $weste=$row[4]-($row[6]/2);
   $pm=punto($row[4],$row[3],19);
   $v1=punto($weste,$norte,19);
   $v2=punto($este,$norte,19);
   $v3=punto($este,$sur,19);
   $v4=punto($weste,$sur,19);
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
   $InfoWindowContent="<div class=\"widget style1 yellow-bg\"><i class=\"fa fa-map-marker\"></i> ".$row[9]."<br><a href=\"pert.php?id_p=".$row[11]."&c=".$row[12]."&p=$t\">Ver detalles</a></div>";

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
                        'coordinates' => array([$v1,
                                        $v2,
                                        $v3,
                                        $v4,
                                        $v1]
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
