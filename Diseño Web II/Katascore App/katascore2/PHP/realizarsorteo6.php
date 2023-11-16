<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="../CSS/competenciacompetidor.css">
    <title>Formulario de Sorteo</title>
</head>
<body>
    <div class="realizarsorteo">
        <img src="../IMG/LogoCuk.png"  class="logorealizar" height="140" width="140" alt="">
        <br>
        
        <form action="../HTML/gestioncompetencia.html">
    <input type="submit" class="volversorteo" value ="Volver">
    </form>
   <br>
   <br>
        <h1 class="realisort" >Realizar sorteo </h1>

    <form action="realizarsorteo.php" method="post">

     <div>
     <div class="eleccioncompetencia">
        <label for="id_competencia">Competencia:</label>
        <select id="id_competencia" name="id_competencia">
            <?php
           
            $mysqli = new mysqli("localhost", "root", "", "bdkatascore");

            if ($mysqli->connect_error) {
                die("La conexión a la base de datos falló: " . $mysqli->connect_error);
            }

            $query = "SELECT DISTINCT id_competencia FROM registra";
            $result = $mysqli->query($query);

            
            if ($result->num_rows > 0) {
                
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['id_competencia']) . '">' . htmlspecialchars($row['id_competencia']) . '</option>';
                }
            } else {
                echo '<option value="">No hay competencias disponibles</option>';
            }


            $mysqli->close();
            ?>
        </select>
        </div>
        <br>
        <div class="eleccioncategoria">
        <label for="categoria">Categoría:</label>
        <select id="categoria" name="categoria">
            <?php
            
            $mysqli = new mysqli("localhost", "root", "", "bdkatascore");

            if ($mysqli->connect_error) {
                die("La conexión a la base de datos falló: " . $mysqli->connect_error);
            }

            $query = "SELECT DISTINCT categoria FROM registra";
            $result = $mysqli->query($query);

            
            if ($result->num_rows > 0) {
               
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($row['categoria']) . '">' . htmlspecialchars($row['categoria']) . '</option>';
                }
            } else {
                echo '<option value="">No hay categorías disponibles</option>';
            }

            $mysqli->close();
            ?>
        </select>
        </div>
<br>
        <div class ="elecciontatami"> 
        <label for="tatami">Tatami:</label>
        <select id="tatami" name="tatami">
            <option value="tatami1">Tatami 1</option>
            <option value="tatami2">Tatami 2</option>
        </select>
        </div>
 <br>   
        <input type="submit" class="realizarsorteo1" value="Realizar Sorteo">
    </form>
    <br>
 
    </div>

   
</body>
</html>

