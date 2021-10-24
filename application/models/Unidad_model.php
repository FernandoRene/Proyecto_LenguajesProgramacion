<?php  

class Unidad_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db_proyecto = $this->load->database('proyecto',TRUE);
    }
    
    function getunidad()
    {
        $query = $this->db_proyecto->query(
        "select * from unidad"
        );
        return $query->result();
    }

    function guardarUnidad($data)
	{
		$this->db_proyecto->insert('unidad',$data);
		return $this->db_proyecto->insert_id();
	}

	function getunidadid($idunidad)
	{
		$query = $this->db_proyecto->query("select *
			                                  from unidad
											 where id_unidad = ".$idunidad);
		return $query->result();
	}

	function updateUnidad($idproveedor,$data)
	{
		$this->db_proyecto->where('id_unidad', $idproveedor);
		return $this->db_proyecto->update('unidad',$data);
	}

}


?>