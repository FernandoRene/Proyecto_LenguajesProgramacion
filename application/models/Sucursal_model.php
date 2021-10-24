<?php  

class Sucursal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db_proyecto = $this->load->database('proyecto',TRUE);
    }
    
    function getsucursal()
    {
        $query = $this->db_proyecto->query(
        "select * from sucursal"
        );
        return $query->result();
    }

    function guardarSucursal($data)
	{
		$this->db_proyecto->insert('sucursal',$data);
		return $this->db_proyecto->insert_id();
	}

	function getsucursalid($idsucursal)
	{
		$query = $this->db_proyecto->query("select *
			                                  from sucursal
											 where id_sucursal = ".$idsucursal);
		return $query->result();
	}

	function updateSucursal($idsucursal,$data)
	{
		$this->db_proyecto->where('id_sucursal', $idsucursal);
		return $this->db_proyecto->update('sucursal',$data);
	}

}


?>