<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/siguienteronda.css">
    <title>Document</title>
</head>
<body>

<?php
$mysqli = new mysqli("localhost", "root", "", "bdkatascore");

if ($mysqli->connect_error) {
    die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
}

session_start();
$competencia = $_SESSION['competencia'];
$categoria = $_SESSION['categoria'];


$query = "SELECT id_competencia, cinturon, tatami, categoria FROM ejecucion WHERE id_competidor = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $competidor);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();



$numCompetidores = obtenerNumeroTotalCompetidores($mysqli, $competencia, $categoria);

echo '<div class="siguienteronda">'; 
echo  "<br>";
echo '<img class="logo" src="../IMG/LogoCuk.png" class="logo">';

echo  "<br>";
echo  "<h1>Gran FINAL</h1>";
echo  "<br>";

switch ($numCompetidores) {
    case 2:
        function obtenerPuestos($mysqli, $competencia, $categoria) {
            $puestos = array("primero" => null, "segundo" => null);
    
            $query = "SELECT id_ejecucion FROM ejecucion WHERE id_competencia = ? AND categoria = ? AND estadocompetencia = 'activa' ORDER BY puntaje_total DESC LIMIT 2";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ss", $competencia, $categoria);
            $stmt->execute();
            $result = $stmt->get_result();
    
            $resultados = array("primero", "segundo");
            $index = 0;
    
            while ($row = $result->fetch_assoc()) {
                $puesto = $resultados[$index];
                $puestos[$puesto] = $row['id_ejecucion'];
                $index++;
            }
    
            return $puestos;
        }
    
        function insertarResultados($mysqli, $competencia, $categoria, $puestos) {
            $competencia = $mysqli->real_escape_string($competencia);
            $categoria = $mysqli->real_escape_string($categoria);
    
       
            $resultados = array("primero", "segundo");
    
            foreach ($resultados as $resultado) {
                $id_ejecucion = $puestos[$resultado];
    
                if ($id_ejecucion !== null) {
                 
                    $query = "SELECT id_competidor FROM ejecucion WHERE id_ejecucion = ?";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("i", $id_ejecucion);
                    $stmt->execute();
                    $result = $stmt->get_result();
    
                    if ($row = $result->fetch_assoc()) {
                        $id_competidor = $row['id_competidor'];
                    } else {
                        echo "Error: No se pudo encontrar el id_competidor para id_ejecucion: $id_ejecucion";
                        continue; 
                    }
    
                    $query = "INSERT INTO resultadocompetencia (id_competencia, categoria, id_competidor, resultado) VALUES (?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("ssis", $competencia, $categoria, $id_competidor, $resultado);
    
                    if ($stmt->execute()) {
                        echo "Resultado insertado correctamente para el competidor con id: $id_competidor como $resultado<br>";
                    } else {
                        echo "Error al insertar el resultado para el competidor con id: $id_competidor como $resultado<br>";
                    }
                }
            }
        }
    
        $puestos = obtenerPuestos($mysqli, $competencia, $categoria);
        insertarResultados($mysqli, $competencia, $categoria, $puestos);
        break;
    
    case 3:
   

        function obtenerPuestos($mysqli, $competencia, $categoria) {
            $puestos = array("primero" => null, "segundo" => null, "tercero" => null);
        
            $query = "SELECT id_ejecucion FROM ejecucion WHERE id_competencia = ? AND categoria = ? AND estadocompetencia = 'activa' ORDER BY puntaje_total DESC LIMIT 3";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("ss", $competencia, $categoria);
            $stmt->execute();
            $result = $stmt->get_result();
        
            $resultados = array("primero", "segundo", "tercero");
            $index = 0;
        
            while ($row = $result->fetch_assoc()) {
                $puesto = $resultados[$index];
                $puestos[$puesto] = $row['id_ejecucion'];
                $index++;
            }
        
            return $puestos;
        }
        
    
        
        function insertarResultados($mysqli, $competencia, $categoria, $puestos) {
            $competencia = $mysqli->real_escape_string($competencia);
            $categoria = $mysqli->real_escape_string($categoria);
        
         
            $resultados = array("primero", "segundo", "tercero");
        
            foreach ($resultados as $resultado) {
                $id_ejecucion = $puestos[$resultado];
        
                if ($id_ejecucion !== null) {
                  
                    $query = "SELECT id_competidor FROM ejecucion WHERE id_ejecucion = ?";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("i", $id_ejecucion);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                
                    if ($row = $result->fetch_assoc()) {
                        $id_competidor = $row['id_competidor'];
                    } else {
                        echo "Error: No se pudo encontrar el id_competidor para id_ejecucion: $id_ejecucion";
                        continue; 
                    }
        
                    $query = "INSERT INTO resultadocompetencia (id_competencia, categoria, id_competidor, resultado) VALUES (?, ?, ?, ?)";
                    $stmt = $mysqli->prepare($query);
                    $stmt->bind_param("ssis", $competencia, $categoria, $id_competidor, $resultado);
        
                    if ($stmt->execute()) {
                        echo "Resultado insertado correctamente para el competidor con id: $id_competidor como $resultado<br>";
                    } else {
                        echo "Error al insertar el resultado para el competidor con id: $id_competidor como $resultado<br>";
                    }
                }
            }
        }
        
        $puestos = obtenerPuestos($mysqli, $competencia, $categoria);
        insertarResultados($mysqli, $competencia, $categoria, $puestos);

      break;



       case 4:
 
$queryCompetidores = "SELECT id_competidor FROM registra WHERE id_competencia = ? AND categoria = ?";
$stmtCompetidores = $mysqli->prepare($queryCompetidores);
$stmtCompetidores->bind_param("ss", $competencia, $categoria);
$stmtCompetidores->execute();
$resultCompetidores = $stmtCompetidores->get_result();

$idCompetidores = array();
while ($row = $resultCompetidores->fetch_assoc()) {
    $idCompetidores[] = $row['id_competidor'];
}


$puntuacionesAKA = array();
$puntuacionesAO = array();

foreach ($idCompetidores as $idCompetidor) {
  
    $queryPuntuacionAKA = "SELECT puntaje_total FROM ejecucion WHERE id_competidor = ? AND cinturon = 'AKA' AND estadocompetencia = 'activa'";
    $stmtPuntuacionAKA = $mysqli->prepare($queryPuntuacionAKA);
    $stmtPuntuacionAKA->bind_param("i", $idCompetidor);
    $stmtPuntuacionAKA->execute();
    $resultPuntuacionAKA = $stmtPuntuacionAKA->get_result();

    if ($rowPuntuacionAKA = $resultPuntuacionAKA->fetch_assoc()) {
        $puntuacionesAKA[$idCompetidor] = $rowPuntuacionAKA['puntaje_total'];
    }

   
    $queryPuntuacionAO = "SELECT puntaje_total FROM ejecucion WHERE id_competidor = ? AND cinturon = 'AO' AND estadocompetencia = 'activa'";
    $stmtPuntuacionAO = $mysqli->prepare($queryPuntuacionAO);
    $stmtPuntuacionAO->bind_param("i", $idCompetidor);
    $stmtPuntuacionAO->execute();
    $resultPuntuacionAO = $stmtPuntuacionAO->get_result();

    if ($rowPuntuacionAO = $resultPuntuacionAO->fetch_assoc()) {
        $puntuacionesAO[$idCompetidor] = $rowPuntuacionAO['puntaje_total'];
    }
}


arsort($puntuacionesAKA); 
arsort($puntuacionesAO); 

$ganadorAKA = key($puntuacionesAKA); 
$terceroAKA = end($puntuacionesAKA); 

$ganadorAO = key($puntuacionesAO); 
$terceroAO = end($puntuacionesAO); 

function generarNuevoIdEjecucion($mysqli, $competidor, $competencia, $ronda, $cinturon, $tatami, $categoria) {
   
    $ronda = "final";

    
    $query = "INSERT INTO ejecucion (id_competidor, id_competencia, ronda, cinturon, tatami, categoria) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iissss", $competidor, $competencia, $ronda, $cinturon, $tatami, $categoria);

    if ($stmt->execute()) {
      
        return $mysqli->insert_id;
    } else {
       
        return 0; 
    }
}



