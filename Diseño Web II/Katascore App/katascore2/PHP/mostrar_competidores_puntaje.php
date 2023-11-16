<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../CSS/publico.css">   
</head>
<body>
<div class ="sorteopublico">
    <br>
    
<img src="../IMG/LogoCuk.png" alt="" class="logoresultados">
<br>
<?php
$mysqli = new mysqli("localhost", "root", "", "bdkatascore");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

$competencia = 'activa';
$categoria = 'Semifinal'; 


$sql_aka = "SELECT e.id_competidor, c.nombre, c.apellido, e.cinturon, e.categoria, e.puntaje_total
        FROM ejecucion AS e
        INNER JOIN competidor AS c ON e.id_competidor = c.id_competidor
        WHERE e.estadocompetencia = '$competencia' AND e.ronda = '$categoria' AND e.cinturon = 'AKA'
        ORDER BY e.puntaje_total DESC
        LIMIT 3";

$sql_ao = "SELECT e.id_competidor, c.nombre, c.apellido, e.cinturon, e.categoria, e.puntaje_total
        FROM ejecucion AS e
        INNER JOIN competidor AS c ON e.id_competidor = c.id_competidor
        WHERE e.estadocompetencia = '$competencia' AND e.ronda = '$categoria' AND e.cinturon = 'AO'
        ORDER BY e.puntaje_total DESC
        LIMIT 3";

$result_aka = $mysqli->query($sql_aka);
$result_ao = $mysqli->query($sql_ao);

if ($result_aka && $result_ao) {
    $akaShown = false;
    $aoShown = false;

    while ($row = $result_aka->fetch_assoc()) {
        if (!$akaShown) {
            echo "<h3 style='color: red;'>AKA</h3>";
            $akaShown = true;
        }
        echo "<li>" . $row['nombre'] . " " . $row['apellido'] . " - Puntaje: " . $row['puntaje_total'] . "</li>";
    }

    while ($row = $result_ao->fetch_assoc()) {
        if (!$aoShown) {
            echo "<h3 style='color: blue;'>AO</h3>";
            $aoShown = true;
        }
        echo "<li>" . $row['nombre'] . " " . $row['apellido'] . " - Puntaje: " . $row['puntaje_total'] . "</li>";
    }
} else {
    echo "Error en la consulta: " . $mysqli->error;
}

$sql_competencia_categoria = "SELECT DISTINCT id_competencia, categoria FROM ejecucion WHERE ronda = 'Semifinal' AND estadocompetencia = 'activa'";
$result_competencia_categoria = $mysqli->query($sql_competencia_categoria);

if ($result_competencia_categoria) {
   
    $row_competencia_categoria = $result_competencia_categoria->fetch_assoc();
    $idcompetencia = $row_competencia_categoria['id_competencia'];
    $idcategoria = $row_competencia_categoria['categoria'];
}

$mysqli->close();


?>
<br>
<br>
<form action="mostrar_competidores.php">
    <input type="submit" class="Competidor" value="Competidor" >
</form>
<br>
<br>

<form action="mostrar_resultados.php" method="get">
    <input type="hidden" name="id_competencia" value="<?php echo $idcompetencia; ?>">
    <input type="hidden" name="categoria" value="<?php echo $idcategoria; ?>">
    <input type="submit" value="Mostrar Resultados" class="boton-competidor2">
</form>

<br>
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
