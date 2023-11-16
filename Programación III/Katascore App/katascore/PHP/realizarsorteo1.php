<!DOCTYPE html>
<html>
<head>
    <title>Sorteo</title>
    <link rel="stylesheet" type="text/css" href="../CSS/sorteo.css">
    <script>
function actualizarEstadoCompetencia() {
    var formData = new FormData(document.getElementById('estadoForm'));
    var mensajeConfirmacion = document.getElementById('mensajeConfirmacion');

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'actualizar_estado.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
         
            mensajeConfirmacion.innerHTML = 'Estado actualizado con éxito.';
        }
    };
    xhr.send(formData);
}
</script>
<script>
function confirmarVolver() {
    var confirmacion = confirm("Al regresar, el sorteo se finalizará. ¿Estás seguro de que deseas volver?");
    if (confirmacion) {
     
        window.location.href = "realizarsorteo6.php";
    }
}
</script>



</head>
<body>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


function obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli) {
    $competidores = [];

  
    $query = "SELECT id_competidor FROM registra WHERE id_competencia = ? AND categoria = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("is", $competencia, $categoria);
    $stmt->execute();
    $stmt->bind_result($id_competidor);

    while ($stmt->fetch()) {
        $competidores[] = ['id_competidor' => $id_competidor];
    }

    $stmt->close();

   
    if (count($competidores) < $totalCompetidores) {
        die("No hay suficientes competidores para realizar el sorteo.");
    }

    return $competidores;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

    if ($mysqli->connect_error) {
        die("La conexión a la base de datos falló: " . $mysqli->connect_error);
    }

   
    $competencia = $_POST['id_competencia'];
    $categoria = $_POST['categoria'];
    $tatami = $_POST['tatami'];
    $allowedTatamiValues = ["tatami1", "tatami2"];
    
    if (!in_array($tatami, $allowedTatamiValues)) {
        die("El valor de 'tatami' no es válido.");
    }

    
    $query = "SELECT COUNT(*) as total FROM registra WHERE id_competencia = $competencia AND categoria = '$categoria'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $totalCompetidores = $row['total'];

    function competenciaCategoriaExistente($idCompetencia, $categoria, $conexion) {
        $consulta = "SELECT COUNT(*) as count FROM ejecucion WHERE id_competencia = $idCompetencia AND categoria = '$categoria'";
        $resultado = $conexion->query($consulta);
        
        if ($resultado) {
            $fila = $resultado->fetch_assoc();
            return $fila['count'] > 0;
        }
        
        return false;
    }
    function obtenerCompetidoresPorCategoriaCompetencia($categoria, $competencia, $mysqli) {
        $query = "SELECT e.id_ejecucion, e.cinturon, c.id_competidor, c.nombre, c.apellido FROM ejecucion e
                  INNER JOIN competidor c ON e.id_competidor = c.id_competidor
                  WHERE e.categoria = '$categoria' AND e.id_competencia = $competencia";
    
        $result = $mysqli->query($query);
        
        if ($result) {
            $competidores = [];
            
            while ($row = $result->fetch_assoc()) {
                $competidores[] = $row;
            }
            
            return $competidores;
        } else {
            die("Error en la consulta: " . $mysqli->error);
        }
    }
    

function obtenerDatosCompetidor($id_competidor, $mysqli) {
    $competidorData = [];

    $query = "SELECT nombre, apellido, escuela FROM competidor WHERE id_competidor = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id_competidor);
    $stmt->execute();
    $stmt->bind_result($nombre, $apellido, $escuela);

    
    if ($stmt->fetch()) {
        $competidorData['nombre'] = $nombre;
        $competidorData['apellido'] = $apellido;
        $competidorData['escuela'] = $escuela;
    }

    $stmt->close();

    return $competidorData;
}

function insertarEjecucion($id_competidor, $id_competencia, $categoria, $ronda, $cinturon, $mysqli, $tatami) {
  
    $query = "INSERT INTO ejecucion (id_competidor, id_competencia, categoria, ronda, cinturon, tatami) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iissss", $id_competidor, $id_competencia, $categoria, $ronda, $cinturon, $tatami);
    $stmt->execute();
    $stmt->close();
    $id_ejecucion = mysqli_insert_id($mysqli);
}
if (competenciaCategoriaExistente($competencia, $categoria, $mysqli)) {
    echo '<div class="sorteo">';
    echo "La combinación de competencia y categoría ya existe en ejecucion.";
    $query = "SELECT id_ejecucion, cinturon FROM ejecucion WHERE id_competencia = $competencia AND categoria = '$categoria'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        echo '<h2>Competencia ya creada</h2>';

        $competidoresAKA = [];
        $competidoresAO = [];

     
        while ($row = $result->fetch_assoc()) {
            $id_ejecucion = $row['id_ejecucion'];
            $cinturon = $row['cinturon'];

     
            $queryCompetidor = "SELECT id_competidor, nombre, apellido FROM competidor WHERE id_competidor IN (SELECT id_competidor FROM ejecucion WHERE id_ejecucion = $id_ejecucion)";
            $resultCompetidor = $mysqli->query($queryCompetidor);

            while ($rowCompetidor = $resultCompetidor->fetch_assoc()) {
                $nombre = $rowCompetidor['nombre'];
                $apellido = $rowCompetidor['apellido'];
                $id_competidor = $rowCompetidor['id_competidor'];

                if ($cinturon === "AKA") {
                
                    $competidoresAKA[$id_ejecucion][] = ['nombre' => $nombre, 'apellido' => $apellido, 'id_competidor' => $id_competidor];
                } elseif ($cinturon === "AO") {
                    
                    $competidoresAO[$id_ejecucion][] = ['nombre' => $nombre, 'apellido' => $apellido, 'id_competidor' => $id_competidor];
                }
            }
        }

       
        echo '<h2 class="AKA">Competidores AKA:</h2>';
        foreach ($competidoresAKA as $id_ejecucion => $competidores) {
            foreach ($competidores as $competidor) {
                $nombre = $competidor['nombre'];
                $apellido = $competidor['apellido'];
                $id_competidor = $competidor['id_competidor'];

          
                echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $nombre . ' ' . $apellido . '</a><br>';
            }
        }

        
        echo '<h2 class="AO">Competidores AO:</h2>';
        foreach ($competidoresAO as $id_ejecucion => $competidores) {
            foreach ($competidores as $competidor) {
                $nombre = $competidor['nombre'];
                $apellido = $competidor['apellido'];
                $id_competidor = $competidor['id_competidor'];

                echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $nombre . ' ' . $apellido . '</a><br>';
            }
        }
        echo '</div>';
    }
} else {
    realizarSorteo($totalCompetidores, $competencia, $categoria, $tatami, $mysqli);
}

