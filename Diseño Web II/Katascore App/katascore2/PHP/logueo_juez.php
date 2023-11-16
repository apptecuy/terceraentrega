<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/calificacionjuez1.css">
    <title>Numero de juez</title>
</head>
<body>
    <div class="numerodejuez">
    <form method="post" action="pagina_de_espera.php">
        <label for="id_juez" class="titulo">Selecciona tu numero de juez</label>
        <br>
        <select name="id_juez" id="id_juez" class="nro">
            <?php
            $host = 'localhost';
            $db   = 'bdkatascore';
            $user = 'root';
            $pass = '';
            $conn = new mysqli($host, $user, $pass, $db);

            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $sql = "SELECT id_juez FROM juez";
            $result = $conn->query($sql);
       
              
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['id_juez'] . '">' . $row['id_juez'] . '</option>';
                }
            }
       
            

            $conn->close();
            ?>
        </select>
        <br>
        <input type="submit" class="enviar" value="Enviar">
    </form>

    </div>
</body>
</html>
