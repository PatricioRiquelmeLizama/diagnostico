<?php
    include 'conexion.php';

    header('Content-Type: application/json; charset=utf-8');

    function obtenerBodegas($conn) {
        $sql = "SELECT ID, bodega FROM bodegas";
        $result = $conn->query($sql);
        $datos = [];

        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }

        return $datos;
    }

    function obtenerMonedas($conn) {
        $sql = "SELECT ID_moneda, moneda FROM monedas";
        $resultado = $conn->query($sql);
        $monedas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $monedas[] = $fila;
        }

        return $monedas;
    }


    function obtenerSucursalId($conn, $idBodega) {
        $sql = "SELECT ID_sucursal, sucursal FROM sucursales WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idBodega);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $sucursales = [];

        while ($fila = $resultado->fetch_assoc()) {
            $sucursales[] = $fila;
        }

        $stmt->close();
        return $sucursales;
    }

    function verificarCodigo($conn, $codigo) {

        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM productos WHERE codigo = ?");
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        echo json_encode(["existe" => $result['total'] > 0]);
        exit;
    }

    function insertarProducto($conn, $data) {

        $materiales = $data['materialesBinarios'] ?? [0,0,0,0,0];
        $tiene_plastico = isset($materiales[0]) ? (int)$materiales[0] : 0;
        $tiene_metal    = isset($materiales[1]) ? (int)$materiales[1] : 0;
        $tiene_madera   = isset($materiales[2]) ? (int)$materiales[2] : 0;
        $tiene_vidrio   = isset($materiales[3]) ? (int)$materiales[3] : 0;
        $tiene_textil   = isset($materiales[4]) ? (int)$materiales[4] : 0;

        $sql = "INSERT INTO productos (
                    codigo, nombre, id_bodega, id_sucursal, id_moneda, precio,
                    tiene_plastico, tiene_metal, tiene_madera, tiene_vidrio, tiene_textil, descripcion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            return ["success" => false, "error" => "Error al preparar consulta: " . $conn->error];
        }

        $stmt->bind_param(
            "ssiiiiiiiiis",
            $data['codigo'],     
            $data['nombre'],      
            $data['idBodega'],    
            $data['idSucursal'],  
            $data['idMoneda'],    
            $data['precio'],      
            $tiene_plastico,      
            $tiene_metal,         
            $tiene_madera,        
            $tiene_vidrio,       
            $tiene_textil,       
            $data['descripcion'] 
        );

        if ($stmt->execute()) {
            $respuesta = ["success" => true];
        } else {
            $respuesta = ["success" => false, "error" => $stmt->error];
        }

        $stmt->close();
        return $respuesta;
    }

    if (isset($_GET['accion'])) {
        $accion = $_GET['accion'];

        switch ($accion) {
            case 'obtenerBodegas':
                echo json_encode(obtenerBodegas($conn));
                break;

            case 'obtenerMonedas':
                echo json_encode(obtenerMonedas($conn));
                break;

            case 'obtenerSucursalId': 
                if (isset($_GET['idBodega'])) {
                    $idBodega = intval($_GET['idBodega']);
                    echo json_encode(obtenerSucursalId($conn, $idBodega));
                } else {
                    echo json_encode(["error" => "Falta idBodega"]);
                }
                break;
            case 'verificarCodigo':
                $json = file_get_contents("php://input");
                $data = json_decode($json, true);
                if (!empty($data['codigo'])) {
                    echo json_encode(verificarCodigo($conn, $data['codigo']));
                } else {
                    echo json_encode(["error" => "Código no recibido"]);
                }
                break;

            case 'insertarProducto':
                $json = file_get_contents("php://input");
                $data = json_decode($json, true);

                if ($data) {
                    echo json_encode(insertarProducto($conn, $data));
                } else {
                    echo json_encode(["success" => false, "error" => "Datos inválidos"]);
                }
            break;

            default:
                echo json_encode(["error" => "Acción no válida"]);
                break;
        }
    } else {
        echo json_encode(obtenerBodegas($conn));
    }
    $conn->close();
?>