$nuevoIdEjecucionAKA = generarNuevoIdEjecucion($mysqli, $ganadorAKA, $competencia, 'final', 'AKA', 'tatami1', $categoria);
$nuevoIdEjecucionAO = generarNuevoIdEjecucion($mysqli, $ganadorAO, $competencia, 'final', 'AO', 'tatami1', $categoria);

$urlGanadorAKA = "gestionar.php?id_ejecucion=$nuevoIdEjecucionAKA";
$urlGanadorAO = "gestionar.php?id_ejecucion=$nuevoIdEjecucionAO";

$nombreApellidoGanadorAKA = obtenerNombreApellidoCompetidor($mysqli, $ganadorAKA);
$nombreApellidoGanadorAO = obtenerNombreApellidoCompetidor($mysqli, $ganadorAO);


$urlGanadorAKA = "gestionar.php?id_ejecucion=$nuevoIdEjecucionAKA&nombre_ganador=$nombreApellidoGanadorAKA";
$urlGanadorAO = "gestionar.php?id_ejecucion=$nuevoIdEjecucionAO&nombre_ganador=$nombreApellidoGanadorAO";
echo '<a href="' . $urlGanadorAKA . '" target="_blank">Ganador de AKA: ' . $nombreApellidoGanadorAKA . '</a><br><p>vs</p><br>';
echo '<a href="' . $urlGanadorAO . '" target="_blank">Ganador de AO: ' . $nombreApellidoGanadorAO . '</a>';



$querySegundosAKA = "SELECT id_competidor FROM ejecucion WHERE cinturon = 'AKA' AND id_competidor NOT IN (?, ?) AND estadocompetencia = 'activa' ORDER BY puntaje_total DESC LIMIT 2";
$stmtSegundosAKA = $mysqli->prepare($querySegundosAKA);
$stmtSegundosAKA->bind_param("ii", $ganadorAKA, $terceroAKA);
$stmtSegundosAKA->execute();
$resultSegundosAKA = $stmtSegundosAKA->get_result();

