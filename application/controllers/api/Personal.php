<?php
require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Personal extends REST_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('personal_model');
        $this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
    }

    public function getlistapersonal_get(){

        try
	  	{
	  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
	  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
	  		{
		  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
		  		$idpersonal = $jwtData['idpersonal']; // OBTENER VALORES DEL TOKEN
		  		$data = $this->personal_model->getpersonal();
		  		if($data)
		  		{
		  			$respuesta = array(
								'error' => false,
								'mensaje' => 'Correcto, datos usuario',
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
								'mensaje' => 'No se recupero ningun registro de usuarios',
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
	public function verificarusuario_ckeck($tipo)
	{
		if($tipo == 'A' || $tipo == 'B' || $tipo == 'C' || $tipo == 'D')
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('verificarusuario_ckeck', 'El campo {field} no es correcto');
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
	  		if (!(array_key_exists('ci', $data)
	  					&& array_key_exists('nombres', $data)
	  					&& array_key_exists('primer_apellido', $data)
	  					&& array_key_exists('segundo_apellido', $data)
	  					&& array_key_exists('direccion', $data)
                        && array_key_exists('celular', $data)
                        && array_key_exists('cargo', $data)
	  					&& array_key_exists('clave', $data)))
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
	  			if($this->form_validation->run('personal_post'))
	  			{
	  				$respuesta = $this->registrarpersonal($data);
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


    function registrarpersonal($data)
  	{
  		$ci = trim($data['ci']);
  		$nombres = trim(strtoupper($data['nombres']));
  		$primer_apellido = trim(strtoupper($data['primer_apellido']));
  		$segundo_apellido = trim(strtoupper($data['segundo_apellido']));
        $direccion = trim($data['direccion']);
        $celular = $data['celular'];
  		$cargo = $data['cargo'];
  		$clave = $data['clave'];

  		$nombres_user = str_replace(" ","", $nombres);
  		$primer_apellido_user = str_replace(" ","",$primer_apellido);
  		$usuario = $nombres_user.".".$primer_apellido_user;
  		$clavemd5 = md5($clave);

  		$datap = array(
  			'ci' => $ci,
  			'nombres' => $nombres,
  			'primer_apellido' => $primer_apellido,
  			'segundo_apellido' => $segundo_apellido,
            'direccion' => $direccion,
            'celular' => $celular,
            'cargo' => $cargo,
  			'usuario' => $usuario,
  			'clave' => $clavemd5,
  			'estado' => 'EX',
  		);
  		$idpersonal = $this->personal_model->guardarPersonal($datap);
 		

  		$respuesta = array(
							'error' => false,
							'mensaje' => 'GUARDADO CORRECTAMENTE',
							'idpersonal' => $idpersonal			
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
		  		if (!(array_key_exists('ci', $data)
		  					&& array_key_exists('nombres', $data)
		  					&& array_key_exists('primer_apellido', $data)
		  					&& array_key_exists('segundo_apellido', $data)	  	
                            && array_key_exists('direccion', $data)
                            && array_key_exists('celular', $data)
                            && array_key_exists('cargo', $data)	 
		  					&& array_key_exists('idpersonal', $data))) //idpersona del registro a modificar
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
		  			if($this->form_validation->run('personal_modificar_post'))
		  			{
		  				$respuesta = $this->modificarpersonal($data);
		  				
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

    function modificarpersonal($data)
  	{
  		$idpersonal = trim($data['idpersonal']);
  		if($this->personal_model->getpersonalid($idpersonal))
  		{
  			$ci = trim($data['ci']);
	  		$nombres = trim(strtoupper($data['nombres']));
	  		$primer_apellido = trim(strtoupper($data['primer_apellido']));
	  		$segundo_apellido = trim(strtoupper($data['segundo_apellido']));
            $direccion = trim($data['direccion']);
            $celular = $data['celular'];
            $cargo = $data['cargo'];

  			$datap = array(
	  			'ci' => $ci,
	  			'nombres' => $nombres,
	  			'primer_apellido' => $primer_apellido,
	  			'segundo_apellido' => $segundo_apellido,
                'direccion' => $direccion,
                'celular' => $celular,
                'cargo' => $cargo
	  		);
  			$idpersonalupdate = $this->personal_model->updatePersonal($idpersonal,$datap);
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'DATOS ACTUALIZADOS CORRECTAMENTE',
							'idpersonal' => $idpersonal
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


    function cambiarclave_post()
  	{
  		$received_Token = $this->input->request_headers('Authorization'); //RECUPERAMOS EL TOKEN 
  		if(array_key_exists('Authorization', $received_Token))  //VERIFICAMOS EL PARAMETRO DE Authorization
  		{
	  		$jwtData = $this->objOfJwt->DecodeToken($received_Token['Authorization']); //DECODIFICAMOS DATOS TOKEN
	  		$idpersonal = $jwtData['idpersonal']; // OBTENER VALORES DEL TOKEN
	  		
	  		$data = $this->post();
	  		if (!(array_key_exists('claveactual', $data)
	  					&& array_key_exists('clavenueva', $data)
	  					&& array_key_exists('confirmacion', $data))) //idpersona del registro a modificar
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
	  			if($this->form_validation->run('cambiarclave_post'))
	  			{
	  				$respuesta = $this->actualizarclaveusuario($idpersonal,$data);
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
    
    
    function actualizarclaveusuario($idpersonal,$data)
  	{
  		$claveactual = md5($data['claveactual']);
  		$clavenueva = md5($data['clavenueva']);
  		$confirmacion = md5($data['confirmacion']);

  		if($clavenueva == $confirmacion)
  		{
  			if($clavenueva != $claveactual)
  			{
  				if($this->personal_model->getverificarclaveusario($idpersonal,$claveactual))
	  			{
	  					$datau = array(
				  			'clave' => $clavenueva,
				  			'estado' => 'AC',
				  		);
  						$idpersonal = $this->personal_model->updatePersonal($idpersonal,$datau);

  						$respuesta = array(
								'error' => false,
								'mensaje' => 'CLAVE ACTUALIZADA CORRECTAMENTE, INICIE SESSION NUEVAMENTE CON LAS NUEVAS CREDENCIALES',
								'estado' => 'AC',
								'idpersonal' => $idpersonal,
							);
	  			}
	  			else
	  			{
	  					$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, la contraseña actual no es correcta',
							);
	  			}
  			}
  			else
  			{
  				$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, la contraseña nueva deber ser diferente a la actual',
					);
  			}
  		}
  		else
  		{
  			$respuesta = array(
							'error' => true,
							'mensaje' => 'Error, No coinciden la nueva contraseña con la confirmacion',
					);
  		}

  		return $respuesta;
			
  	}

}

?>