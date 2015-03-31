<?php
class model_network extends CI_model{
	

	public function query_friend(){

		//This query will return all the friend of current user. Attention: include the user self!
		$uid=$this->session->userdata('uid');
		$username=$this->session->userdata('username');
		$sql="drop view if exists myCommunity";
		$this->db->query($sql);
		$sql="drop view if exists frienduid";
		$this->db->query($sql);
		$sql="create view myCommunity as (select category from Project where initiator='$uid') union
			 (select category from Fund join Project on Fund.pid=Project.pid where uid='$uid') ";
		$this->db->query($sql);
		$sql="create view frienduid as (select initiator as uid from Project where Project.category in 
			(select category from myCommunity)) union (select uid from Fund join Project on Fund.pid=Project.pid
			where category in (select category from myCommunity))";
		$this->db->query($sql);
		$sql="select email,firstName,lastName from User natural join frienduid";
		$result=$this->db->query($sql);
		return $result;
	}

}