<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// $app = new \Slim\App;

//Obtener todos los categoria
$app->get('/api/categoria/', function(Request $request, Response $response){
    $consulta = "SELECT * FROM categoria";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $categoria = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        return $response->withJson($categoria);

    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
    }
});

//Obtener un solo categoria
$app->get('/api/categoria/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM categoria WHERE CodigoCat='$id'";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $categoria = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        return $response->withJson($categoria);
        
    } catch(PDOException $e){
        $err= array('error' => $e->getMessage(), 'status'=> 500);
        return $response->withJson($err, 500);
    }
});