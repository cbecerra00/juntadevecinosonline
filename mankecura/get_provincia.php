<?php
include_once('lib/lib.inc');
$connect = db();
if($_POST['id']) {
   $id=$_POST['id'];
#   $id=3;
   $query_c = "SELECT P.Prov_Nombre, P.Prov_ID FROM Provincia P WHERE P.Region_Regi_ID=$id ORDER BY P.Prov_Nombre ASC;";
   #print "$query_c\n";
   $result_c = mysqli_query($connect, $query_c);
?>
       <option selected="selected">--Selecciona provincia--</option>
<?php
   while($row_c = mysqli_fetch_array($result_c)) {
?>
        <option value="<?php echo $row_c[1]; ?>"><?php echo $row_c[0]; ?></option>
<?php
   }
}
?>
