<?php
	/*
		Web Service RESTful en PHP con MySQL (CRUD)
		Marko Robles
		Códigos de Programación
	*/
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
	header("Access-Control-Allow-Headers: Content-Type");

	include 'conexion.php';
	
	$pdo = new Conexion();
	
	//Listar registros y consultar registro
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		if(isset($_GET['id']))
		{
			$sql = $pdo->prepare("SELECT * FROM users WHERE id=:id");
			$sql->bindValue(':id', $_GET['id']);
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			header("HTTP/1.1 200 hay datos");
			echo json_encode($sql->fetchAll());
			exit;				
			
			} else {
			
			$sql = $pdo->prepare("SELECT * FROM users");
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			header("HTTP/1.1 200 hay datos");
			echo json_encode($sql->fetchAll());
			exit;		
		}
	}
	
	//Insertar registro
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$sql = "INSERT INTO users (nombre, cantidad, sabores) VALUES(:nombre,:cantidad,:sabores)";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':nombre', $_POST['nombre']);
		$stmt->bindValue(':cantidad', $_POST['cantidad']);
		$stmt->bindValue(':sabores', $_POST['sabores']);
		$stmt->execute();
		$idPost = $pdo->lastInsertId(); 
		if($idPost)
		{
			header("HTTP/1.1 200 Ok");
			echo json_encode($idPost);
			exit;
		}
	}
	
	// Actualizar registro
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    if ($requestData) {
        $sql = "UPDATE users SET nombre=:nombre, cantidad=:cantidad, sabores=:sabores WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nombre', $requestData['nombre']);
        $stmt->bindValue(':cantidad', $requestData['cantidad']);
        $stmt->bindValue(':sabores', $requestData['sabores']);
        $stmt->bindValue(':id', $requestData['id']);
        $stmt->execute();

        header("HTTP/1.1 200 Ok");
        exit;
    }
}

	
//Eliminar registro
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    if (isset($requestData['id'])) {
        $sql = "DELETE FROM users WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $requestData['id']);
        $stmt->execute();
        header("HTTP/1.1 200 Ok");
        exit;
    }
}
//Si no corresponde a ninguna opción anterior
header("HTTP/1.1 400 Bad Request");

?>