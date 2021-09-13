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
}


?>