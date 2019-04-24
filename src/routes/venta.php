<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Obtener todos los venta
$app->get('/api/venta', function(Request $request, Response $response){
    $consulta = "SELECT * FROM venta";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $venta = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        echo json_encode($venta);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Obtener un solo venta
$app->get('/api/venta/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM venta WHERE NumPedido='$id'";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $venta = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        echo json_encode($venta);
        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

// Agregar venta
$app->post('/api/venta/agregar', function(Request $request, Response $response){
    $numPedido = $request->getParam('numPedido');
    $fecha = $request->getParam('fecha');
    $nit = $request->getParam('nit');
    $descuento = $request->getParam('descuento');
    $totalPagar = $request->getParam('totalPagar');
    $estado = $request->getParam('estado');

       
        $consulta = "INSERT INTO venta (NumPedido, Fecha, NIT, Descuento, TotalPagar, Estado) VALUES
        (:numPedido, :fecha, :nit, :descuento, :totalPagar, :estado)";
        try{
            // Instanciar la base de datos
            $db = new db();
    
            // Conexión
            $db = $db->conectar();
            $stmt = $db->prepare($consulta);
            $stmt->bindParam(':numPedido', $numPedido);
            $stmt->bindParam(':fecha',  $fecha);
            $stmt->bindParam(':nit',      $nit);
            $stmt->bindParam(':descuento',      $descuento);
            $stmt->bindParam(':totalPagar',    $totalPagar);
            $stmt->bindParam(':estado',       $estado);
            $stmt->execute();
            echo '{"notice": {"text": "venta agregada"}';
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }

});