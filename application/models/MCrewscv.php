<?php
class MCrewscv extends CI_Model
{
	function __construct()
    {
      parent::__construct();
	  $this->CI = get_instance();	  
    }

    function getDataQuery($query = "")
    {
    	$dataOut = array();
    	$dataOut = $this->db->query($query)->result();
    	return $dataOut;
    }

    function getData($slc = "",$db = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db->select($slc);
		$this->db->from($db);
		if($whereNya != "")
		{
			$this->db->where($whereNya);
		}
		if($order != "")
		{
			$this->db->order_by($order);
		}
		if($grp != "")
		{
			$this->db->group_by($order);
		}
		
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function getJoin2($slc = "",$db1 = "",$db2 = "",$joinOn = "",$typeJoin = "",$whereNya = "",$order = "",$grp = "")
	{
		$this->db->select($slc);
		$this->db->from($db1);
		$this->db->join($db2,$joinOn,$typeJoin);
		if($whereNya != "")
		{
			$this->db->where($whereNya);
		}
		if($order != "")
		{
			$this->db->order_by($order);
		}
		if($grp != "")
		{
			$this->db->group_by($order);
		}
		
		$query = $this->db->get();
		$dataOut = $query->result();
		return $dataOut;
	}

	function insData($db = "",$insData = "",$return = "")
	{
		$this->db->insert($db,$insData);

		if($return != "")
		{
			return $this->db->insert_id();
		}
	}

	function delData($db = "",$idWhere = "")
	{
		$this->db->where($idWhere);
  		$this->db->delete($db);
	}

	function updateData($whereNya = "",$data = "",$tbl = "")
	{
		$this->db->where($whereNya);
		$this->db->update($tbl,$data);
	}



}
?>