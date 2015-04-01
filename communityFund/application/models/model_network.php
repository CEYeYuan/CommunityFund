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
		$sql="drop view if exists friendList";
		$this->db->query($sql);
		$sql="drop view if exists unReadMsg";
		$this->db->query($sql);
		$sql="create view myCommunity as (select category from Project where initiator='$uid') union
			 (select category from Fund join Project on Fund.pid=Project.pid where uid='$uid') ";
		$this->db->query($sql);
		$sql="create view frienduid as (select initiator as uid from Project where Project.category in 
			(select category from myCommunity)) union (select uid from Fund join Project on Fund.pid=Project.pid
			where category in (select category from myCommunity))";
		$this->db->query($sql);
		$sql="create view friendList as select username,firstName,lastName,uid from User natural join frienduid";
		$this->db->query($sql);
		// so far the friend list is queried out. we also want to know if user have unread msg from that friend

		$sql="create view unReadMsg as select sender as uid,ifread from Chathistory where receiver='$uid' and ifread='-1'";
		$this->db->query($sql);
		$sql="select distinct username,firstName,lastName,friendList.uid as uid,ifread from friendList left join unReadMsg
			 on friendList.uid=unReadMsg.uid";
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
		$escapeMsg=mysql_real_escape_string($msg);
		$sql="insert into Chathistory values ('$from','$to','$now','$escapeMsg','-1')";
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


	public function query_unread(){
		//return true if there're msgs current user haven't read
		$me=$this->session->userdata('uid');
		$now=date("Y-m-d H:i:s");
		$sql="select * from Chathistory where receiver='$me' ifread='-1'";
		$result=$this->db->query('$sql');
		if ($result->num_rows()>0){
			return true;
		}else{
			return false;
		}
	}


	public function mark_As_Read($withWhom){
		$me=$this->session->userdata('uid');
		$now=date("Y-m-d H-i-s");
		$sql="update Chathistory set ifread='1' where receiver='$me' and sender='$withWhom' and time<='$now'";
		$this->db->query($sql);
	}
}