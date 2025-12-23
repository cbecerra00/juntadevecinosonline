<?php
include_once('lib/lib.inc');
$connect = db();
if($_POST['id']) {
   $id=$_POST['id'];
#   $id=44;
   $query_c = "SELECT J.Juzg_Nombre, J.Juzg_ID FROM Juzgado J, Ciudad C, Provincia P, Region R WHERE J.Ciudad_Ciud_ID=C.Ciud_ID and C.Provincia_Prov_ID=P.Prov_ID and P.Region_Regi_ID=R.Regi_ID and P.Prov_ID=$id;";
   #print "$query_c\n";
   $result_c = mysqli_query($connect, $query_c);
?>
        <option selected="selected">-- Selecciona Juzgado --</option>
<?php
   while($row_c = mysqli_fetch_array($result_c)) {
?>
        <option value="<?php echo $row_c[1]; ?>"><?php echo $row_c[0]; ?></option>
<?php
   }
}
?>
