<?php
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Sucursal extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Sucursal_model');
        $this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
    }

    public function getlistasucursal_get(){

        try
	  	{
	  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
	  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
	  		{
		  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
		  		$idpersonal = $jwtData['idpersonal']; // OBTENER VALORES DEL TOKEN
		  		$data = $this->Sucursal_model->getsucursal();
		  		if($data)
		  		{
		  			$respuesta = array(
								'error' => false,
								'mensaje' => 'Correcto, datos de la sucursal',
								'datos' => $data,
								'token' => $jwtData,
								'idpersonal' => $idpersonal
						);
				  	$this->response($respuesta, REST_Controller::HTTP_OK);	
		  		}
		  		else
		  		{
		  			$respuesta = array(
								'error' => true,
								'mensaje' => 'No se recupero ningun registro de sucursales',
								'datos' => null,							
						);
				  	$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);	
		  		}	  		
			  }
			  else
			  {
			  	$respuesta = array(
								'error' => true,
								'mensaje' => 'ACCESO DENEGADO',
						);
				  $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
			  }
			}
			catch(Exception $e)
			{
				http_response_code('401');
				$respuesta = array(
												"status" =>false,
												"message" => $e->getMessage()
				              	);
				echo json_encode($respuesta);
				exit;
		}
       
    }


    
    public function verificarcadena_ckeck($cadena)
	{
		$patron =  "/^[a-zA-Z\sñáéíóúÁÉÍÓÚ]+$/";
		if(preg_match($patron, $cadena))
		{
			return true;				
		}
		else
		{
			$this->form_validation->set_message('verificarcadena_ckeck', 'El campo {field} solo debe contener letras');
			return false;
		}
	}

    function registrar_post()
  	{
  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
  		{
	  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
	  		$idpersonal = $jwtData['idpersonal']; // OBTENER VALORES DEL TOKEN
	  		
	  		$data = $this->post();
	  		if (!(array_key_exists('descripcion', $data)				
	  					&& array_key_exists('direccion', $data)
                        && array_key_exists('telefono', $data)))
	  		{
	  			$respuesta = array(
								'error' => true,
								'mensaje' => 'Debe introducir los parametros correctos',						
							);
					$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);	
	  		}
	  		else
	  		{	  			
	  			$this->load->library('form_validation');  //inicializando la libreria
	  			$this->form_validation->set_data($data);
	  			//$this->form_validation->set_rules('nombres','nombres','required');   // aplicando reglas de validacion
	  		//	if($this->form_validation->run() == FALSE) // obteniendo respuesta de validacion
	  			if($this->form_validation->run('sucursal_post'))
	  			{
	  				$respuesta = $this->registrarsucursal($data);
	  			}
	  			else
	  			{	  				
	  				$respuesta = array(
							'error' => false,
							'mensaje' => 'datos incorrectos',
							'errores' => $this->form_validation->get_errores_arreglo(),		//SE DEVUELVE LOS ERRORES					
						);
	  			}

	  			

	  			//$respuesta = $this->registrarusuario($data);
	  			
	  			
			  	$this->response($respuesta, REST_Controller::HTTP_OK);	
	  		}
	  	}
		  else
		  {
		  	$respuesta = array(
							'error' => true,
							'mensaje' => 'ACCESO DENEGADO',
					);
			  $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		  }
  	}


      function registrarsucursal($data)
  	{
        $descripcion = trim($data['descripcion']);
        $direccion = trim($data['direccion']);
        $telefono = $data['telefono'];
       

  		$datap = array(
            'descripcion' => $descripcion,
            'direccion' => $direccion,
            'telefono' => $telefono
  		);
  		$idsucursal = $this->Sucursal_model->guardarSucursal($datap);
 		

  		$respuesta = array(
							'error' => false,
							'mensaje' => 'GUARDADO CORRECTAMENTE',
							'idsucursal' => $idsucursal			
			);
			return $respuesta;
  	}


      public function modificar_post()
  	{
  		try
  		{
	  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
	  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
	  		{
		  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
		  		$iduser = $jwtData['idpersonal']; // OBTENER VALORES DEL TOKEN
		  		
		  		$data = $this->post();
		  		if (!(array_key_exists('direccion', $data)
                            && array_key_exists('telefono', $data)                            
                            && array_key_exists('descripcion', $data) 
		  					&& array_key_exists('id_sucursal', $data))) //idpersona del registro a modificar
		  		{
		  			$respuesta = array(
									'error' => true,
									'mensaje' => 'Debe introducir los parametros correctos',						
								);
						$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);	
		  		}
		  		else
		  		{	  			
		  			$this->load->library('form_validation');  //inicializando la libreria
		  			$this->form_validation->set_data($data);
		  			//$this->form_validation->set_rules('nombres','nombres','required');   // aplicando reglas de validacion
		  		//	if($this->form_validation->run() == FALSE) // obteniendo respuesta de validacion
		  			if($this->form_validation->run('sucursal_modificar_post'))
		  			{
		  				$respuesta = $this->modificarsucursal($data);
		  				
		  			}
		  			else
		  			{	  				
		  				$respuesta = array(
								'error' => false,
								'mensaje' => 'datos incorrectos',
								'errores' => $this->form_validation->get_errores_arreglo(),		//SE DEVUELVE LOS ERRORES					
							);
		  			}
		  			//$respuesta = $this->registrarusuario($data);
				  	$this->response($respuesta, REST_Controller::HTTP_OK);	
		  		}
		  	}
			  else
			  {
			  	$respuesta = array(
								'error' => true,
								'mensaje' => 'ACCESO DENEGADO',
						);
				  $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
			  }
			}
			catch(Exception $e)
			{
				http_response_code('401');
				$respuesta = array(
												"status" =>false,
												"message" => $e->getMessage()
				              	);
				echo json_encode($respuesta);
				exit;
			}
  	}

    function modificarsucursal($data)
  	{
  		$idsucursal = trim($data['id_sucursal']);
  		if($this->Sucursal_model->getsucursalid($idsucursal))
  		{
            $descripcion = trim($data['descripcion']);
  			$direccion = trim($data['direccion']);
            $telefono = $data['telefono'];  	    
            

  			$datap = array(
                'descripcion' => $descripcion,
                'direccion' => $direccion,
                'telefono' => $telefono
                
	  		);
  			$idsucursalupdate = $this->Sucursal_model->updateSucursal($idsucursal,$datap);
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'DATOS ACTUALIZADOS CORRECTAMENTE',
							'idsucursal' => $idsucursal
				);
  		}	
  		else
  		{
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, El id de la sucursal no se encuentra registrado',
				);	
  		}
  		
  		return $respuesta;
  	}
}

?>