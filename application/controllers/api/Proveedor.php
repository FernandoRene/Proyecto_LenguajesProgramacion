<?php
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Proveedor extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('Proveedor_model');
        $this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
    }

    public function getlistaproveedor_get(){

        try
	  	{
	  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
	  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
	  		{
		  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
		  		$idpersonal = $jwtData['idpersonal']; // OBTENER VALORES DEL TOKEN
		  		$data = $this->Proveedor_model->getproveedor();
		  		if($data)
		  		{
		  			$respuesta = array(
								'error' => false,
								'mensaje' => 'Correcto, datos del proveedor',
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
								'mensaje' => 'No se recupero ningun registro de proveedores',
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
	  		if (!(array_key_exists('razon_social', $data)
	  					&& array_key_exists('nit', $data)	  					
	  					&& array_key_exists('direccion', $data)
                        && array_key_exists('telefono', $data)
                        && array_key_exists('contacto', $data)
                        && array_key_exists('email', $data)
                        && array_key_exists('descripcion', $data)))
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
	  			if($this->form_validation->run('proveedor_post'))
	  			{
	  				$respuesta = $this->registrarproveedor($data);
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

    function registrarproveedor($data)
  	{
  		$razon_social = trim($data['razon_social']);
  		$nit = $data['nit'];  		
        $direccion = trim($data['direccion']);
        $telefono = $data['telefono'];
  		$contacto = $data['contacto'];
  		$email = $data['email'];
        $descripcion = trim($data['descripcion']);

  		$datap = array(
  			'razon_social' => $razon_social,
  			'nit' => $nit,
            'direccion' => $direccion,
            'telefono' => $telefono,
            'contacto' => $contacto,
  			'email' => $email,
            'descripcion' => $descripcion,
  			'estado' => 1,
  		);
  		$idproveedor = $this->Proveedor_model->guardarProveedor($datap);
 		

  		$respuesta = array(
							'error' => false,
							'mensaje' => 'GUARDADO CORRECTAMENTE',
							'idproveedor' => $idproveedor			
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
		  		if (!(array_key_exists('razon_social', $data)
                             && array_key_exists('nit', $data)	  					
                            && array_key_exists('direccion', $data)
                            && array_key_exists('telefono', $data)
                            && array_key_exists('contacto', $data)
                            && array_key_exists('email', $data)
                            && array_key_exists('descripcion', $data) 
		  					&& array_key_exists('id_proveedor', $data))) //idpersona del registro a modificar
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
		  			if($this->form_validation->run('proveedor_modificar_post'))
		  			{
		  				$respuesta = $this->modificarproveedor($data);
		  				
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

    function modificarproveedor($data)
  	{
  		$idproveedor = trim($data['id_proveedor']);
  		if($this->Proveedor_model->getproveedorid($idproveedor))
  		{
  			$razon_social = trim($data['razon_social']);
  		    $nit = $data['nit'];  		
            $direccion = trim($data['direccion']);
            $telefono = $data['telefono'];
  		    $contacto = $data['contacto'];
  		    $email = $data['email'];
            $descripcion = trim($data['descripcion']);

  			$datap = array(
                'razon_social' => $razon_social,
                'nit' => $nit,
                'direccion' => $direccion,
                'telefono' => $telefono,
                'contacto' => $contacto,
                'email' => $email,
                'descripcion' => $descripcion
	  		);
  			$idproveedorupdate = $this->Proveedor_model->updateProveedor($idproveedor,$datap);
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'DATOS ACTUALIZADOS CORRECTAMENTE',
							'idproveedor' => $idproveedor
				);
  		}	
  		else
  		{
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, El id del personal no se encuentra registrado',
				);	
  		}
  		
  		return $respuesta;
  	}

}

?>