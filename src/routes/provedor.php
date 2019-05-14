<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// $app = new \Slim\App;

//Obtener todos los provedor
$app->get('/api/provedor/', function(Request $request, Response $response){
    $consulta = "SELECT * FROM proveedor";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $provedor = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        return $response->withJson($provedor);

    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
    }
});

//Obtener un solo provedor
$app->get('/api/provedor/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM proveedor WHERE NITProveedor='$id'";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $provedor = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        return $response->withJson($provedor);
        
    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
    }
});