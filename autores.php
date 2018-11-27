<?php
session_start();
session_unset();
?>
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
  .ancho{
      width: 200px;
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

/*Filtro */
$filtra = "";
$busqueda=(isset($_POST['busqueda'])? $_POST['busqueda'] : '');
if($busqueda != ''){
    $filtra = "where ID_AUT = '$busqueda' OR NOM_AUT like '%$busqueda%'";
}
?>
<form name="formulario" action="" method="POST" >
   <input type="submit" name="Ascendente"  value="A-Z"  />
   <input type="submit" name="Descendente"  value="Z-A" /><BR>
   <input type="submit" name="codiAsc"  value="0-9"  />
   <input type="submit" name="codiDesc"  value="9-0" /><BR>
</form><br>
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

$resultados=10;
if (isset($_GET["pagina"])) {
    if (is_numeric($_GET["pagina"])) {
        if ($_GET["pagina"] == 1) {
            $pagina=1;
        }else{
          $pagina=$_GET["pagina"];  
        }
    }
}else{
    $pagina=1;
}
$inicio=($pagina-1)*$resultados;

$query = "SELECT ID_AUT, NOM_AUT FROM autors $filtra";
$query .= " ORDER BY $ordenar LIMIT $inicio , $resultados";

/*Obtenemos los datos de la consulta global*//*
if(!isset($_SESSION["principal"])){
$principal=$query;
$_SESSION["principal"]=$principal;
}else{
    $principal=$_SESSION["principal"];
}*/
$principal="SELECT ID_AUT, NOM_AUT FROM autors $filtra";
echo"hola".$principal;
$consulta_global= mysqli_query($mysqli,$principal);
/*Todos los resultados*/
$total_registros=mysqli_num_rows($consulta_global);
/*Obtener el total de paginas*/
/*Registros/el limite que quiero*/
$total_paginas= ceil($total_registros/$resultados);


echo "<table border = 1 px>";
if ($cursor = $mysqli->query($query)) {
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] . "</td></tr>";
    }
    $cursor->free();
}
/*Input amb el cerca de nom o codi*/
?>

<form name="buscar" action="" method="POST" >
<fieldset>
    <legend>Introduzca un numero <br>o un nombre<br>para buscar</legend>
    <input type ="text" name="busqueda" class="ancho" />
    <input type="submit" value="cercarID" >
    </fieldset>
</form><br>
<?php
for ($i=1; $i<=$total_paginas; $i++) {
	//En el bucle, muestra la paginación
	echo "<a href='?pagina=".$i."'>".$i."</a> | ";
}; ?>

 </body>
 </html>
