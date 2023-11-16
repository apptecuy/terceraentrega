<?php
include('conectar2.php');

$con = conectar();


if (isset($_GET['id_competidor']) && !empty($_GET['id_competidor'])) {
    $filtro_id = $_GET['id_competidor'];
    $sql = "SELECT id_competidor, nombre, apellido, escuela, sexo, fecha_nac FROM competidor WHERE id_competidor = $filtro_id";
} else {
    $sql = "SELECT id_competidor, nombre, apellido, escuela, sexo, fecha_nac FROM competidor";
}

$resultado = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Competidores</title>
    <link rel="stylesheet" href="../CSS/competenciacompetidor.css">
</head>
<body>
    <div class="vincularcompetencia">
        <h1 class="titulovincular">Vincular competidores a competencias</h1>
        <form action="../HTML/competicion.html">
            <input type="submit" class="volver3" value="Volver">
        </form>

        <form method="GET" action="">
            <label for="filtro_id">Filtrar por ID de Competidor:</label>
            <input type="text" name="id_competidor" id="filtro_id">
            <input type="submit" value="Filtrar">
        </form>

        <table class="vincularcompetencia1" cellspacing="45px">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Escuela</th>
                <th>Sexo</th>
                <th>Fecha de Nacimiento</th>
                <th>Acción</th>
            </tr>

            <?php
            while ($fila = mysqli_fetch_assoc($resultado)) {
            ?>
                <tr>
                    <td><?php echo $fila['id_competidor']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['escuela']; ?></td>
                    <td><?php echo $fila['sexo']; ?></td>
                    <td><?php echo $fila['fecha_nac']; ?></td>
                    <td><a class="link" href='vincular_competidor.php?id_competidor=<?php echo $fila['id_competidor']; ?>'>Vincular</a></td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
</body>
</html>
