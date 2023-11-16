<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Competidores</title>
</head>
<body>
    <h1>Filtrar Competidores</h1>
    
    <form method="post" action="">
        <label for="competencia">Selecciona una competencia:</label>
        <select name="competencia" id="competencia">
            <?php
        
            $conexion = mysqli_connect("localhost", "apptec", "47743966", "bd_apptec");

         
            $query = "SELECT id_competencia, nombre FROM competencia";
            $result = mysqli_query($conexion, $query);

        
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id_competencia'] . "'>" . $row['nombre'] . "</option>";
            }

       
            mysqli_close($conexion);
            ?>
        </select>
        <br>

        <label for="categoria">Selecciona una categoría:</label>
        <select name="categoria" id="categoria">
            <?php
         
            $conexion = mysqli_connect("localhost", "root", "", "bdkatascore");

            
            $query = "SELECT DISTINCT categoria FROM registra";
            $result = mysqli_query($conexion, $query);

            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['categoria'] . "'>" . $row['categoria'] . "</option>";
            }

            mysqli_close($conexion);
            ?>
        </select>
        <br>

        <input type="submit" name="submit" value="Filtrar">
    </form>

    <?php

    if (isset($_POST['submit'])) {
     
        $competenciaSeleccionada = $_POST['competencia'];
        $categoriaSeleccionada = $_POST['categoria'];
    
      

        $conexion = mysqli_connect("localhost", "root", "", "bdkatascore");
    
     
        $query = "SELECT c.nombre, c.apellido, c.escuela, c.sexo
                  FROM competidor c
                  INNER JOIN registra r ON c.id_competidor = r.id_competidor
                  WHERE r.id_competencia = $competenciaSeleccionada
                  AND r.categoria = '$categoriaSeleccionada'"; 
        $result = mysqli_query($conexion, $query);
    
    
        echo "<h2>Resultados:</h2>";
        echo "Competencia seleccionada: $competenciaSeleccionada <br>";
        echo "Categoría seleccionada: $categoriaSeleccionada <br>";
        echo "<table>";
        echo "<tr><th>Nombre</th><th>Apellido</th><th>Escuela</th><th>Sexo</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['apellido'] . "</td>";
            echo "<td>" . $row['escuela'] . "</td>";
            echo "<td>" . $row['sexo'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";

      
        echo '<form action="realizarsorteo.php" method="POST">';
        echo '<input type="hidden" name="competencia" value="' . $competenciaSeleccionada . '">';
        echo '<input type="hidden" name="categoria" value="' . $categoriaSeleccionada . '">';
        echo '<input type="submit" name="sortear" value="Realizar Sorteo para Competidores">';
        echo '</form>';

      
        mysqli_close($conexion);
    }
    ?>
</body>
</html>
