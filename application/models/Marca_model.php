<?php  

class Marca_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db_proyecto = $this->load->database('proyecto',TRUE);
    }
    
    function getmarca()
    {
        $query = $this->db_proyecto->query(
        "select * from marca"
        );
        return $query->result();
    }

    function guardarMarca($data)
	{
		$this->db_proyecto->insert('marca',$data);
		return $this->db_proyecto->insert_id();
	}

	function getmarcaid($idmarca)
	{
		$query = $this->db_proyecto->query("select *
			                                  from marca
											 where id_marca = ".$idmarca);
		return $query->result();
	}

	function updateMarca($idmarca,$data)
	{
		$this->db_proyecto->where('id_marca', $idmarca);
		return $this->db_proyecto->update('marca',$data);
	}

}


?>