<!DOCTYPE html>
<html>
<head>
    <title>Seleccionar Competencias</title>
    <link rel="stylesheet" href="../CSS/competenciacompetidor.css">
</head>
<body>
    <div class="filtradocompetidores">
      <h1 class="seleccionarcompetencia">Seleccionar Competencias</h1>
    
     <form method="POST" action="procesar_filtrado.php">
        <label for="competencia" class="competencia1">Selecciona una competencia:</label>
        <select name="competencia" class="competencia2" id="competencia">
           
            <?php
            
            $conexion = new mysqli("localhost", "root", "emi093908463", "bdkatascore");

           
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }

            
            $sql = "SELECT id_competencia, nombre FROM competencia";
            $result = $conexion->query($sql);

           
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['id_competencia'] . "'>" . $row['nombre'] . "</option>";
            }

            $conexion->close();
            ?>
        </select>
        
        <br>

        <label for="categoria" class="categoria1">Filtrar por categoría:</label>
        <select name="categoria" class="categoria2" id="categoria">
            <option value="mayores">Mayores de edad</option>
            <option value="16-17">16 a 17 años</option>
            <option value="14-15">14 a 15 años</option>
            <option value="12-13">12 a 13 años</option>
            <option value="menores">Menores de 12 años</option>
        </select>
        
        <br>

        <label for="sexo" class="sexo1">Filtrar por sexo:</label>
        <select name="sexo" class="sexo2" id="sexo">
            <option value="F">Femenino</option>
            <option value="M">Masculino</option>
        </select>
        
        <br>

        <input type="submit" class="buscar" value="Buscar">
     </form>
     <form action="../HTML/competicion.html">
    <input type="submit" class="volver5" value="Volver">
    </form>

    </div>
</body>
</html>
