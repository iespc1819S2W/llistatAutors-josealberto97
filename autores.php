
<!DOCTYPE html>
 <html>
 <head>
 <meta charset='UTF-8'>
 <title>AUTORS</title>
<style>
    *{
        text-align:center;
    }
    img{
    margin: auto;
    }
  table{
      margin-left: 38%;
  }
  input{
      width: 50px;
  }
</style>
 </head>
 <body>
<header>

<img  src="logo.png" ><br>
</header>

 <?php
$mysqli = new mysqli("localhost","biblioteca","Calamorell","biblioteca");

// Comprobar conexion
if ($mysqli->connect_error) {
    die("Conexion fallida: " . $mysqli->connect_error);
}
echo "Se ha podido conectar correctamente";
echo "<br>";
$mysqli->set_charset("utf8");

?>
<form name="formulario" action="" method="POST" >

   <input type="submit" name="Ascendente"  value="A-Z"  />
   <input type="submit" name="Descendente"  value="Z-A" /><BR>

   <input type="submit" name="codiAsc"  value="0-9"  />
   <input type="submit" name="codiDesc"  value="9-0" /><BR>

</form>
<?php
$ordenar = "NOM_AUT ASC";
if(isset($_POST['Ascendente'])){
    $ordenar = "NOM_AUT ASC";
}
if(isset($_POST['Descendente'])){
    $ordenar = "NOM_AUT DESC";
}
if(isset($_POST['codiAsc'])){
    $ordenar = "ID_AUT ASC";
}
if(isset($_POST['codiDesc'])){
    $ordenar = "ID_AUT DESC";
}
$query = "SELECT ID_AUT, NOM_AUT FROM autors ORDER BY $ordenar LIMIT 0 , 20";

echo "<table border = 1 px>";

if ($cursor = $mysqli->query($query)) {
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] . "</td></tr>";
    }
    $cursor->free();
}


$mysqli->close();
echo" Query utilizada para el orden<br>
SELECT `ID_AUT`,`NOM_AUT` FROM `autors` ORDER BY `NOM_AUT` DESC LIMIT 0,10";



?>

 </body>
 </html>


