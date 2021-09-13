<?php
require APPPATH . '/libraries/REST_Controller.php';

class Sucursal extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Sucursal_model');
    }

    public function getlistasucursal_get(){

        $data = $this->Sucursal_model->getsucursal();
        
        $respuesta = array(
            'error' => false,
            'mensaje' => 'Correcto, informacion valida',
            'datos' => $data
        );
    $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}

?>