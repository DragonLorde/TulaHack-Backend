<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");


require_once "core/base.php";
include_once "modules/user.php";
include_once "core/error.php";
include_once "modules/servise.php";


class Api {

    private $q;
    public $params;
    private $data;

    function __construct()
    {
        
        $this->q = $_GET['q'];
        $this->params = explode('/' , $_GET['q']);
        if($_POST) {
            $this->data = $_POST;
        } else {
            $this->data = json_decode(file_get_contents("php://input"), true);
        }
    }

    function CallMethod($class , $method, $params=null) {
        if(!method_exists($class , $method)) ErrorMaker::ErrorHeandler("not method" , 404);
        try {
            $class->{$method}($this->data, $this->params[1]);
        } catch(Exception $e) {
            echo "stop server Warning";
        }
    }
}


$api = new Api();


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $api->CallMethod( $user , ucfirst($api->params[0]) );
        break;
    case 'POST':
        $api->CallMethod($servise , ucfirst($api->params[0]));
        break;
    default:
        ErrorMaker::ErrorHeandler("not params", 200, [
            "version" => "1.0",
            "date" => date('l jS \of F Y h:i:s A')
        ]);
        break;
}