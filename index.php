<?php
$serverName = "DESKTOP-Q681RM2\SQLEXPRESS"; //Ruta a base de datos SQL Server
$connectionInfo = array(
    "Database" => "TuTenderoDB", //Mi base de datos
    "CharacterSet" => "UTF-8"
);

$conn = sqlsrv_connect($serverName, $connectionInfo);

if (!$conn) {
    echo "<h3>Error conectando a SQL Server:</h3>";
    die(print_r(sqlsrv_errors(), true));
}

// 2. Obtener filtro del formulario
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';

// 3. Preparar consulta SQL
$sql = "SELECT nombre, telefono, estado, codigo_error, detalle_error, fecha_envio FROM reportes_whatsapp";

if (!empty($estado_filtro)) {
    $sql .= " WHERE estado = ?";
    $stmt = sqlsrv_query($conn, $sql, array($estado_filtro));
} else {
    $stmt = sqlsrv_query($conn, $sql);
}

if ($stmt === false) {
    echo "<h3>Error en la consulta SQL:</h3>";
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Envíos WhatsApp</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        form { margin-bottom: 20px; }
        select, button { padding: 8px 12px; margin-right: 10px; border-radius: 4px; border: 1px solid #ccc; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #343a40; color: white; }
        .badge-sent { background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; }
        .badge-failed { background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Reporte de Estado de Envíos - Tu Tendero</h2>

    <form method="GET" action="index.php">
        <label for="estado">Filtrar por estado:</label>
        <select name="estado" id="estado">
            <option value="">-- Todos --</option>
            <option value="Enviado" <?php if($estado_filtro == 'Enviado') echo 'selected'; ?>>Enviado</option>
            <option value="Error" <?php if($estado_filtro == 'Error') echo 'selected'; ?>>Fallido</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Código Error</th>
                <th>Detalle Error</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)): ?>
                <tr>
                    <td>
                        <?php 
                            if ($row['fecha_envio'] instanceof DateTime) {
                                echo $row['fecha_envio']->format('Y-m-d H:i:s');
                            } else {
                                echo htmlspecialchars($row['fecha_envio'] ?? 'N/A');
                            }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['nombre'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['telefono'] ?? ''); ?></td>
                    <td>
                        <?php if ($row['estado'] === 'Enviado'): ?>
                            <span class="badge-sent">ENVIADO</span>
                        <?php else: ?>
                            <span class="badge-failed">FALLIDO</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['codigo_error'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($row['detalle_error'] ?? 'Sin errores'); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>