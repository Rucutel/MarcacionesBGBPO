<?php
include 'db_connect.php';
date_default_timezone_set('America/Lima');  // Cambia "America/Lima" por tu zona horaria si es diferente.
// Obtener datos enviados desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

$dni = $data['dni'];
$tipo_marcacion = $data['tipo_marcacion'];
$fecha = date('Y-m-d');
$hora = date('H:i:s');

// Verificar si ya existe una marcación para la fecha y el DNI
$query = "SELECT * FROM marcaciones WHERE dni = '$dni' AND fecha = '$fecha'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Actualizar la fila existente
    $updateQuery = "";

    if ($tipo_marcacion == 'Ingreso') {
        $updateQuery = "UPDATE marcaciones SET ingreso = '$hora' WHERE dni = '$dni' AND fecha = '$fecha'";
    } elseif ($tipo_marcacion == 'Inicio Break') {
        $updateQuery = "UPDATE marcaciones SET inicio_break = '$hora' WHERE dni = '$dni' AND fecha = '$fecha'";
    } elseif ($tipo_marcacion == 'Retorno Break') {
        $updateQuery = "UPDATE marcaciones SET retorno_break = '$hora' WHERE dni = '$dni' AND fecha = '$fecha'";
    } elseif ($tipo_marcacion == 'Salida') {
        $updateQuery = "UPDATE marcaciones SET salida = '$hora' WHERE dni = '$dni' AND fecha = '$fecha'";
    }

    if ($conn->query($updateQuery) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Marcación actualizada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar marcación']);
    }
} else {
    // Insertar nueva fila
    $insertQuery = "INSERT INTO marcaciones (dni, fecha, $tipo_marcacion) VALUES ('$dni', '$fecha', '$hora')";
    
    if ($conn->query($insertQuery) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Marcación guardada correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar marcación']);
    }
}

$conn->close();
?>