$idSegundosAKA = array();
while ($rowSegundoAKA = $resultSegundosAKA->fetch_assoc()) {
    $idSegundosAKA[] = $rowSegundoAKA['id_competidor'];
}


$querySegundosAO = "SELECT id_competidor FROM ejecucion WHERE cinturon = 'AO' AND id_competidor NOT IN (?, ?) AND estadocompetencia = 'activa' ORDER BY puntaje_total DESC LIMIT 2";
$stmtSegundosAO = $mysqli->prepare($querySegundosAO);
$stmtSegundosAO->bind_param("ii", $ganadorAO, $terceroAO);
$stmtSegundosAO->execute();
$resultSegundosAO = $stmtSegundosAO->get_result();

function insertarResultados($mysqli, $competidor, $competencia, $categoria, $resultado) {
    $queryInsertResultado = "INSERT INTO resultadocompetencia (id_competidor, id_competencia, categoria, resultado) VALUES (?, ?, ?, ?)";
    $stmtInsertResultado = $mysqli->prepare($queryInsertResultado);
    $stmtInsertResultado->bind_param("iiss", $competidor, $competencia, $categoria, $resultado);
    $stmtInsertResultado->execute();
}
$idSegundosAO = array();
while ($rowSegundoAO = $resultSegundosAO->fetch_assoc()) {
    $idSegundosAO[] = $rowSegundoAO['id_competidor'];
}



foreach ($idSegundosAKA as $idSegundoAKA) {
    insertarResultados($mysqli, $idSegundoAKA, $competencia, $categoria, 'tercero');
}

foreach ($idSegundosAO as $idSegundoAO) {
    insertarResultados($mysqli, $idSegundoAO, $competencia, $categoria, 'tercero');
}

echo "<br>";
echo "<br>";
echo  "<br>";
echo '<form action="resultado1.php" method="post" target="_blank">
    <input type="hidden" name="competencia" value="' . $competencia . '">
    <input type="hidden" name="categoria" value="' . $categoria . '">
    <input type="hidden" name="competidor1" value="' . $ganadorAKA . '">
    <input type="hidden" name="competidor2" value="' . $ganadorAO . '">
    <input type="submit" name="calcular_resultado" value="Calcular Resultados" class="Calcuresultados">
</form>';

echo "<br>";


        break;
       

   
        case 5:
            
            $queryCompetenciaCategoria = "SELECT estadocompetencia FROM ejecucion WHERE id_competencia = ? AND categoria = ? AND estadocompetencia = 'activa'";
            $stmtCompetenciaCategoria = $mysqli->prepare($queryCompetenciaCategoria);
            $stmtCompetenciaCategoria->bind_param("ss", $competencia, $categoria);
            $stmtCompetenciaCategoria->execute();
            $resultCompetenciaCategoria = $stmtCompetenciaCategoria->get_result();
        
            
            if ($resultCompetenciaCategoria->num_rows > 0) {
                
                $queryPuntuacionesAKA = "SELECT id_competidor, puntaje_total FROM ejecucion WHERE cinturon = 'AKA' AND estadocompetencia = 'activa' ORDER BY puntaje_total DESC";
                $stmtPuntuacionesAKA = $mysqli->prepare($queryPuntuacionesAKA);
                $stmtPuntuacionesAKA->execute();
                $resultPuntuacionesAKA = $stmtPuntuacionesAKA->get_result();
        
                $puntuacionesAKA = array();
                while ($rowPuntuacionAKA = $resultPuntuacionesAKA->fetch_assoc()) {
                    $puntuacionesAKA[$rowPuntuacionAKA['id_competidor']] = $rowPuntuacionAKA['puntaje_total'];
                }
              
             $queryPuntuacionesAO = "SELECT id_competidor, puntaje_total FROM ejecucion WHERE cinturon = 'AO' ORDER BY puntaje_total DESC";
              $stmtPuntuacionesAO = $mysqli->prepare($queryPuntuacionesAO);
             $stmtPuntuacionesAO->execute();
              $resultPuntuacionesAO = $stmtPuntuacionesAO->get_result();

             $puntuacionesAO = array();
                while ($rowPuntuacionAO = $resultPuntuacionesAO->fetch_assoc()) {
                 $puntuacionesAO[$rowPuntuacionAO['id_competidor']] = $rowPuntuacionAO['puntaje_total'];
                     }
             
            } else {
            
                echo "La competencia o la categoría no están activas.";
            }
        
        
        
          
        
       



$primerPuestoAO = key($puntuacionesAO);
next($puntuacionesAO);
$segundoPuestoAO = key($puntuacionesAO);

$primerPuestoAKA = key($puntuacionesAKA); 
$segundoPuestoAKA = key($puntuacionesAKA);
next($puntuacionesAKA); 
$tercerPuestoAKA = key($puntuacionesAKA); 




