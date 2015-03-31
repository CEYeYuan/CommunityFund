<?php
class model_network extends CI_model{
	

	public function query_friend(){

		//This query will return all the friend of current user. Attention: include the user self!
		$uid=$this->session->userdata('uid');
		$username=$this->session->userdata('email');
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
		$sql="select username,firstName,lastName,uid from User natural join frienduid";
		$result=$this->db->query($sql);
		return $result;
	}

	public function query_firstname($uid){
		//given the uid, query the corresponding firstname
		$sql="select firstName from User where uid='$uid'";
		$result=$this->db->query($sql);
		return $result;
	}

	public function insert_msg($to,$msg){
		//store the message 
		$from=$this->session->userdata('uid');
		$now=date("Y-m-d H:i:s");
		$sql="insert into Chathistory values ('$from','$to','$now','$msg','-1')";
		$result=$this->db->query($sql);
		return $result;
	}

	public function query_history($withWhom){
		//query the history messages between two users
		$me=$this->session->userdata('uid');
		$sql="(select * from Chathistory where sender='$me' and receiver='$withWhom') 
		union (select * from Chathistory where sender='$withWhom' and receiver='$me')
		order by time ASC";
		$result=$this->db->query($sql);
		return $result;
	}

}