<?php
if(! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
	'personal_post' => array(
		array('field' => 'ci', 
			  'label' => 'ci',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[9]'),
		array('field' => 'nombres', 
			  'label' => 'nombres',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'primer_apellido', 
			  'label' => 'primer_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'segundo_apellido', 
			  'label' => 'segundo_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'cargo', 
			  'label' => 'cargo',
			  'rules' => 'trim|required|min_length[1]|max_length[1]|alpha|callback_verificarusuario_ckeck'),
		array('field' => 'clave', 
			  'label' => 'clave',
			  'rules' => 'trim|required|min_length[8]|max_length[20]|alpha_numeric'),
	),
	'personal_modificar_post' => array(
		array('field' => 'ci', 
			  'label' => 'ci',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[9]'),
		array('field' => 'nombres', 
			  'label' => 'nombres',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'primer_apellido', 
			  'label' => 'primer_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'segundo_apellido', 
			  'label' => 'segundo_apellido',
			  'rules' => 'trim|required|min_length[1]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'idpersonal', 
			  'label' => 'idpersonal',
			  'rules' => 'trim|required|numeric')		
	)
	,
	'cambiarclave_post' => array(
		array('field' => 'claveactual', 
			  'label' => 'claveactual',
			  'rules' => 'trim|required|min_length[6]|max_length[20]|alpha_numeric'),
		array('field' => 'clavenueva', 
			  'label' => 'clavenueva',
			  'rules' => 'trim|required|min_length[8]|max_length[20]|alpha_numeric'),
		array('field' => 'confirmacion', 
			  'label' => 'confirmacion',
			  'rules' => 'trim|required|min_length[8]|max_length[20]|alpha_numeric')
	),

	'proveedor_post' => array(
		array('field' => 'nit', 
			  'label' => 'nit',
			  'rules' => 'trim|required|numeric|min_length[7]|max_length[10]'),
		array('field' => 'razon_social', 
			  'label' => 'razon_social',
			  'rules' => 'trim|required|min_length[8]|max_length[50]'),
		array('field' => 'telefono', 
			  'label' => 'telefono',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[8]'),
		array('field' => 'direccion', 
			  'label' => 'direccion',
			  'rules' => 'trim|required|min_length[1]|max_length[100]'),
		array('field' => 'contacto', 
			  'label' => 'contacto',
			  'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'descripcion', 
			  'label' => 'descripcion',
			  'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'email', 
			  'label' => 'email',
			  'rules' => 'required|min_length[3]|valid_email|trim')
	),
	
	'proveedor_modificar_post' => array(
		array('field' => 'nit', 
			  'label' => 'nit',
			  'rules' => 'trim|required|numeric|min_length[7]|max_length[10]'),
		array('field' => 'razon_social', 
			  'label' => 'razon_social',
			  'rules' => 'trim|required|min_length[8]|max_length[50]'),
		array('field' => 'telefono', 
			  'label' => 'telefono',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[8]'),
		array('field' => 'direccion', 
			  'label' => 'direccion',
			  'rules' => 'trim|required|min_length[1]|max_length[100]'),
		array('field' => 'contacto', 
			  'label' => 'contacto',
			  'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'descripcion', 
			  'label' => 'descripcion',
			  'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'email', 
			  'label' => 'email',
			  'rules' => 'required|min_length[3]|valid_email|trim'),
		array('field' => 'id_proveedor', 
			  'label' => 'id_proveedor',
			  'rules' => 'trim|required|numeric')
	),

	'sucursal_post' => array(
		array('field' => 'descripcion', 
		'label' => 'descripcion',
		'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_ckeck'),		
		array('field' => 'telefono', 
			  'label' => 'telefono',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[8]'),
		array('field' => 'direccion', 
			  'label' => 'direccion',
			  'rules' => 'trim|required|min_length[1]|max_length[100]')

	),
	'sucursal_modificar_post' => array(
		array('field' => 'telefono', 
			  'label' => 'telefono',
			  'rules' => 'trim|required|numeric|min_length[3]|max_length[8]'),
		array('field' => 'direccion', 
			  'label' => 'direccion',
			  'rules' => 'trim|required|min_length[1]|max_length[100]'),
		array('field' => 'descripcion', 
			  'label' => 'descripcion',
			  'rules' => 'trim|required|min_length[3]|max_length[100]|callback_verificarcadena_ckeck'),
		array('field' => 'id_sucursal', 
			  'label' => 'id_sucursal',
			  'rules' => 'trim|required|numeric')
	),

	

);



?>