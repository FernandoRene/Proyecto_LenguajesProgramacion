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
        "select * from proveedor"
        );
        return $query->result();
    }
}


?>