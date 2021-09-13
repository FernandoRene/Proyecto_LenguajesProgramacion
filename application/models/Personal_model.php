<?php  
class Personal_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db_proyecto = $this->load->database('proyecto',TRUE);
    }
    function getpersonal()
    {
        $query = $this->db_proyecto->query(
        "select * from personal"
        );
        return $query->result();
    }
}


?>