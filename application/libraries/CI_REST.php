<?php
defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CI_REST extends CI_Controller {

    public $requestBody;
    private $alloweHTTPMethods = ['get', 'delete', 'post', 'put', 'options', 'patch', 'head'];
    //['get', 'delete', 'post', 'put', 'options', 'patch', 'head'];
    //200 (OK), o 201 (CREATED), o 204 (NO CONTENT), o 404 (NOT FOUND) e o 400 (BAD REQUEST).
    public function __construct() {
        parent::__construct();
        
    }

    final function index($param = array()) {

        /*
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET' : $method = '_get'; break;
            case 'PUT' : $method = '_put'; break;
            case 'POST' : $method = '_post'; break;
            case 'DELETE' : $method = '_delete'; break;
            case 'OPTIONS' : $method = '_options'; break;
            case 'PATCH' : $method = '_patch'; break;
            case 'HEAD' : $method = '_head'; break;
            default: $method = 'index';
        }
        */
        $this->requestBody = json_decode(file_get_contents('php://input'));
        
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        call_user_func_array(array($this, '_'.$requestMethod), $param);
    }

    final function _remap($method, $param) {
        $this->index(array_merge(array($method), $param));
    }

    public function responseJSON($r) {
        header('Content-Encoding: gzip');
		header('Content-type:application/json; charset=utf-8');
		echo gzencode(json_encode($r));
    }

    public function responseCode($code) {
        http_response_code($code);
	}

    abstract public function _get();
    abstract public function _put();
    abstract public function _post();
    abstract public function _delete();
    abstract public function _options();
    abstract public function _patch();
    abstract public function _head();
}