$queryPuntajeMasBajoAO = "SELECT id_competidor FROM ejecucion WHERE cinturon = 'AO' ORDER BY puntaje_total ASC LIMIT 1";
$stmtPuntajeMasBajoAO = $mysqli->prepare($queryPuntajeMasBajoAO);
$stmtPuntajeMasBajoAO->execute();
$resultPuntajeMasBajoAO = $stmtPuntajeMasBajoAO->get_result();

$idCompetidorPuntajeMasBajoAO = 0; 
if ($rowPuntajeMasBajoAO = $resultPuntajeMasBajoAO->fetch_assoc()) {
    $idCompetidorPuntajeMasBajoAO = $rowPuntajeMasBajoAO['id_competidor'];
}

$querySegundoAKA = "SELECT id_competidor 
                    FROM ejecucion
                    WHERE cinturon = 'AKA' AND categoria = ? AND estadocompetencia = 'activa'
                    ORDER BY puntaje_total DESC LIMIT 1 OFFSET 1";

$stmtSegundoAKA = $mysqli->prepare($querySegundoAKA);
$stmtSegundoAKA->bind_param("s", $categoria);  // Agregar el parámetro de la categoría
$stmtSegundoAKA->execute();
$resultSegundoAKA = $stmtSegundoAKA->get_result();

$idCompetidorSegundoAKA = 0; 
if ($rowSegundoAKA = $resultSegundoAKA->fetch_assoc()) {
    $idCompetidorSegundoAKA = $rowSegundoAKA['id_competidor'];
}

$queryInsertResultadoTerceroAKA = "INSERT INTO resultadocompetencia (id_competidor, id_competencia, categoria, resultado) VALUES (?, ?, ?, 'tercero')";

$stmtInsertResultadoTerceroAKA = $mysqli->prepare($queryInsertResultadoTerceroAKA);
$stmtInsertResultadoTerceroAKA->bind_param("iis", $idCompetidorSegundoAKA, $competencia, $categoria);
$stmtInsertResultadoTerceroAKA->execute();



function generarNuevoIdEjecucion($mysqli, $competidor, $competencia, $ronda, $cinturon, $tatami, $categoria) {
  
    $ronda = "final";

    
    $query = "INSERT INTO ejecucion (id_competidor, id_competencia, ronda, cinturon, tatami, categoria) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iissss", $competidor, $competencia, $ronda, $cinturon, $tatami, $categoria);

    if ($stmt->execute()) {
   
        return $mysqli->insert_id;
    } else {
       
        return 0;
    }
}

$queryPuntajeMasAltoAKA = "SELECT id_competidor
                          FROM ejecucion
                          WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AKA' AND estadocompetencia = 'activa'
                          ORDER BY puntaje_total DESC
                          LIMIT 1";
$stmtPuntajeMasAltoAKA = $mysqli->prepare($queryPuntajeMasAltoAKA);
$stmtPuntajeMasAltoAKA->bind_param("ss", $categoria, $competencia);
$stmtPuntajeMasAltoAKA->execute();
$resultPuntajeMasAltoAKA = $stmtPuntajeMasAltoAKA->get_result();


if ($rowPuntajeMasAltoAKA = $resultPuntajeMasAltoAKA->fetch_assoc()) {
    $idCompetidorGanadorAKA = $rowPuntajeMasAltoAKA['id_competidor'];
    
    $nuevoIdEjecucionFinal = generarNuevoIdEjecucion($mysqli, $idCompetidorGanadorAKA, $competencia, 'final', 'AKA', 'tatami1', $categoria);
} else {

}

$queryPuntajeMasAltoAO = "SELECT id_competidor
                          FROM ejecucion
                          WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AO' AND estadocompetencia = 'activa'
                          ORDER BY puntaje_total DESC
                          LIMIT 1";
$stmtPuntajeMasAltoAO = $mysqli->prepare($queryPuntajeMasAltoAO);
$stmtPuntajeMasAltoAO->bind_param("ss", $categoria, $competencia);
$stmtPuntajeMasAltoAO->execute();
$resultPuntajeMasAltoAO = $stmtPuntajeMasAltoAO->get_result();

if ($rowPuntajeMasAltoAO = $resultPuntajeMasAltoAO->fetch_assoc()) {
    $idCompetidorGanadorAO = $rowPuntajeMasAltoAO['id_competidor'];
    $nuevoIdEjecucionFinal = generarNuevoIdEjecucion($mysqli, $idCompetidorGanadorAO, $competencia, 'final', 'AO', 'tatami1', $categoria);
   
} else {
    
}








$nombreApellidoCompetidorGanadorAKA = obtenerNombreApellidoCompetidor($mysqli, $idCompetidorGanadorAKA); 
$nombreApellidoCompetidorGanadorAO = obtenerNombreApellidoCompetidor($mysqli, $idCompetidorGanadorAO);

