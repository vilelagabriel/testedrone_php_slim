<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
/*
$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = '127.0.0.1';
$config['db']['user']   = 'root';
$config['db']['pass']   = '';
$config['db']['dbname'] = 'teste_drone';

$app = new \Slim\App([['settings' => $config]]);
*/

$app = new \Slim\App();

/*teste Slim Framework
$app->get('/', function (Request $request, Response $response, array $args) {
    $response->getBody()->write("Hello World"); 
    return $response;
});
*/

$app->get('/', function () {});

$app->get('/Drone','getDadosDrone');//lista drones

$app->post('/NewDrone','addNewDrone');//insere drone


$app->run();

//Cria a conexão com o BD
function getConn()
{
    return new PDO('mysql:host=127.0.0.1;dbname=teste_drone','root','',
    array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );

}

//Seleciona a tabela criada no BD
function getDadosDrone()
{
    $stmt = getConn()->query("SELECT * FROM drone");
    $dronebd = $stmt->fetchAll(PDO::FETCH_OBJ);
    echo "{Dados Drone:".json_encode($dronebd)."}";
}

function addNewDrone()
{
    $request = \Slim\Slim::getInstance()->request();
    $drone = json_decode($request->getBody());
    $sql = "INSERT INTO 'drone' (`img`, `name`, `address`, `batery`, `max_speed`, `average_speed`, `status`) 
            values (:'img',:'name',:'address',:'batery','max_speed','avarage_speed','status') ";
    $conn = getConn();
    $stmt = $conn->prepare($sql);
    $stmt->bindParam("img",$drone->img);
    $stmt->bindParam("name",$drone->name);
    $stmt->bindParam("address",$drone->address);
    $stmt->bindParam("batery",$drone->batery);
    $stmt->bindParam("max_speed",$drone->max_speed);
    $stmt->bindParam("avarage_speed",$drone->avarage_speed);
    $stmt->bindParam("status",$drone->status);
    $stmt->execute();
    $drone->id = $conn->lastInsertId();
    echo json_encode($drone);
}


