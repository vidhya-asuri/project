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


$app->get('/pos', "getAllPOs" );
$app->post('/pos', "create" );
$app->get('/pos/{po_id}', "read" );


/*****
 * https://stackoverflow.com/questions/630453/put-vs-post-in-rest
 * 
 * https://stackoverflow.com/questions/18504235/understand-backbone-js-rest-calls
 * 
 * https://www.mike-miles.com/blog/reading-backbonejs-post-variables-php
 * 
 * https://kleopetrov.me/2015/12/10/Backbone-collections/
 * 
 */
$app->run();


function read(Request $request, Response $response, array $args) {
	$poNumber = $args['po_id'];

	$sql = "select * FROM po_test where po_id = " . $poNumber;
	try {
		$db = getConnection();
		// echo "connection successful";
		$stmt = $db->query($sql);
		$po = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"app::get /pos/{poNumber}": ' . json_encode($po) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
	
}

// insert into po_test ( po_num,date, name, email,vendor, description, amount, purchase_location, status)
// values ('LEX1234', 20190528, 'Vidhya', 'sasuri@sstire.com', 'Dell', ' laptop', 600.55 ,1, 1);
function create() {

	$formData = json_decode(file_get_contents('php://input'), true);
	$sql = "insert into po_test ( po_num,name, date, email,vendor, description, amount, purchase_location, status)
            values ( 'Test_Po_Number', '".  $formData['name'] . "' , " .date("Ymd") ." ,'" . $formData['email'] . "' , '" . $formData['vendor'] . "' , '" . $formData['description'] . "' , '" . $formData['amount'] . "' , " . $formData['location'] . ",1" . ")";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);
		// $saveResult = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"save result ": ' . json_encode($stmt) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}';
	}
// 	echo json_encode($sql);

}


function getAllPOs() {
	$sql = "select * FROM po_test ";
	try {
		$db = getConnection();
		// echo "connection successful";
		$stmt = $db->query($sql);
		$pos = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		//echo '{"pos ": ' . json_encode($pos) . '}';
		echo json_encode($pos);
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
