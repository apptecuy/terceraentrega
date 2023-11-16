
<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../CSS/publico.css">   
</head>
<body>
<div class ="sorteopublico">
<?php
 $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}


$sql = "SELECT e.id_competidor, c.nombre, c.apellido, e.cinturon, e.categoria
        FROM ejecucion AS e
        INNER JOIN competidor AS c ON e.id_competidor = c.id_competidor
        WHERE e.estadocompetencia = 'activa'
        ORDER BY e.cinturon";

$result = $mysqli->query($sql);

if ($result) {
    $categoria = null;
    $akaShown = false;
    $aoShown = false;

    while ($row = $result->fetch_assoc()) {
        if (!$categoria) {
            $categoria = $row['categoria'];
            echo "<h2>Competidores de la categoría: $categoria</h2>";
        }

        if ($row['cinturon'] === 'AKA' && !$akaShown) {
            echo "<h3 style='color: red;'>$row[cinturon]</h3>";
            $akaShown = true;
        } elseif ($row['cinturon'] === 'AO' && !$aoShown) {
            echo "<h3 style='color: blue;'>$row[cinturon]</h3>";
            $aoShown = true;
        }

        echo "<li>" . $row['nombre'] . " " . $row['apellido'] . "</li>";
    }
} else {
    echo "Error en la consulta: " . $mysqli->error;
}
           
$mysqli->close();
?>
<br>
   
<br>
   <form action="mostrar_competidores.php">
    <input type="submit" value ="Competidor" class= "boton-competidor">

    </form>
<br>
    <form action="menu_publico.php">
    <input type="submit" value ="Volver" class= "Volver"> 
    </form>
    <br>
    <br>
    <br>
    </div>

</body>
</html>
