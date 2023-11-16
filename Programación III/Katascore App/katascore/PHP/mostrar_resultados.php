<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../CSS/publico.css">
</head>
<body>
<div class="sorteopublico">
    <br>
    <img src="../IMG/LogoCuk.png" alt="" class="logo4">
    <?php
    $mysqli = new mysqli("localhost", "apptec", "47743966", "bd_apptec");

    if ($mysqli->connect_error) {
        die("Conexión a la base de datos fallida: " . $mysqli->connect_error);
    }

    $idcompetencia = $_GET['id_competencia'];
    $idcategoria = $_GET['categoria'];

    $sql_resultados = "SELECT rc.id_competidor, rc.resultado, c.nombre, c.apellido
                      FROM resultadocompetencia rc
                      INNER JOIN competidor c ON rc.id_competidor = c.id_competidor
                      WHERE rc.id_competencia = $idcompetencia AND rc.categoria = '$idcategoria'
                      ORDER BY CASE rc.resultado
                          WHEN 'primero' THEN 1
                          WHEN 'segundo' THEN 2
                          WHEN 'tercero' THEN 3
                          WHEN 'quinto' THEN 4
                          ELSE 5
                      END";

    $result_resultados = $mysqli->query($sql_resultados);
    
    if ($result_resultados) {
        echo "<h3 class=resulcomp>Resultados para la competencia: $idcompetencia <br>Categoría: $idcategoria </h3><br>";
        echo "<ul>";



        while ($row = $result_resultados->fetch_assoc()) {
            echo "<p class='resultado'>Resultado: " . $row['resultado'] ."</p>";
          

            echo "<li>Competidor: " . $row['id_competidor'] . " - Nombre: " . $row['nombre'] . " - Apellido: " . $row['apellido'] .  "</li>";
           echo "<br>";
        }


        echo "</ul>";
    } else {
        echo "Error en la consulta: " . $mysqli->error;
    }
    $mysqli->close();
    ?>
    <br>
    <br>
    <form action="mostrar_competidores_puntaje.php">
    <input type="submit" value="Volver" class="boton-competidor4">
</form>
    <p></p>
    <br>
</div>
</body>
</html>