$mysqli->close();


}



function realizarSorteo($totalCompetidores, $competencia, $categoria, $tatami, $mysqli) {
    $ronda = "";
    $cinturon = "";
    $id_ejecucion_por_competidor = [];
 
    switch ($totalCompetidores) {

         
        case 2:
            $cinturon = "AKA";
            $ronda = "Semifinal";
            echo '<div class="sorteo">';
            echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
            echo "<br>";
            echo '<p class="titulos" >Categoria: </p> ';
            echo $categoria;
            echo "<br>";
            echo '<p class="titulos" >Competencia: </p>';
           echo $competencia;
           echo "<br>";
           
         
            $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
           
            
            shuffle($competidores);
            echo '<h2 class="AKA">Competidores AKA:</h2>';
            
            foreach ($competidores as $competidor) {
                $id_competidor = $competidor['id_competidor'];
        
              
                $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
        
        
                
                insertarEjecucion($id_competidor, $competencia, $categoria, $ronda, $cinturon, $mysqli, $tatami);
                $id_ejecucion = mysqli_insert_id($mysqli);
                $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

               
            }
        
            break;
        
            case 3:
                $cinturon = "AKA"; 
                $ronda = "Semifinal";
                echo '<div class="sorteo">';
            
                echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
                echo "<br>";
                echo '<p class="titulos" >Categoria: </p> ';
                echo $categoria;
                echo "<br>";
                echo '<p class="titulos" >Competencia: </p>';
               echo $competencia;
               echo "<br>";
               
           
                $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
            
                
                shuffle($competidores);
                echo '<h2 class="AKA">Competidores AKA:</h2>';
               
                foreach ($competidores as $competidor) {
                    $id_competidor = $competidor['id_competidor'];
            
                  
                    $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
    
                    
                    insertarEjecucion($id_competidor, $competencia, $categoria, $ronda, $cinturon, $mysqli, $tatami);
                    $id_ejecucion = mysqli_insert_id($mysqli);
                    $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                    echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

              
            }
            echo '</div>';
                break;
            
            

        case 4:
            $cinturon = "AKA"; 
            $ronda = "Semifinal";
            echo '<div class="sorteo">';
            echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
            echo "<br>";
            echo '<p class="titulos" >Categoria: </p> ';
            echo $categoria;
            echo "<br>";
            echo '<p class="titulos" >Competencia: </p>';
           echo $competencia;
           echo "<br>";
           
            $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
            
            shuffle($competidores);
            
            $competidoresAKA = array_slice($competidores, 0, 2);
            $competidoresAO = array_slice($competidores, 2, 2);
            
          
            echo '<h2 class="AKA">Competidores AKA:</h2>';
            foreach ($competidoresAKA as $competidor) {
                $id_competidor = $competidor['id_competidor'];
                $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                
                insertarEjecucion($id_competidor, $competencia, $categoria, $ronda, 'AKA', $mysqli, $tatami);
                $id_ejecucion = mysqli_insert_id($mysqli);
                $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

            }
            
           
            echo "<h2>Competidores AO:</h2>";
            foreach ($competidoresAO as $competidor) {
                $id_competidor = $competidor['id_competidor'];
                $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                
                
                
                insertarEjecucion($id_competidor, $competencia, $categoria, $ronda, 'AO', $mysqli, $tatami);
                $id_ejecucion = mysqli_insert_id($mysqli);
                $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';
            }
            echo '</div>';
        
          
            break;
            case 5:
                
                $ronda = "Semifinal";
                $grupos = [
                    "AKA" => ["count" => 3, "competidores" => []],
                    "AO" => ["count" => 2, "competidores" => []]
                ];
                echo '<div class="sorteo">';
                echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
                echo "<br>";
                echo '<p class="titulos" >Categoria: </p> ';
                echo $categoria;
                echo "<br>";
                echo '<p class="titulos" >Competencia: </p>';
               echo $competencia;
               echo "<br>";
               
                $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
                shuffle($competidores);
             
                foreach ($competidores as $competidor) {
                    $id_competidor = $competidor['id_competidor'];
                    $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                    $escuela = $competidorData['escuela'];
            
                    if ($grupos["AKA"]["count"] > 0 && count($grupos["AKA"]["competidores"]) < 3) {
                    
                        $grupos["AKA"]["competidores"][] = $competidor;
                        $grupos["AKA"]["count"]--;
            
                     
                        insertarEjecucion($id_competidor, $competencia, $categoria, $ronda, "AKA", $mysqli, $tatami);
                        $id_ejecucion = mysqli_insert_id($mysqli);
                        $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                    } elseif ($grupos["AO"]["count"] > 0) {
                    
                        $grupos["AO"]["competidores"][] = $competidor;
                        $grupos["AO"]["count"]--;
            
                        
                        insertarEjecucion($id_competidor, $competencia, $categoria, $ronda, "AO", $mysqli, $tatami);
                        $id_ejecucion = mysqli_insert_id($mysqli);
                        $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                    }
                }
            
             
                echo '<h2 class="AKA">Competidores AKA:</h2>';
                foreach ($grupos["AKA"]["competidores"] as $competidor) {
                    $id_competidor = $competidor['id_competidor'];
                    $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                    $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                    echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                }
            
              
                echo "<h2>Competidores AO:</h2>";
                foreach ($grupos["AO"]["competidores"] as $competidor) {
                    $id_competidor = $competidor['id_competidor'];
                    $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                    $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                    echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                }
                echo '</div>';
                break;

                case 6:
                   
                    $rondaInicial = "Semifinal";
                    $grupos = [
                        "AKA" => ["count" => 3, "competidores" => []],
                        "AO" => ["count" => 3, "competidores" => []]
                    ];
                    echo '<div class="sorteo">';
                    echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
                    echo "<br>";
                    echo '<p class="titulos" >Categoria: </p> ';
                    echo $categoria;
                    echo "<br>";
                    echo '<p class="titulos" >Competencia: </p>';
                   echo $competencia;
                   echo "<br>";
                   
                    
                    $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
                    shuffle($competidores);
                    
                    foreach ($competidores as $competidor) {
                        $id_competidor = $competidor['id_competidor'];
                        $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                        $escuela = $competidorData['escuela'];
                
                        if ($grupos["AKA"]["count"] > 0) {
                            
                            $grupos["AKA"]["competidores"][] = $competidor;
                            $grupos["AKA"]["count"]--;
                
                            insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AKA", $mysqli, $tatami);
                            $id_ejecucion = mysqli_insert_id($mysqli);
                            $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                        } elseif ($grupos["AO"]["count"] > 0) {
                           
                            $grupos["AO"]["competidores"][] = $competidor;
                            $grupos["AO"]["count"]--;
                
                            
                            insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AO", $mysqli, $tatami);
                            $id_ejecucion = mysqli_insert_id($mysqli);
                            $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                        }
                    }
                
                    
                    echo '<h2 class="AKA">Competidores AKA:</h2>';
                    foreach ($grupos["AKA"]["competidores"] as $competidor) {
                        $id_competidor = $competidor['id_competidor'];
                        $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                        $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                        echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                    }
                
                    echo "<h2>Grupo AO:</h2>";
                    foreach ($grupos["AO"]["competidores"] as $competidor) {
                        $id_competidor = $competidor['id_competidor'];
                        $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                        $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                        echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                    }
                    echo '</div>';
                    break;
                    case 7:
                        
                        $rondaInicial = "Semifinal";
                        $grupos = [
                            "AKA" => ["count" => 4, "competidores" => []],
                            "AO" => ["count" => 3, "competidores" => []]
                        ];
                        echo '<div class="sorteo">';
                        echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
                        echo "<br>";
                        echo '<p class="titulos" >Categoria: </p> ';
                        echo $categoria;
                        echo "<br>";
                        echo '<p class="titulos" >Competencia: </p>';
                       echo $competencia;
                       echo "<br>";
                       
                        $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
                        shuffle($competidores);
                        
                        foreach ($competidores as $competidor) {
                            $id_competidor = $competidor['id_competidor'];
                            $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                            $escuela = $competidorData['escuela'];
                    
                            if ($grupos["AKA"]["count"] > 0) {
                              
                                $grupos["AKA"]["competidores"][] = $competidor;
                                $grupos["AKA"]["count"]--;
                    
                              
                                insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AKA", $mysqli, $tatami);
                                $id_ejecucion = mysqli_insert_id($mysqli);
                                $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                        
                            } elseif ($grupos["AO"]["count"] > 0) {
                               
                                $grupos["AO"]["competidores"][] = $competidor;
                                $grupos["AO"]["count"]--;
                    
                                
                                insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AO", $mysqli, $tatami);
                                $id_ejecucion = mysqli_insert_id($mysqli);
                                $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                            }
                        }
                    
                        
                        echo '<h2 class="AKA">Competidores AKA:</h2>';
                        foreach ($grupos["AKA"]["competidores"] as $competidor) {
                            $id_competidor = $competidor['id_competidor'];
                            $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                            $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                            echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                        }
                    
                        echo "<h2>Grupo AO:</h2>";
                        foreach ($grupos["AO"]["competidores"] as $competidor) {
                            $id_competidor = $competidor['id_competidor'];
                            $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                            $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                            echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';
                        }
                        echo '</div>';
                        break;

                        case 8:
                            
                            $rondaInicial = "Semifinal";
                            $grupos = [
                                "AKA" => ["count" => 4, "competidores" => []],
                                "AO" => ["count" => 4, "competidores" => []]
                            ];
                            echo '<div class="sorteo">';
                            echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
                    echo "<br>";
                    echo '<p class="titulos" >Categoria: </p> ';
                    echo $categoria;
                    echo "<br>";
                    echo '<p class="titulos" >Competencia: </p>';
                   echo $competencia;
                   echo "<br>";
                   
                           
                            $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
                            shuffle($competidores);
                           
                            foreach ($competidores as $competidor) {
                                $id_competidor = $competidor['id_competidor'];
                                $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                $escuela = $competidorData['escuela'];
                        
                                if ($grupos["AKA"]["count"] > 0) {
                                  
                                    $grupos["AKA"]["competidores"][] = $competidor;
                                    $grupos["AKA"]["count"]--;
                        
                                  
                                    insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AKA", $mysqli, $tatami);
                                    $id_ejecucion = mysqli_insert_id($mysqli);
                                    $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                                } elseif ($grupos["AO"]["count"] > 0) {
                                    
                                    $grupos["AO"]["competidores"][] = $competidor;
                                    $grupos["AO"]["count"]--;
                        
                                    
                                    insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AO", $mysqli, $tatami);
                                    $id_ejecucion = mysqli_insert_id($mysqli);
                                    $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                                }
                            }
                        
                           
                            echo '<h2 class="AKA">Competidores AKA:</h2>';
                            foreach ($grupos["AKA"]["competidores"] as $competidor) {
                                $id_competidor = $competidor['id_competidor'];
                                $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                                echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                            
                            }
                        
                            echo "<h2>Grupo AO:</h2>";
                            foreach ($grupos["AO"]["competidores"] as $competidor) {
                                $id_competidor = $competidor['id_competidor'];
                                $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                                echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';
                            }
                            echo '</div>';
                            break;

                            case 9:
                                
                                $rondaInicial = "Semifinal";
                                $grupos = [
                                    "AKA" => ["count" => 5, "competidores" => []],
                                    "AO" => ["count" => 4, "competidores" => []]
                                ];
                                echo '<div class="sorteo">';
                                echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
                                echo "<br>";
                                echo '<p class="titulos" >Categoria: </p> ';
                                echo $categoria;
                                echo "<br>";
                                echo '<p class="titulos" >Competencia: </p>';
                               echo $competencia;
                               echo "<br>";
                               
                            
                                $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
                                shuffle($competidores);
                                
                                foreach ($competidores as $competidor) {
                                    $id_competidor = $competidor['id_competidor'];
                                    $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                    $escuela = $competidorData['escuela'];
                            
                                    if ($grupos["AKA"]["count"] > 0) {
                                        
                                        $grupos["AKA"]["competidores"][] = $competidor;
                                        $grupos["AKA"]["count"]--;
                            
                                        
                                        insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AKA", $mysqli, $tatami);
                                        $id_ejecucion = mysqli_insert_id($mysqli);
                                        $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                                    } elseif ($grupos["AO"]["count"] > 0) {
                                       
                                        $grupos["AO"]["competidores"][] = $competidor;
                                        $grupos["AO"]["count"]--;
                            
                                        
                                        insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AO", $mysqli, $tatami);
                                        $id_ejecucion = mysqli_insert_id($mysqli);
                                        $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                                    }
                                }
                            
                                echo '<h2 class="AKA">Competidores AKA:</h2>';
                                foreach ($grupos["AKA"]["competidores"] as $competidor) {
                                    $id_competidor = $competidor['id_competidor'];
                                    $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                    $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                                    echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                                }
                            
                                echo "<h2>Grupo AO:</h2>";
                                foreach ($grupos["AO"]["competidores"] as $competidor) {
                                    $id_competidor = $competidor['id_competidor'];
                                    $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                    $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                                    echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                                }
                                echo '</div>';
                                break;
                                case 10:
                                   
                                    $rondaInicial = "Semifinal";
                                    $grupos = [
                                        "AKA" => ["count" => 5, "competidores" => []],
                                        "AO" => ["count" => 5, "competidores" => []]
                                    ];
                                    echo '<div class="sorteo">';
                                    echo '<img src="../IMG/LogoCuk.png" class="logosorteo" alt="" height="120px" width="120px">';
            
                                    echo "<br>";
                                    echo '<p class="titulos" >Categoria: </p> ';
                                    echo $categoria;
                                    echo "<br>";
                                    echo '<p class="titulos" >Competencia: </p>';
                                   echo $competencia;
                                   echo "<br>";
                                   
                                    $competidores = obtenerCompetidores($competencia, $categoria, $totalCompetidores, $mysqli);
                                    shuffle($competidores);
                                    
                                    foreach ($competidores as $competidor) {
                                        $id_competidor = $competidor['id_competidor'];
                                        $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                        $escuela = $competidorData['escuela'];
                                
                                        if ($grupos["AKA"]["count"] > 0) {
                                        
                                            $grupos["AKA"]["competidores"][] = $competidor;
                                            $grupos["AKA"]["count"]--;
                                
                                        
                                            insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AKA", $mysqli, $tatami);
                                            $id_ejecucion = mysqli_insert_id($mysqli);
                                            $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                                        } elseif ($grupos["AO"]["count"] > 0) {
                                           
                                            $grupos["AO"]["competidores"][] = $competidor;
                                            $grupos["AO"]["count"]--;
                                
                                           
                                            insertarEjecucion($id_competidor, $competencia, $categoria, $rondaInicial, "AO", $mysqli, $tatami);
                                            $id_ejecucion = mysqli_insert_id($mysqli);
                                            $id_ejecucion_por_competidor[$id_competidor] = $id_ejecucion;
                                        }
                                    }
                                
                                   
                                    echo '<h2 class="AKA">Competidores AKA:</h2>';
                                    foreach ($grupos["AKA"]["competidores"] as $competidor) {
                                        $id_competidor = $competidor['id_competidor'];
                                        $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                        $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                                        echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                                    }
                                
                                    echo "<h2>Grupo AO:</h2>";
                                    foreach ($grupos["AO"]["competidores"] as $competidor) {
                                        $id_competidor = $competidor['id_competidor'];
                                        $competidorData = obtenerDatosCompetidor($id_competidor, $mysqli);
                                        $id_ejecucion = $id_ejecucion_por_competidor[$id_competidor];
                                        echo '<a href="gestionar.php?id_competidor=' . $id_competidor . '&id_ejecucion=' . $id_ejecucion . '" target="_blank">' . $competidorData['nombre'] . ' ' . $competidorData['apellido'] . ' (' . $competidorData['escuela'] . ')</a><br>';

                                    }
                                    echo '</div>';
                                    break;
                                    

                         
                                 
                    
                
                                    default:
                                    die("Número de competidores no soportado.");
                                    
                                    
        
    }
    
}



