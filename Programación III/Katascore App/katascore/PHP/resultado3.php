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
echo  "<br>";
$competencia = $_POST["competencia"];
echo "<h4> Competencia: $competencia</h4>";
echo  "<br>";
echo  "<br>";
$categoria = $_POST["categoria"];
echo  "<h4> Categoria: $categoria</h4>";
echo  "<br>";
echo  "<br>";
$idCompetidorGanadorAKA = $_POST["competidor1"];

$idCompetidorGanadorAO = $_POST["competidor2"];

$idTercerPuestoAKA = $_POST["competidor3"];

$idSegundoPuestoAO = $_POST["competidor4"];

$idTercerPuestoAO = $_POST["competidor5"];

$idSegundoPuestoAKA = $_POST["competidor6"];




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
$puntajeTercerPuestoAKA = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idTercerPuestoAKA);
$puntajeSegundoPuestoAO = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idSegundoPuestoAO);
$puntajeTercerPuestoAO = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idTercerPuestoAO);
$puntajeSegundoPuestoAKA = obtenerPuntajeTotal($mysqli, $competencia, $categoria, $idSegundoPuestoAKA);


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

$resultadoTercerPuestoAO = "quinto";
$resultadoSegundoPuestoAKA = "tercero";

if ($puntajeTercerPuestoAO > $puntajeSegundoPuestoAKA) {
    $resultadoTercerPuestoAO = "quinto";
    $resultadoSegundoPuestoAKA = "tercero";
}


function almacenarResultados($mysqli, $competencia, $categoria, $idCompetidor, $resultado) {
    $query = "INSERT INTO resultadocompetencia (id_competidor, id_competencia, categoria, resultado) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iiss", $idCompetidor, $competencia, $categoria, $resultado);
    $stmt->execute();
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


$resultadoExistenteAKA = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idCompetidorGanadorAKA);


$resultadoExistenteAO = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idCompetidorGanadorAO);


$resultadoExistenteTercerPuestoAKA = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idTercerPuestoAKA);


$resultadoExistenteSegundoPuestoAO = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idSegundoPuestoAO);


$resultadoExistenteTercerPuestoAO = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idTercerPuestoAO);


$resultadoExistenteSegundoPuestoAKA = obtenerResultadoExistente($mysqli, $competencia, $categoria, $idSegundoPuestoAKA);


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


if ($resultadoExistenteTercerPuestoAKA !== null) {
    echo "Resultado existente para tercerPuestoAKA: " . $resultadoExistenteTercerPuestoAKA;
    echo  "<br>";
} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idTercerPuestoAKA, $resultadoTercerPuestoAKA);
}


if ($resultadoExistenteSegundoPuestoAO !== null) {
    echo "Resultado existente para segundoPuestoAO: " . $resultadoExistenteSegundoPuestoAO;
    echo  "<br>";
} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idSegundoPuestoAO, $resultadoSegundoPuestoAO);
}


if ($resultadoExistenteTercerPuestoAO !== null) {
    echo "Resultado existente para tercerPuestoAO: " . $resultadoExistenteTercerPuestoAO;
    echo  "<br>";

} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idTercerPuestoAO, $resultadoTercerPuestoAO);
}

 
if ($resultadoExistenteSegundoPuestoAKA !== null) {
    echo "Resultado existente para segundoPuestoAKA: " . $resultadoExistenteSegundoPuestoAKA;
    echo  "<br>";

} else {
    almacenarResultados($mysqli, $competencia, $categoria, $idSegundoPuestoAKA, $resultadoSegundoPuestoAKA);
    echo  "<br>";

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