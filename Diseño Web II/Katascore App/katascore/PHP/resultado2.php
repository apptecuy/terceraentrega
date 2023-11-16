<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/resultadofinal.css">
    <title>Resultado Final</title>
</head>
<body>


<div class="resultadofinal" >
    <br>
    <img src="../IMG/LogoCuk.png" class="imagen" alt="">
<?php
 $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

if ($mysqli->connect_error) {
    die("Error en la conexión a la base de datos: " . $mysqli->connect_error);
}

$competencia = $_POST["competencia"];
echo $competencia;
echo "<h4> Competencia: $competencia</h4>";
echo  "<br>";
$categoria = $_POST["categoria"];
echo  "<h4> Categoria: $categoria</h4>";
echo  "<br>";
echo  "<br>";
$idCompetidorGanadorAKA = $_POST["competidor1"];

$idCompetidorGanadorAO = $_POST["competidor2"];

$idTercerCompetidorAKA = $_POST["competidor3"];

$idCompetidorPuntajeMasBajoAO = $_POST["competidor4"];


function obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idCompetidor) {
    $query = "SELECT puntaje_total
              FROM ejecucion
              WHERE id_competencia = ? AND categoria = ? AND id_competidor = ? AND ronda = 'final'";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iss", $competencia, $categoria, $idCompetidor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['puntaje_total'];
    } else {
        return 0; 
    }
}



$puntajeCompetidorGanadorAKA = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idCompetidorGanadorAKA);
$puntajeCompetidorGanadorAO = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idCompetidorGanadorAO);
$puntajeTercerPuestoAKA = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idTercerCompetidorAKA);
$puntajeSegundoPuestoAO = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idCompetidorPuntajeMasBajoAO);




$resultadoCompetidorGanadorAKA = "segundo";
$resultadoCompetidorGanadorAO = "primero";

if ($puntajeCompetidorGanadorAKA > $puntajeCompetidorGanadorAO) {
    $resultadoCompetidorGanadorAKA = "primero";
    $resultadoCompetidorGanadorAO = "segundo";
}

$resultadoTercerPuestoAKA = "quinto";
$resultadoSegundoPuestoAO = "tercero";

if ($puntajeTercerPuestoAKA > $puntajeSegundoPuestoAO) {
    $resultadoTercerPuestoAKA = "tercero";
    $resultadoSegundoPuestoAO = "quinto";
}



function almacenarResultados($mysqli, $competencia, $categoria, $idCompetidor, $resultado) {
    $query = "INSERT INTO resultadocompetencia (id_competidor, id_competencia, categoria, resultado) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iiss", $idCompetidor, $competencia, $categoria, $resultado);
    $stmt->execute();
}


$resultadoExistenteAKA = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idCompetidorGanadorAKA);

$resultadoExistenteAO = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idCompetidorGanadorAO);

$resultadoExistenteTercerCompetidorAKA = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idTercerCompetidorAKA);

$resultadoExistenteCompetidorPuntajeMasBajoAO = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idCompetidorPuntajeMasBajoAO);

if ($resultadoExistenteAKA !== null) {
    echo "Resultado existente para ganadorAKA: " . $resultadoExistenteAKA;
    echo  "<br>";
} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idCompetidorGanadorAKA, $resultadoCompetidorGanadorAKA);
}

if ($resultadoExistenteAO !== null) {
    echo "Resultado existente para ganadorAO: " . $resultadoExistenteAO;
    echo  "<br>";
} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idCompetidorGanadorAO, $resultadoCompetidorGanadorAO);
}

if ($resultadoExistenteTercerCompetidorAKA !== null) {
    echo "Resultado existente para tercerCompetidorAKA: " . $resultadoExistenteTercerCompetidorAKA;
    echo  "<br>";
} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idTercerCompetidorAKA, $resultadoTercerPuestoAKA);
}

if ($resultadoExistenteCompetidorPuntajeMasBajoAO !== null) {
    echo "Resultado existente para competidorPuntajeMasBajoAO: " . $resultadoExistenteCompetidorPuntajeMasBajoAO;
    echo  "<br>";

} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idCompetidorPuntajeMasBajoAO, $resultadoSegundoPuestoAO);
}

function obtenerResultadoExistente($mysqli, $competencia, $categoria, $idCompetidor) {
    $query = "SELECT resultado
              FROM resultadocompetencia
              WHERE id_competencia = ? AND categoria = ? AND id_competidor = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iss", $competencia, $categoria, $idCompetidor);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['resultado'];
    } else {
        return null;
    }
}

echo  "<br>";
echo "<h3>Resultados almacenados exitosamente.</h3>";
echo  "<br>";

$query = "SELECT id_competidor, resultado
          FROM resultadocompetencia
          WHERE id_competencia = ? AND categoria = ?
          ORDER BY FIELD(resultado, 'primero', 'segundo', 'tercero', 'quinto')";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("is", $competencia, $categoria);
$stmt->execute();
$result = $stmt->get_result();

echo "<h4>Resultados de la competencia $competencia en la categoría $categoria:</h4>";

while ($row = $result->fetch_assoc()) {
    echo "ID Competidor: " . $row['id_competidor'] . ", Resultado: " . $row['resultado'] . "<br>";
}
echo  "<br>";
echo  "<br>";
$stmt->close();
?>
    </div>
    </body>
</html>