$urlCompetidorGanadorAKA = "gestionar.php?id_ejecucion=$nuevoIdEjecucionFinal&nombre_ganador=$nombreApellidoCompetidorGanadorAKA";
$urlCompetidorGanadorAO = "gestionar.php?id_ejecucion=$nuevoIdEjecucionFinal&nombre_ganador=$nombreApellidoCompetidorGanadorAO";

echo '<a href="' . $urlCompetidorGanadorAKA . '" target="_blank">Competidor Ganador AKA: ' . $nombreApellidoCompetidorGanadorAKA . '</a><br><p>vs</p><br>';
echo '<a href="' . $urlCompetidorGanadorAO . '" target="_blank">Competidor Ganador AO: ' . $nombreApellidoCompetidorGanadorAO . '</a><br><br>';
echo  "<br>";

$queryTercerCompetidorAKA = "SELECT id_competidor 
                             FROM ejecucion 
                             WHERE cinturon = 'AKA' 
                             AND estadocompetencia = 'activa' 
                             AND id_competidor NOT IN (?, ?, ?) 
                             ORDER BY puntaje_total DESC 
                             LIMIT 1";

$stmtTercerCompetidorAKA = $mysqli->prepare($queryTercerCompetidorAKA);
$stmtTercerCompetidorAKA->bind_param("iii", $primerPuestoAKA, $segundoPuestoAKA, $tercerPuestoAKA);
$stmtTercerCompetidorAKA->execute();
$resultTercerCompetidorAKA = $stmtTercerCompetidorAKA->get_result();

$idTercerCompetidorAKA = 0; 
if ($rowTercerCompetidorAKA = $resultTercerCompetidorAKA->fetch_assoc()) {
    $idTercerCompetidorAKA = $rowTercerCompetidorAKA['id_competidor'];
}

$querySegundoAO = "SELECT id_competidor 
                   FROM ejecucion 
                   WHERE cinturon = 'AO' 
                   AND estadocompetencia = 'activa' 
                   ORDER BY puntaje_total DESC 
                   LIMIT 1 OFFSET 1";

$stmtSegundoAO = $mysqli->prepare($querySegundoAO);
$stmtSegundoAO->execute();
$resultSegundoAO = $stmtSegundoAO->get_result();

$idCompetidorSegundoAO = 0; 
if ($rowSegundoAO = $resultSegundoAO->fetch_assoc()) {
    $segundoPuestoAO = $rowSegundoAO['id_competidor'];
}



$nuevoIdEjecucionTercerPuestoAKA = generarNuevoIdEjecucion($mysqli, $idTercerCompetidorAKA, $competencia, 'final', 'AKA', 'tatami1', $categoria);

$nuevoIdEjecucionPuntajeMasBajoAO = generarNuevoIdEjecucion($mysqli, $segundoPuestoAO, $competencia, 'final', 'AO', 'tatami1', $categoria);


$nombreApellidoCompetidorTerceroAKA = obtenerNombreApellidoCompetidor($mysqli, $idTercerCompetidorAKA);
$nombreApellidoCompetidorSegundoAO = obtenerNombreApellidoCompetidor($mysqli, $segundoPuestoAO);

$urlCompetidorTerceroAKA = "gestionar.php?id_ejecucion=$nuevoIdEjecucionTercerPuestoAKA&nombre_ganador=$nombreApellidoCompetidorTerceroAKA";
$urlCompetidorSegundoAO = "gestionar.php?id_ejecucion=$nuevoIdEjecucionPuntajeMasBajoAO&nombre_ganador=$nombreApellidoCompetidorSegundoAO";

echo '<a href="' . $urlCompetidorTerceroAKA . '" target="_blank">Competidor Tercero AKA: ' . $nombreApellidoCompetidorTerceroAKA . '</a><br><p>vs</p><br>';
echo '<a href="' . $urlCompetidorSegundoAO . '" target="_blank">Competidor Segundo AO: ' . $nombreApellidoCompetidorSegundoAO . '</a>';
echo  "<br>";
echo  "<br>";
echo '<form action="resultado2.php" method="post" target="_blank">
    <input type="hidden" name="competencia" value="' . $competencia . '">
    <input type="hidden" name="categoria" value="' . $categoria . '">
    <input type="hidden" name="competidor1" value="' . $idCompetidorGanadorAKA . '">
    <input type="hidden" name="competidor2" value="' . $idCompetidorGanadorAO . '">
    <input type="hidden" name="competidor3" value="' . $idTercerCompetidorAKA . '">
    <input type="hidden" name="competidor4" value="' . $segundoPuestoAO . '">
    <input type="submit" name="calcular_resultado" value="Calcular Resultados" class="Calcuresultados">
</form>';


