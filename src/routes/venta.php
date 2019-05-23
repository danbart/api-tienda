<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// $app = new \Slim\App;

//Obtener todos los venta
$app->get('/api/venta/', function(Request $request, Response $response){
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
        return $response->withJson($venta);

    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
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
        return $response->withJson($venta);
        
    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
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
            $err= array('error' => $e->getMessage(), 'status'=> 500);
            return $response->withJson($err, 500);
        }

});

// Confirmar venta
$app->post('/api/venta/confirmar/', function(Request $request, Response $response){ 
    $fecha = date('d-m-Y');
    $nit = $request->getParam('tarjeta');
    $descuento = 0;
    $totalPagar = $request->getParam('totalPagar');
    $estado = "Pendiente";
    $codigoProd =  json_decode($request->getParam('codigoProd'));
    var_dump($codigoProd);
    if(isset($nit)&&isset($totalPagar)&&isset($codigoProd)){
        //Insertamos la venta
        $venta = "INSERT INTO venta (Fecha, NIT, Descuento, TotalPagar, Estado) VALUES ('$fecha', '$nit', '$descuento', '$totalPagar', '$estado')";

        try{
            $db = new db();
            $db = $db->conectar();
            $result = $db->query($venta);
            
        } catch(PDOException $e){
            $err= array('error' => $e->getMessage(), 'status'=> 500);
            return $response->withJson($err, 500);
        }

    }else{
        $err= array('error' => 'Es necesario el envio de todos los campos', 'status'=> 500);
        return $response->withJson($err, 500);
    }




});