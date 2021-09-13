<?php
require APPPATH . '/libraries/REST_Controller.php';

class Proveedor extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Proveedor_model');
    }

    public function getlistaproveedor_get(){

        $data = $this->Proveedor_model->getproveedor();
        
        $respuesta = array(
            'error' => false,
            'mensaje' => 'Correcto, informacion valida',
            'datos' => $data
        );
    $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}

?>