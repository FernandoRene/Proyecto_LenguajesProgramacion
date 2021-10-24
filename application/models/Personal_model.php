<?php  
class Personal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db_proyecto = $this->load->database('proyecto',TRUE);
    }

    function verificar_login($usuario, $clave)
	{
		$query = $this->db_proyecto->query("select estado as estadouser, 
												   id_personal as idpersonal											       
			                                  from personal
											 where usuario = '".$usuario."' 
											   and clave = '".$clave."'"
											);
		return $query->result();
	}    

    function getpersonal()
    {
        $query = $this->db_proyecto->query(
        "select * from personal"
        );
        return $query->result();
    }

    function guardarPersonal($data)
	{
		$this->db_proyecto->insert('personal',$data);
		return $this->db_proyecto->insert_id();
	}
	function getpersonalid($idpersonal)
	{
		$query = $this->db_proyecto->query("select *
			                                  from personal
											 where id_personal = ".$idpersonal);
		return $query->result();
	}
	function getverificarclaveusario($idpersonal, $clave)
	{
		$query = $this->db_proyecto->query("select *
			                                  from personal  
											 where id_personal = '".$idpersonal."' 
											   and clave = '".$clave."'"
											);
		return $query->result();
	}
	function updatePersonal($idpersonal,$data)
	{
		$this->db_proyecto->where('id_personal', $idpersonal);
		return $this->db_proyecto->update('personal',$data);
	}

}


?>