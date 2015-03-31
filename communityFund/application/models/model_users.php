<?php

class Model_users extends CI_Model{

	// DAVID ADDED THIS FUNCTION
	
	public function getAdmin($data) {
		
		$username = $data['username'];
		
		$query = $this->db->query("SELECT IFNULL(username, 0) AS username FROM User WHERE username='" . $username . "' AND admin=1 AND active=1;");
		
		// NOTE: I did not return $query->result() and will need to
		// run the result() function in the controller
		return $query;
		
	}
	
	public function getUid($dataIn) {
		$username = $dataIn['username'];
		
		$queryString = "SELECT uid FROM User WHERE username='" . $username . "';";
		
		$query = $this->db->query($queryString);
		
		// return only the first row
		// there should only be one row anyways
		$row = $query->row();
		$uid = $row->uid;
		
		return $uid;
	}
	
	public function can_log_in(){
		$this->db->where('username',$this->input->post('email'));
		$this->db->where('password',md5($this->input->post('password')));
		$query=$this->db->get('User');

		if($query->num_rows()==1){
			return true;
		}else{
			return false;
		}
	}
	
	public function add_user($key){
		$data=array(
			'username'=>$this->input->post('email'),
			'password'=>md5($this->input->post('password')),
			'firstName'=>$this->input->post('fn'),
			'lastName'=>$this->input->post('ln'),
			'email'=>($key),
			'active'=>-1,
			'admin'=>-1
		);
		$query=$this->db->insert('User',$data);
		if($query){
			return true;
		}else{
			return false;
		}
	}

	public function is_key_valid($key){
		$this->db->where('email',$key);
		$query=$this->db->get('User');

		if($query->num_rows()==1) {
			return true;
		}else{
			return false;
		}
	}

	public function add_permanent_user($key)/*{
		$this->db->where('email',$key);
		$tmp_user=$this->db->get('User');
		$email = mysql_real_escape_string($this->session->userdata('email'));


		if($tmp_user){
			$row=$tmp_user->row();
			$data=array('email'=>$email,
				'password'=>$row ->password,
				'active'=>1,
				);
			
			$where = "username = '$email'"; 
			$this->db->update("User",$data,$where);
			return $data['email'];
		}else{
			return false;
		}
	}*/
	{
		$this->db->where('email',$key);
		$tmp_user=$this->db->get('User');


		if($tmp_user){
			$row=$tmp_user->row();
			$data=array('email'=>$row->username,
				'username'=>$row->username,
				'password'=>$row ->password,
				'admin'=>$row->admin,
				'active'=>1
				);
			$this->db->where('email',$key);
			$this->db->update('User',$data,"email = '$key'");

			return $data['email'];
		}else{
			return false;
		}
	}


	public function query_profile(){



	$email = mysql_real_escape_string($this->session->userdata('email'));
			//$where = "username = '$email'";
	$queryResult = $this->db->query("select * from User where username='" . $email . "';");
	//echo $query->row()->DateofBirth;
	
	//set the initial value for gender
	return $queryResult;
	

	}

	public function update_profile(){
    	$tmp_user=$this->db->get('User');

		if($tmp_user){
			if (md5($this->input->post('pswd'))=="d41d8cd98f00b204e9800998ecf8427e")
			$data=array(
				//'password'=>md5($this->input->post('pswd')),
				'firstName'=>$this->input->post('fn'),
				'lastName'=>$this->input->post('ln'),
				'gender'=>$this->input->post('gender'),
				'DateofBirth'=>$this->input->post('year')."y".$this->input->post('month')."m".
				$this->input->post('day')."d"
				); else
			$data=array(
				'password'=>md5($this->input->post('pswd')),
				'firstName'=>$this->input->post('fn'),
				'lastName'=>$this->input->post('ln'),
				'gender'=>$this->input->post('gender'),
				'DateofBirth'=>$this->input->post('year')."y".$this->input->post('month')."m".
				$this->input->post('day')."d"
				);
            $email = mysql_real_escape_string($this->session->userdata('email'));
			$where = "username = '$email'"; 
			$this->db->update("User",$data,$where);
			return true;}
		else{
			return false;
		}
	}

	public function query_fund(){
		
		$email = mysql_real_escape_string($this->session->userdata('email'));
			//$where = "username = '$email'";
		
		// OLD QUERY
		// find all the project names where the current user is a Funder
		/*
		$queryResult = $this->db->query("select pname from User natural join Funder
			natural join Fund natural join Project  where username='$email';");
		*
		* **/
		
		// find all the project names where the current user is a Funder
		//$queryString = "SELECT pname FROM Project NATURAL JOIN Fund NATURAL JOIN User WHERE username='".$email."';";
		$queryString = "SELECT pname  FROM (Project join Fund on Project.pid=Fund.pid)  join  User on Fund.uid=User.uid 
						where Project.active=1 and User.username='$email';";
		
		$queryResult = $this->db->query($queryString);

		return $queryResult;
	}

	public function query_init(){
		
		$uid = $this->session->userdata('uid');
		
		$email = mysql_real_escape_string($this->session->userdata('email'));
			//$where = "username = '$email'";
			
		// OLD QUERY
		// find all the project names where the current user is an Initiator
		/*
		$queryResult = $this->db->query("select pname from User natural join Initiator
			natural join InitiateProj natural join Project  where username='$email';");
		*
		* **/
		
		// find all the project names where the current user is an Initiator
		$queryString = "SELECT pname FROM Project WHERE initiator=".$uid." AND active=1;";
		
		$queryResult = $this->db->query($queryString);
		
		return $queryResult;

	}

	public function query_community(){
		//query the community related to one particular user, whether he/she is a funder or initiator
		$uid = $this->session->userdata('uid');
		$queryString="select name from Category join ((select category from Project where initiator='$uid') union
			 (select category from Fund join Project on Fund.pid=Project.pid where uid='$uid')) R on R.category=Category.cid";
		$queryResult=$this->db->query($queryString);
		return $queryResult;

	}
}
