<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//Obtener todos los clientes
$app->get('/api/clientes', function(Request $request, Response $response){
    $consulta = "SELECT * FROM cliente";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $clientes = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        echo json_encode($clientes);

    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});

//Obtener un solo cliente
$app->get('/api/clientes/{id}', function(Request $request, Response $response){

    $id = $request->getAttribute('id');

    $consulta = "SELECT * FROM cliente WHERE NIT='$id'";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexión
        $db = $db->conectar();
        $ejecutar = $db->query($consulta);
        $cliente = $ejecutar->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        //Exportar y mostrar en formato JSON
        echo json_encode($cliente);
        
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


// Agregar Cliente
$app->post('/api/clientes/agregar', function(Request $request, Response $response){
    $nit = $request->getParam('nit');
    $nombre = $request->getParam('nombre');
    $apellido = $request->getParam('apellido');
    $telefono = $request->getParam('Telefono');
    $email = $request->getParam('email');
    $direccion = $request->getParam('direccion');
    $nombreCompleto = $request->getParam('nombreCompleto');
    $clave = $request->getParam('clave');


    //consulta si existe el nit para que no existan repetidos
    $consulta = "SELECT * FROM cliente WHERE NIT='$nit'";
    $db = new db();
    // Conexión
    $db = $db->conectar();
    $ejecutar = $db->query($consulta);
    
    if($ejecutar==""){
        
        $consulta = "INSERT INTO cliente (Nombre, Apellido, Telefono, Email, Direccion, NIT, Clave, NombreCompleto) VALUES
        (:nombre, :apellido, :telefono, :email, :direccion, :nit, :clave, :nombreCompleto)";
        try{
            // Instanciar la base de datos
            $db = new db();
    
            // Conexión
            $db = $db->conectar();
            $stmt = $db->prepare($consulta);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':apellidos',  $apellidos);
            $stmt->bindParam(':telefono',      $telefono);
            $stmt->bindParam(':email',      $email);
            $stmt->bindParam(':direccion',    $direccion);
            $stmt->bindParam(':nit',       $nit);
            $stmt->bindParam(':clave',      $clave);
            $stmt->bindParam(':nombreCompleto',      $nombreCompleto);
            $stmt->execute();
            echo '{"notice": {"text": "Cliente agregado"}';
        } catch(PDOException $e){
            echo '{"error": {"text": '.$e->getMessage().'}';
        }
    } else {
        echo '{"error": {"text": ya existe en base de datos}';
    }

});


// Actualizar Cliente
$app->put('/api/clientes/actualizar/{id}', function(Request $request, Response $response){
    $nit = $request->getAttribute('nit');
    $nombre = $request->getParam('nombre');
    $apellido = $request->getParam('apellido');
    $telefono = $request->getParam('telefono');
    $email = $request->getParam('email');
    $direccion = $request->getParam('direccion');
    $clave = $request->getParam('clave');
    $nombreCompleto = $request->getParam('nombreCompleto');


     $consulta = "UPDATE clientes SET
				Nombre 	        = :nombre,
				Apellido 	    = :apellido,
                telefono	    = :telefono,
                email		    = :email,
                Direccion   	= :direccion,
                Clave 		    = :clave,
                NombreCompleto    = :nombreCompleto
			WHERE NIT = $nit";


    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexion
        $db = $db->conectar();
        $stmt = $db->prepare($consulta);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellidos',  $apellidos);
        $stmt->bindParam(':telefono',      $telefono);
        $stmt->bindParam(':email',      $email);
        $stmt->bindParam(':direccion',    $direccion);
        $stmt->bindParam(':ciudad',       $ciudad);
        $stmt->bindParam(':departamento',      $departamento);
        $stmt->execute();
        echo '{"notice": {"text": "Cliente actualizado"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});


// Borrar cliente
$app->delete('/api/clientes/borrar/{id}', function(Request $request, Response $response){
    $id = $request->getAttribute('id');
    $sql = "DELETE FROM clientes WHERE id = $id";
    try{
        // Instanciar la base de datos
        $db = new db();

        // Conexion
        $db = $db->conectar();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;
        echo '{"notice": {"text": "Cliente borrado"}';
    } catch(PDOException $e){
        echo '{"error": {"text": '.$e->getMessage().'}';
    }
});