<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// $app = new \Slim\App;

//Obtener todos los detalle
$app->get('/api/detalle/', function(Request $request, Response $response){
    $consulta = "SELECT * FROM detalle";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $detalle = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        // echo json_encode($detalle);
        return $response->withJson($detalle);

    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
    }
});

//Obtener un solo detalle
$app->get('/api/detalle/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM detalle WHERE NumPedido='$id'";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $detalle = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        return $response->withJson($detalle);
        
    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
    }
});

// Agregar detalle
$app->post('/api/detalle/add', function(Request $request, Response $response){
    $numPedido = $request->getParam('numPedido');
    $codigoProd = $request->getParam('codigoProd');
    $cantidadProductos = $request->getParam('cantidadProductos');

       
        $consulta = "INSERT INTO detalle (NumPedido, CodigoProd, CantidadProductos) VALUES
        (:numPedido, :codigoProd, :cantidadProductos)";
        try{
            // Instanciar la base de datos
            $db = new db();
    
            // Conexión
            $db = $db->conectar();
            $stmt = $db->prepare($consulta);
            $stmt->bindParam(':numPedido', $numPedido);
            $stmt->bindParam(':codigoProd',  $codigoProd);
            $stmt->bindParam(':cantidadProductos',      $cantidadProductos);
            $stmt->execute();
            $resp = array('result'=>'detalle agregada', 'status' => 200);
            return $response->withJson($resp);
        } catch(PDOException $e){
            $err= array('error' => $e->getMessage(), 'status'=> 500);
            return $response->withJson($err, 500);
        }

});