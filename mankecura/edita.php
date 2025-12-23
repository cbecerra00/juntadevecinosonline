<?php
include_once "lib/lib.inc";
session_start();
ini_set("session.cookie_domain", ".mankecura.com");
is_logeado();
#top();
$connect = db();
$query_g = "SELECT ST_AsGeoJSON(Pert_Geometrico,12),ST_AsGeoJSON(Pert_Geometrico_LatLon,12),Pert_Nro_Lados FROM Pertenencia WHERE Pert_ID=$_GET[p];";
#print "$query_g\n";
$result_g = mysqli_query($connect, $query_g);
while($row_g = mysqli_fetch_array($result_g)) {
   $lala=((array) json_decode($row_g[0]));
   #print_r($lala['coordinates']);
   $nr = count($lala['coordinates'][0])-1;
   print "<pre>";
   for ($k=0 ; $k <= $nr ; $k++) {
      print "Valor X: ".$lala['coordinates'][0][$k][0]." Valor Y: ".$lala['coordinates'][0][$k][1]."<br>\n";
   }
   print "</pre>";
   #print "Valor: ".$lala['coordinates'][0][0][0]."\n";
}
?>