break;

    case 6:
    case 7:
    case 8:
    case 9:
    case 10:

  $queryPuntuacionesAKA = "SELECT id_competidor, puntaje_total FROM ejecucion WHERE cinturon = 'AKA' AND estadocompetencia = 'activa' ORDER BY puntaje_total DESC";
  $stmtPuntuacionesAKA = $mysqli->prepare($queryPuntuacionesAKA);
  $stmtPuntuacionesAKA->execute();
  $resultPuntuacionesAKA = $stmtPuntuacionesAKA->get_result();
  
  $puntuacionesAKA = array();
  while ($rowPuntuacionAKA = $resultPuntuacionesAKA->fetch_assoc()) {
      $puntuacionesAKA[$rowPuntuacionAKA['id_competidor']] = $rowPuntuacionAKA['puntaje_total'];
  }
  
 
  $queryPuntuacionesAO = "SELECT id_competidor, puntaje_total FROM ejecucion WHERE cinturon = 'AO' AND estadocompetencia = 'activa' ORDER BY puntaje_total ASC";
  $stmtPuntuacionesAO = $mysqli->prepare($queryPuntuacionesAO);
  $stmtPuntuacionesAO->execute();
  $resultPuntuacionesAO = $stmtPuntuacionesAO->get_result();
  
  $puntuacionesAO = array();
  while ($rowPuntuacionAO = $resultPuntuacionesAO->fetch_assoc()) {
      $puntuacionesAO[$rowPuntuacionAO['id_competidor']] = $rowPuntuacionAO['puntaje_total'];
  }
  
  $primerPuestoAKA = key($puntuacionesAKA); 
  $segundoPuestoAKA = key($puntuacionesAKA);
  next($puntuacionesAKA); 
  $tercerPuestoAKA = key($puntuacionesAKA); 
  
  $primerPuestoAO = key($puntuacionesAO); 
  $segundoPuestoAO = key($puntuacionesAO);
  next($puntuacionesAO); 
  $tercerPuestoAO = key($puntuacionesAO);

  
  function generarNuevoIdEjecucion($mysqli, $competidor, $competencia, $ronda, $cinturon, $tatami, $categoria) {
    
      $ronda = "final";
  
      
      $query = "INSERT INTO ejecucion (id_competidor, id_competencia, ronda, cinturon, tatami, categoria) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $mysqli->prepare($query);
      $stmt->bind_param("iissss", $competidor, $competencia, $ronda, $cinturon, $tatami, $categoria);
  
      if ($stmt->execute()) {
     
          return $mysqli->insert_id;
      } else {
       
          return 0; 
      }
  }

  $queryPuntajeMasAltoAKA = "SELECT id_competidor, MAX(puntaje_total) AS max_puntaje
  FROM ejecucion
  WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AKA' AND estadocompetencia = 'activa'
  GROUP BY id_competidor
  ORDER BY max_puntaje DESC
  LIMIT 1";
$stmtPuntajeMasAltoAKA = $mysqli->prepare($queryPuntajeMasAltoAKA);
$stmtPuntajeMasAltoAKA->bind_param("ss", $categoria, $competencia);
$stmtPuntajeMasAltoAKA->execute();
$resultPuntajeMasAltoAKA = $stmtPuntajeMasAltoAKA->get_result();

if ($rowPuntajeMasAltoAKA = $resultPuntajeMasAltoAKA->fetch_assoc()) {
$idCompetidorGanadorAKA = $rowPuntajeMasAltoAKA['id_competidor'];
$nuevoIdEjecucionFinal = generarNuevoIdEjecucion($mysqli, $idCompetidorGanadorAKA, $competencia, 'final', 'AKA', 'tatami1', $categoria);
} else {

}

  
  
$queryPuntajeMasAltoAO = "SELECT id_competidor, MAX(puntaje_total) AS max_puntaje
                            FROM ejecucion
                            WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AO' AND estadocompetencia = 'activa'
                            GROUP BY id_competidor
                            ORDER BY max_puntaje DESC
                            LIMIT 1";
$stmtPuntajeMasAltoAO = $mysqli->prepare($queryPuntajeMasAltoAO);
$stmtPuntajeMasAltoAO->bind_param("ss", $categoria, $competencia);
$stmtPuntajeMasAltoAO->execute();
$resultPuntajeMasAltoAO = $stmtPuntajeMasAltoAO->get_result();

