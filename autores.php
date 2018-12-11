
<!DOCTYPE html>
 <html>
 <head>
 <meta charset='UTF-8'>
 <title>AUTORS</title>
 <link rel="stylesheet" type="text/css" href="estil.css">

<style>

</style>
 </head>
 <body>
<header>
<img  src="logo.png" ><br>
</header>

 <?php
 /*Archivo para funciones*/
 INCLUDE ("funciones.php");
/*Tipo de Orden a seguir*/
$ordenar = "NOM_AUT ASC";
if (isset($_POST['ordenar'])) {
    $ordenar = $_POST['ordenar'];
}
if (isset($_POST['Ascendente'])) {
    $ordenar = "NOM_AUT ASC";
}
if (isset($_POST['Descendente'])) {
    $ordenar = "NOM_AUT DESC";
}
if (isset($_POST['codiAsc'])) {
    $ordenar = "ID_AUT ASC";
}
if (isset($_POST['codiDesc'])) {
    $ordenar = "ID_AUT DESC";
}
/*Realizar la conexion*/
$mysqli = new mysqli("localhost", "biblioteca", "Calamorell", "biblioteca");

// Comprobar conexion
if ($mysqli->connect_error) {
    die("Conexion fallida: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");

/*Filtro */
$filtra = "";
$busqueda = (isset($_POST['busqueda']) ? $_POST['busqueda'] : '');
if ($busqueda != '') {
    $filtra = "where ID_AUT = '$busqueda' OR NOM_AUT like '%$busqueda%'";
}

/*Obtener las paginas*/
if (isset($_POST['calculopaginas'])) {
    $total_paginas = $_POST['calculopaginas'];
} else {
    $total_paginas = 1;
}

/*Creamos el contenido de  calcular las pag*/
if (isset($_POST['filtro'])) {
    $mantener = $_POST['filtro'];
} else {
    $mantener = 0;
}
$resultados = 20;
$pagina = 0;
$sql2 = "select count(*) as total from autors $filtra";
$cursor3 = $mysqli->query($sql2) or die($sql2);
if ($fila2 = $cursor3->fetch_assoc()) {
    $finicher2 = $fila2['total'];
    $pagina = ceil($finicher2 / $resultados);
}
/*Inicio Paginación*/
/*Calcular el siguiente*/
if (isset($_POST['siguiente'])) {
    if (!($total_paginas == $pagina)) {

        $mantener = $mantener + $resultados;
        $total_paginas = $total_paginas + 1;
    }
}
if (isset($_POST['anterior'])) {

    if ($total_paginas != 1) {
        $mantener = $mantener - $resultados;
        $total_paginas = $total_paginas - 1;
    }
}
if (isset($_POST['inicial'])) {
    $mantener = 0;
    $total_paginas = 1;

}
if (isset($_POST['final'])) {

    $sql = "select count(*) as total from autors $filtra";
    $cursor2 = $mysqli->query($sql) or die($sql);
    if ($fila = $cursor2->fetch_assoc()) {
        $finicher = $fila['total'];
    }
    $mantener = $finicher - $resultados;
    $total_paginas = ceil($finicher / $resultados);
}
/*Fin Paginación*/
$query = "SELECT ID_AUT, NOM_AUT, FK_NACIONALITAT FROM autors $filtra";
$query .= " ORDER BY $ordenar LIMIT $mantener , $resultados";

/*Realizar la accion de borrar*/
$borrar = "";
if (isset($_POST['eliminar'])) {
    $borrar = $_POST['eliminar'];
    $queryBorrar = "DELETE from AUTORS where ID_AUT = $borrar";
    $cursor = $mysqli->query($queryBorrar) or die($queryBorrar);
}
/*Incluir el nuevo Autor*/
if (isset($_POST['añadir'])) {
    $nuevo = $mysqli->real_escape_string($_POST['busquedaañadir']);
    $incluir = "INSERT INTO AUTORS(ID_AUT, NOM_AUT) VALUES((SELECT MAX(ID_AUT)+1 FROM autors AS TOTAL), '$nuevo')";
    $cursor5 = $mysqli->query($incluir) or die("Error query" . $incluir);
}
/*editar*/
$modificar  = "";
if(isset($_POST['modificar'])){
$modificar = $_POST['modificar'];
}
/*Añadir los cambios*/
if(isset($_POST['guardar'])){
$editarAutor = $mysqli->real_escape_string($_POST['editarAutor']);
$ordenacion=($_POST['ordenacion']);
$guardar = $mysqli->real_escape_string($_POST['guardar']);
/*Poner pais en null o la nacionalidad*/
if($ordenacion==""){
$queryGuardar = "UPDATE AUTORS SET NOM_AUT ='$editarAutor',FK_NACIONALITAT= null where ID_AUT = $guardar";
}else{
    $queryGuardar = "UPDATE AUTORS SET NOM_AUT ='$editarAutor',FK_NACIONALITAT= '$ordenacion' where ID_AUT = $guardar";
}
/*Guardar cambios*/
$resultat = $mysqli->query($queryGuardar) or die($queryGuardar);
}
/*Select de Nacionalitat*/
$basico="Elija una opcion";

echo "<table border = 1 px>";
/*Botones para Editar y Borrar*/
if ($cursor = $mysqli->query($query)) {
    echo "<tr><td>ID</td><td>Autor</td><td>Nacionalidad</td><td colspan='2'>Acciones</td><tr>";
    while ($row = $cursor->fetch_assoc()) {
       
        if($modificar == $row["ID_AUT"]){
            echo "<tr><td>" . $row["ID_AUT"] . "</td>";
                    echo "<td><input type='text' name='editarAutor' class='ancho' value='{$row["NOM_AUT"]}' form='formulario' /></td>";
                    echo "<td>";/*Select con las nacionalidades*/
                    echo nacionalidades($mysqli, 'formulario', $basico, 'ordenacion');
                    echo"</td>";/*Botones de Si y No*/
                    echo "<td><button type='submit' form='formulario' name='guardar' class='confirmar' value='{$row["ID_AUT"]}'>Si</button></td>
                    <td><button type='submit' form='formulario' name='denegar' class='negar' value='{$row["ID_AUT"]}'>No </button></td>";
                }else{
                    echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] ."</td><td>" . $row["FK_NACIONALITAT"] ."</td>";
                    echo "<td><button type='submit' form='formulario' name='modificar' value='{$row["ID_AUT"]}'>
                    Editar</button></td>";
                    echo "<td><button type='submit' form='formulario' name='eliminar' value='{$row["ID_AUT"]}'>
    Borrar</button></td>";
                }
        echo " </tr>";

    }
    $cursor->free();
}
/*Input amb el cerca de nom o codi*/
?>
<div>
<form name="ordenacion" action=""  id="formulario" method="POST" >
   <input type="submit" name="Ascendente"  value="A-Z"  />
   <input type="submit" name="Descendente"  value="Z-A" /><BR>
   <input type="submit" name="codiAsc"  value="0-9"  />
   <input type="submit" name="codiDesc"  value="9-0" /><BR>

   <input type="hidden" name="calculopaginas" value="<?=$total_paginas?>"/>
   <input type="hidden" name="ordenar" value="<?=$ordenar?>"/>
   <input type="hidden" name="filtro" value="<?=$mantener?>"/>

    <input type="submit" name="inicial" value="<<"/>
    <input type="submit" name="anterior" value="<"/>
    <input type="submit" name="siguiente" value=">"/>
    <input type="submit" name="final" value=">>"/>
    <br>

    <input type ="text" name="busqueda" class="ancho" value="<?=$busqueda?>"/>
    <input type="submit" value="cercarID" >
    <br>
    <input type="submit" name="mostrar" value="..." />
</form><br>
<div>
<?php
/*Nuevo Autor*/
if (isset($_POST['mostrar'])) {
    echo "<fieldset><b>Añadir un nuevo Autor</b>";
    echo "<form name='nuevosnombres'  action='' method='POST'>";
    echo "<input type='text' name='busquedaañadir' id='busquedaañadir' class='ancho'/>";
    echo "<button type='submit' name='añadir'>Añadir:</button>";
    echo "</form></fieldset>";
}
echo " $total_paginas" . "/" . "$pagina <br>";

$mysqli->close();
?>
 </body>
 </html>
