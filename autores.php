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

$ordenar = "NOM_AUT ASC";
if(isset($_POST['ordenar'])){
    $ordenar=$_POST['ordenar'];
}

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


/**/
if(isset($_POST['calculopaginas'])){
    $total_paginas=$_POST['calculopaginas'];
}else{
    $total_paginas=1;
}

/*Creamos el contenido de  calcular las pag*/
if(isset($_POST['filtro'])){
    $mantener= $_POST['filtro'];
}else{
    $mantener=0;
}
$resultados=20;
$pagina=0;
$sql2="select count(*) as total from autors $filtra";
    $cursor3=$mysqli->query($sql2)or die ($sql2);
    if($fila2=$cursor3->fetch_assoc()){
        $finicher2=$fila2['total'];
       $pagina= ceil($finicher2/$resultados);
    }



/*Calcular el siguiente*/
if(isset($_POST['siguiente'])){
    if(!($total_paginas==$pagina)){


    $mantener=$mantener+$resultados;
    $total_paginas=$total_paginas+1;
    }
}
if(isset($_POST['anterior'])){

    if($total_paginas!= 1){
    $mantener=$mantener-$resultados;
    $total_paginas=$total_paginas-1;
    }
}
if(isset($_POST['inicial'])){
    $mantener=0;
    $total_paginas=1;

}
if(isset($_POST['final'])){
    
    $sql="select count(*) as total from autors $filtra";
    $cursor2=$mysqli->query($sql)or die ($sql);
    if($fila=$cursor2->fetch_assoc()){
        $finicher=$fila['total'];
    }
    $mantener=$finicher-$resultados;
    $total_paginas= ceil($finicher/$resultados);
}










$query = "SELECT ID_AUT, NOM_AUT FROM autors $filtra";
$query .= " ORDER BY $ordenar LIMIT $mantener , $resultados";

/*Obtenemos los datos de la consulta global
if($_POST['busqueda'] = ''){
$principal="SELECT ID_AUT, NOM_AUT FROM autors $filtra";
}else{
    $principal=$query;
}

echo"hola".$principal;
$consulta_global= mysqli_query($mysqli,$principal);
Todos los resultados
$total_registros=mysqli_num_rows($consulta_global);
Obtener el total de paginas
Registros/el limite que quiero
$total_paginas= ceil($total_registros/$resultados);
*/

echo "<table border = 1 px>";
if ($cursor = $mysqli->query($query)) {
    while ($row = $cursor->fetch_assoc()) {
        echo "<tr><td>" . $row["ID_AUT"] . "</td><td>" . $row["NOM_AUT"] . "</td></tr>";
    }
    $cursor->free();
}
/*Input amb el cerca de nom o codi*/
?>
<form name="formulario" action="" method="POST" >
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
</form><br>

<?php
echo " $total_paginas"."/"."$pagina";
/*
<form name="buscar" action="" method="POST" >
<fieldset>
    <legend>Introduzca un numero <br>o un nombre<br>para buscar</legend>
    <input type ="text" name="busqueda" class="ancho" />
    <input type="submit" value="cercarID" >
    </fieldset>
</form><br>*/
?>


 </body>
 </html>