function competidorEnEjecucion($id_competidor, $id_competencia, $categoria, $ronda, $cinturon, $mysqli, $tatami) {
    $query = "SELECT COUNT(*) as total FROM ejecucion WHERE id_competidor = ? AND id_competencia = ? AND categoria = ? AND ronda = ? AND cinturon = ? AND tatami = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("iissss", $id_competidor, $id_competencia, $categoria, $ronda, $cinturon, $tatami);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    $stmt->close();
    
    return $total > 0;
}

session_start();
$_SESSION['competencia'] = $competencia;
$_SESSION['categoria'] = $categoria;
echo "<br>";
echo "<br>";
echo "<br>";
echo '<a href="siguiente_ronda.php?competencia=' . $competencia . '&categoria=' . $categoria . '&otro_parametro=valor" target="_blank" class="siguienteRonda">Siguiente ronda</a>';

?>

<div class ="estadocompetencia">
<form id="estadoForm" action="actualizar_estado.php" method="post">
    <label for="estadoCompetencia"class="tituloestado">Estado de la competencia:</label> <br>
    <select name="estadoCompetencia" class="activaono" id="estadoCompetencia">
        <option value="activa">Activa</option>
        <option value="finalizada">Finalizada</option>
    </select>
    
    <input type="hidden" name="competencia" value="<?php echo $competencia; ?>">
    <input type="hidden" name="categoria" value="<?php echo $categoria; ?>">
    
    <button type="button" class="actualizarestado" onclick="actualizarEstadoCompetencia()">Actualizar Estado</button>
</form>

<br>
<input type="submit" value="Volver" class="volversorteo" onclick="confirmarVolver()">

</div>


<div id="mensajeConfirmacion" class="mensajeConfirmacion"></div>
</body>
</html>

