<?php
require APPPATH . '/libraries/REST_Controller.php';

class Personal extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Personal_model');
    }

    public function getlistapersonal_get(){

        $data = $this->Personal_model->getpersonal();
        
        $respuesta = array(
            'error' => false,
            'mensaje' => 'Correcto, informacion valida',
            'datos' => $data
        );
    $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}

?>