if ($rowPuntajeMasAltoAO = $resultPuntajeMasAltoAO->fetch_assoc()) {
    $idCompetidorGanadorAO = $rowPuntajeMasAltoAO['id_competidor'];
    $nuevoIdEjecucionFinalAO = generarNuevoIdEjecucion($mysqli, $idCompetidorGanadorAO, $competencia, 'final', 'AO', 'tatami1', $categoria);
} else {
   
}

  
  $nombreApellidoCompetidorGanadorAKA = obtenerNombreApellidoCompetidor($mysqli, $idCompetidorGanadorAKA); 
  $nombreApellidoCompetidorGanadorAO = obtenerNombreApellidoCompetidor($mysqli, $idCompetidorGanadorAO); 
  
  $urlCompetidorGanadorAKA = "gestionar.php?id_ejecucion=$nuevoIdEjecucionFinal&nombre_ganador=$nombreApellidoCompetidorGanadorAKA";
  $urlCompetidorGanadorAO = "gestionar.php?id_ejecucion=$nuevoIdEjecucionFinalAO&nombre_ganador=$nombreApellidoCompetidorGanadorAO";
  
  echo '<a href="' . $urlCompetidorGanadorAKA . '" target="_blank">Competidor Ganador AKA: ' . $nombreApellidoCompetidorGanadorAKA . '</a><br><p>vs</p><br>';
  echo '<a href="' . $urlCompetidorGanadorAO . '" target="_blank">Competidor Ganador AO: ' . $nombreApellidoCompetidorGanadorAO . '</a><br><br>';
  echo  "<br>";

  $queryTercerCompetidorAKA = "SELECT id_competidor FROM ejecucion WHERE cinturon = 'AKA' AND id_competidor NOT IN (?, ?, ?) AND estadocompetencia = 'activa' ORDER BY puntaje_total DESC LIMIT 1";
  $stmtTercerCompetidorAKA = $mysqli->prepare($queryTercerCompetidorAKA);
  $stmtTercerCompetidorAKA->bind_param("iii", $primerPuestoAKA, $segundoPuestoAKA, $tercerPuestoAKA);
  $stmtTercerCompetidorAKA->execute();
  $resultTercerCompetidorAKA = $stmtTercerCompetidorAKA->get_result();
  
  $idTercerCompetidorAKA = 0; 
  if ($rowTercerCompetidorAKA = $resultTercerCompetidorAKA->fetch_assoc()) {
      $idTercerCompetidorAKA = $rowTercerCompetidorAKA['id_competidor'];
  }

  $queryTercerPuestoAKA = "SELECT id_competidor, MAX(puntaje_total) AS max_puntaje
    FROM ejecucion
    WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AKA' AND estadocompetencia = 'activa'
    AND id_competidor NOT IN (?, ?, ?)  
    GROUP BY id_competidor
    ORDER BY max_puntaje DESC
    LIMIT 1";
$stmtTercerPuestoAKA = $mysqli->prepare($queryTercerPuestoAKA);
$stmtTercerPuestoAKA->bind_param("ssiii", $categoria, $competencia, $idCompetidorGanadorAKA, $segundoPuestoAKA, $tercerPuestoAKA);
$stmtTercerPuestoAKA->execute();
$resultTercerPuestoAKA = $stmtTercerPuestoAKA->get_result();

if ($rowTercerPuestoAKA = $resultTercerPuestoAKA->fetch_assoc()) {
    $idTercerPuestoAKA = $rowTercerPuestoAKA['id_competidor'];
    $nuevoIdEjecucionTercerPuestoAKA = generarNuevoIdEjecucion($mysqli, $idTercerPuestoAKA, $competencia, 'final', 'AKA', 'tatami1', $categoria);
} else {
 
}

$querySegundoPuestoAO = "SELECT id_competidor, MAX(puntaje_total) AS max_puntaje
FROM ejecucion
WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AO'  AND estadocompetencia = 'activa'
AND id_competidor NOT IN (?, ?)  
GROUP BY id_competidor
ORDER BY max_puntaje DESC
LIMIT 1";
$stmtSegundoPuestoAO = $mysqli->prepare($querySegundoPuestoAO);
$stmtSegundoPuestoAO->bind_param("ssii", $categoria, $competencia, $idCompetidorGanadorAO, $primerPuestoAO);
$stmtSegundoPuestoAO->execute();
$resultSegundoPuestoAO = $stmtSegundoPuestoAO->get_result();

if ($rowSegundoPuestoAO = $resultSegundoPuestoAO->fetch_assoc()) {
    $idSegundoPuestoAO = $rowSegundoPuestoAO['id_competidor'];
    
    $nuevoIdEjecucionSegundoPuestoAO = generarNuevoIdEjecucion($mysqli, $idSegundoPuestoAO, $competencia, 'final', 'AO', 'tatami1', $categoria);
} else {
   
}


  
  
  $nombreApellidoCompetidorTerceroAKA = obtenerNombreApellidoCompetidor($mysqli, $idTercerPuestoAKA);
  $nombreApellidoCompetidorSegundoAO = obtenerNombreApellidoCompetidor($mysqli, $idSegundoPuestoAO);
  
  $urlCompetidorTerceroAKA = "gestionar.php?id_ejecucion=$nuevoIdEjecucionTercerPuestoAKA&nombre_ganador=$nombreApellidoCompetidorTerceroAKA";
  $urlCompetidorSegundoAO = "gestionar.php?id_ejecucion=$nuevoIdEjecucionSegundoPuestoAO&nombre_ganador=$nombreApellidoCompetidorSegundoAO";
  
  echo '<a href="' . $urlCompetidorTerceroAKA . '" target="_blank">Competidor Tercero AKA: ' . $nombreApellidoCompetidorTerceroAKA . '</a><br><p>vs</p><br>';
  echo '<a href="' . $urlCompetidorSegundoAO . '" target="_blank">Competidor Segundo AO: ' . $nombreApellidoCompetidorSegundoAO . '</a>';
  
  echo  "<br>";

  
