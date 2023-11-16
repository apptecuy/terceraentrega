<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/sorteo.css">
    <title>Entorno de espera</title>
</head>
<body>
    <?php
    
    if (isset($_POST['id_juez'])) {
        $id_juez = $_POST['id_juez'];
    } else {
        echo "No se ha proporcionado un número de juez.";
    }
    ?>

    <div class="espera">
        <h1 class="h2espera">Bienvenidos a la competencia</h1>
        <img src="../IMG/katarojo1.png" class="kimonorojo" alt="" height="500px">
        <img src="../IMG/LogoCuk.png" class="logoespera" alt="" height="150px" width="150px">
        <img src="../img/kataazul3.png" class="kimonoazul" alt="" height="500px">
        <form method="POST" action="juez.php">
            <input type="hidden" name="id_juez" value="<?php echo $id_juez; ?>">
            <input type="submit" class="boton-calificar" value="Esperando confirmación del administrador">
        </form>
    </div>
</body>
</html>
