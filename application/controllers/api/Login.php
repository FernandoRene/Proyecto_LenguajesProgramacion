<?php 

require APPPATH . '/libraries/REST_Controller.php';
require APPPATH . '/libraries/CreatorJwt.php';

class Login extends REST_Controller{

	public function __construct()
	{
		parent::__construct();
		$this->objOfJwt = new CreatorJwt();
		header('Content-Type: application/json');
		$this->load->model('personal_model');
	}

	public function index_post()
	{
		$data = $this->post();
		if(array_key_exists('usuario', $data) && array_key_exists('clave', $data))			
		{	
			$usuario = $this->post("usuario");
			$clave = $this->post("clave");
			$clavemd5 = md5($clave);
			$login = $this->personal_model->verificar_login($usuario,$clavemd5);//LLAMAR A UN MODELO
			if($login)
			{
				if($login[0]->estadouser == 'AC')
				{
					$date = new DateTime();
					//VALORES PARA EL TOKEN
					$tokenData['idpersonal'] = $login[0]->idpersonal;
					$tokenData['fecha'] = Date('Y-m-d h:i:s');
					$tokenData['iat'] = $date->getTimestamp();
					$tokenData['exp'] = $date->getTimestamp()+$this->config->item('jwt_token_expire');

					$jwtToken = $this->objOfJwt->GenerateToken($tokenData); //GENERA EL TOKEN

					$respuesta = array(
								'error' => true,
								'mensaje' => 'TOKEN',
								'fecha' => Date('Y-m-d h:i:s'),
								'token'	  => $jwtToken    //devolvemos el token
							);
					$this->response($respuesta, REST_Controller::HTTP_OK);	
				}
				elseif ($login[0]->estadouser == 'EX')
				{
					$date = new DateTime();
					//VALORES PARA EL TOKEN
					$tokenData['idpersonal'] = $login[0]->idpersonal;
					$tokenData['fecha'] = $date->getTimestamp();
					$jwtToken = $this->objOfJwt->GenerateToken($tokenData); //GENERA EL TOKEN

					$respuesta = array(
								'error' => true,
								'mensaje' => 'ACTUALICE LA CONTRASEÑA',
								'fecha' => $date->getTimestamp(),
								'token_actualizacion'	=> $jwtToken    //devolvemos el token
								//'login'	  => $login
							);
					$this->response($respuesta, REST_Controller::HTTP_OK);	
				}
				elseif ($login[0]->estadouser == 'BA')
				{
					$respuesta = array(
								'error' => true,
								'mensaje' => 'USUARIO DESAHABILITADO',
								//'login'	  => $login
							);
					$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);	
				}
			}
			else
			{
				$respuesta = array(
								'error' => true,
								'mensaje' => 'Datos no Existentes',							
							);
				$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
			}
		}
		else
		{
			$respuesta = array(
								'error' => true,
								'mensaje' => 'Debe introducir los parametros correctos',						
							);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
		
		
	}

}

?>