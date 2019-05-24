<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';




$app = new \Slim\App;
$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->get('/tasks', "getAllTasks" );

$app->post('/tasks', "getAllTasks" );


$app->post('/tasks/new',"save");	



$app->run();


function save() {
	//https://coderwall.com/p/vwvy_a/retrieving-post-data-from-backbone-js-in-php
	$var = json_decode(file_get_contents('php://input'), true);
	// echo  '{"post data ": ' . json_encode($var) . '}';
	$sql = "insert into tasks (name, date) values ( '".  $var['name'] . "' , '" . $var['date'] . "' )";
 	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		$db = null;
		echo '{"sql ": ' . json_encode($sql) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
	
}

function getAllTasks() {
	$sql = "select * FROM tasks ";
	try {
		$db = getConnection();
		// echo "connection successful";
		$stmt = $db->query($sql);
		$pos = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"tasks ": ' . json_encode($pos) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
}

function getConnection() {
	$dbhost="10.10.102.41";
	$dbuser="intranetrw";
	$dbpass="aSw2017";
	$dbname="PO";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}
