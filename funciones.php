<?php
function nacionalidades($mysqli, $formulario, $basico,$ordenacion, $opciones = true)
{
    echo " <select name='$ordenacion' form='$formulario'>";
    $nada = '';
    if ($opciones) {
        echo "<option value=$nada>$basico</option>";
    }
        $pais = "SELECT * FROM NACIONALITATS";
        $cursor = $mysqli->query($pais) or die($pais);
        while ($row = $cursor->fetch_assoc()) {
            echo '<option value="' . $row['NACIONALITAT'] . '">' . $row['NACIONALITAT'] . '</option>';
        }
    echo "</select>";
}