$querySegundoPuestoAKA = "SELECT id_competidor, MAX(puntaje_total) AS max_puntaje
FROM ejecucion
WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AKA' AND estadocompetencia = 'activa'
AND id_competidor NOT IN (?, ?)  
GROUP BY id_competidor
ORDER BY max_puntaje DESC
LIMIT 1";
$stmtSegundoPuestoAKA = $mysqli->prepare($querySegundoPuestoAKA);
$stmtSegundoPuestoAKA->bind_param("ssii", $categoria, $competencia, $idCompetidorGanadorAKA, $primerPuestoAKA);
$stmtSegundoPuestoAKA->execute();
$resultSegundoPuestoAKA = $stmtSegundoPuestoAKA->get_result();

if ($rowSegundoPuestoAKA = $resultSegundoPuestoAKA->fetch_assoc()) {
$idSegundoPuestoAKA = $rowSegundoPuestoAKA['id_competidor'];

$nuevoIdEjecucionSegundoPuestoAKA = generarNuevoIdEjecucion($mysqli, $idSegundoPuestoAKA, $competencia, 'final', 'AKA', 'tatami1', $categoria);
} else {

}


$queryTercerPuestoAO = "SELECT id_competidor
FROM (
    SELECT id_competidor
    FROM ejecucion
    WHERE categoria = ? AND id_competencia = ? AND cinturon = 'AO'
    ORDER BY puntaje_total DESC
    LIMIT 1 OFFSET 2 
) AS tercer_lugar";
$stmtTercerPuestoAO = $mysqli->prepare($queryTercerPuestoAO);
$stmtTercerPuestoAO->bind_param("ss", $categoria, $competencia);
$stmtTercerPuestoAO->execute();
$resultTercerPuestoAO = $stmtTercerPuestoAO->get_result();
$idTercerPuestoAO = null;

if ($rowTercerPuestoAO = $resultTercerPuestoAO->fetch_assoc()) {
    $idTercerPuestoAO = $rowTercerPuestoAO['id_competidor'];
    
    $nuevoIdEjecucionTercerPuestoAO = generarNuevoIdEjecucion($mysqli, $idTercerPuestoAO, $competencia, 'final', 'AO', 'tatami1', $categoria);
} else {
    
}



$nombreApellidoCompetidorTerceroAO = obtenerNombreApellidoCompetidor($mysqli, $idTercerPuestoAO);
$nombreApellidoCompetidorSegundoAKA = obtenerNombreApellidoCompetidor($mysqli, $idSegundoPuestoAKA);

$urlCompetidorTerceroAO = "gestionar.php?id_ejecucion=$nuevoIdEjecucionTercerPuestoAO&nombre_ganador=$nombreApellidoCompetidorTerceroAO";
$urlCompetidorSegundoAKA = "gestionar.php?id_ejecucion=$nuevoIdEjecucionSegundoPuestoAKA&nombre_ganador=$nombreApellidoCompetidorSegundoAKA";

echo '<br><br> <a href="' . $urlCompetidorTerceroAO . '" target="_blank">Competidor Tercero AO: ' . $nombreApellidoCompetidorTerceroAO . '</a><br><p>vs</p><br>';
echo '<a href="' . $urlCompetidorSegundoAKA . '" target="_blank">Competidor Segundo AKA: ' . $nombreApellidoCompetidorSegundoAKA . '</a>';
echo  "<br>";
echo  "<br>";
echo  "<br>";
echo '<form action="resultado3.php" method="post" target="_blank">
    <input type="hidden" name="competencia" value="' . $competencia . '">
    <input type="hidden" name="categoria" value="' . $categoria . '">
    <input type="hidden" name="competidor1" value="' . $idCompetidorGanadorAKA . '">
    <input type="hidden" name="competidor2" value="' . $idCompetidorGanadorAO . '">
    <input type="hidden" name="competidor3" value="' . $idTercerPuestoAKA . '">
    <input type="hidden" name="competidor4" value="' . $idSegundoPuestoAO . '">
    <input type="hidden" name="competidor5" value="' . $idTercerPuestoAO . '">
    <input type="hidden" name="competidor6" value="' . $idSegundoPuestoAKA . '">
    <input type="submit" name="calcular_resultado" value="Calcular Resultados" class="Calcuresultados">
</form>';

echo  "<br>";


break;
    default:
        
        die("Número de competidores no soportado.");
        break;

    }
    echo  "<br>";
   echo '</div>';

function obtenerNumeroTotalCompetidores($mysqli, $competencia, $categoria) {
    $query = "SELECT COUNT(*) AS num_competidores FROM registra WHERE id_competencia = ? AND categoria = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ss", $competencia, $categoria);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['num_competidores'];
    } else {
        return 0;
    }
}



function obtenerNombreApellidoCompetidor($mysqli, $competidor) {
    $query = "SELECT nombre, apellido FROM competidor WHERE id_competidor = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $competidor);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row['nombre'] . ' ' . $row['apellido'];
    }
    return "";
}


$mysqli->close();



?>
</body>
</html>