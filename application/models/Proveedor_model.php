<?php  
class Proveedor_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db_proyecto = $this->load->database('proyecto',TRUE);
    }
    function getproveedor()
    {
        $query = $this->db_proyecto->query(
        "select * from proveedor where estado = '1'"
        );
        return $query->result();
    }

    function guardarProveedor($data)
	{
		$this->db_proyecto->insert('proveedor',$data);
		return $this->db_proyecto->insert_id();
	}

	function getproveedorid($idproveedor)
	{
		$query = $this->db_proyecto->query("select *
			                                  from proveedor
											 where id_proveedor = ".$idproveedor);
		return $query->result();
	}

	function updateProveedor($idproveedor,$data)
	{
		$this->db_proyecto->where('id_proveedor', $idproveedor);
		return $this->db_proyecto->update('proveedor',$data);
	}

}


?>