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

function createFeature () {
   $feature = new stdClass();
   $feature->type = 'Feature';
   $feature->properties = new stdClass();
   $feature->geometry = new stdClass();
   #$feature->geometry->type = 'Polygon';
   return $feature;
}
function createCollection () {
   $collection = new stdClass();
   $collection->type = 'FeatureCollection';
   $collection->features = array();
   return $collection;
}
$t = $_GET["t"];
$query_gjson = "SELECT P.Pert_NumExp, J.Juzg_Nombre, P.Pert_Datum, P.Pert_Norte_PIPM, P.Pert_Este_PIPM, P.Pert_N_S, P.Pert_E_W, P.Pert_SupTotal, P.Pert_Nro_Lados, P.Pert_Nombre, T.Titu_Nombre, P.Pert_Zona, ST_AsGeoJSON(P.Pert_Geometrico,12), ST_AsGeoJSON(P.Pert_Geometrico_LatLon,12) FROM Pertenencia P, Juzgado J, Titular T, Proyecto Pr WHERE P.Juzgado_Juzg_ID=J.Juzg_ID and Pr.Proy_ID=P.Proyecto_Proy_ID and Pr.Titular_Titu_ID=T.Titu_ID and P.Pert_ID=$t;";
#print "$query_gjson\n";
$result_gjson = mysqli_query($connect, $query_gjson);
#$geojson = array( 'type' => 'Feature', 'Feature' => array());
while($row = mysqli_fetch_array($result_gjson)) {
   $norte=$row[3]-($row[5]/2);
   $sur=$row[3]+($row[5]/2);
   $este=$row[4]+($row[6]/2);
   $weste=$row[4]-($row[6]/2);
   $v1=punto($weste,$norte,$row[11]);
   $v2=punto($este,$norte,$row[11]);
   $v3=punto($este,$sur,$row[11]);
   $v4=punto($weste,$sur,$row[11]);
   $pm=punto($row[4],$row[3],$row[11]);

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
   
   $InfoWindowContent="<div class=\"widget style1 yellow-bg\"><i class=\"fa fa-map-marker\"></i> ".$row[9]."</div>";
   $marker = array(
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
    );
}
header('Content-type: application/json');
echo json_encode($marker, JSON_NUMERIC_CHECK);
?>
