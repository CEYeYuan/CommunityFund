<?php

class Project_model extends CI_Model {
	
	public function getTitle($data) {
		
		// get the $pid value from $data
		$pid = $data['pid'];
		
		// run the query
		$titleQuery = $this->db->query("SELECT pname FROM Project WHERE pid=" . $pid . ";");
		
		return $titleQuery->result();
		
	}
	
	public function getDescription($data) {
		// get the $pid value from $data
		$pid = $data['pid'];
		
		// run the query
		$descriptionQuery = $this->db->query("SELECT description FROM Project WHERE pid=" . $pid . ";");
		
		return $descriptionQuery->result();
	}
	
	public function getNumFunders($data) {
		
		// get the $pid value from $data
		$pid = $data['pid'];
		
		// run the query
		// find the number of funders for a pid
		//$numFunders = $this->db->query("SELECT DISTINCT COUNT(fid) AS total FROM Fund WHERE pid=" . $pid . ";");
		$queryString = "SELECT DISTINCT COUNT(uid) AS total FROM Fund WHERE pid=1;";
		
		$numFunders = $this->db->query($queryString);
		
		return $numFunders->result();
	}
	
	public function getDaysToGo($data) {
		$pid = $data['pid'];
		
		/*
		 * Example query result for the next query
		 * 
		 * +----------+
		   | diffDate |
		   +----------+
		   |      106 |
		   +----------+
		 * 
		 * */
		
		$daysToGo = $this->db->query("SELECT DATEDIFF((SELECT endDate FROM Project WHERE pid=" . $pid . "), now()) AS diffDate;");
		
		return $daysToGo->result();
	}
	
	public function getProjectRating($data) {
		
		$pid = $data['pid'];
		
		$prating = $this->db->query("SELECT IFNULL(AVG(rating),0) AS total FROM RateProj WHERE pid=" . $pid . ";");
		
		return $prating->result();
		
	}
	
	public function getCashSoFar($data) {
		$pid = $data['pid'];
		
		$cashFunded = $this->db->query("SELECT IFNULL(SUM(amount),0) AS total FROM Fund WHERE pid=" . $pid . ";");
		
		return $cashFunded->result();
	}
	
	public function getCashNeeded($data) {
		$pid = $data['pid'];
		
		$cashNeeded = $this->db->query("SELECT IFNULL(fundsNeeded, 0) AS needed FROM Project WHERE pid=" . $pid . ";");
		
		return $cashNeeded->result();
		
	}
	
	// takes in a uid parameter and returns the fid
	public function getFid($UID) {
		$uid = $UID;
		
		$query = $this->db->query("SELECT fid FROM Funder WHERE active=1 AND uid='" . $uid . "';");
		$row = $query->row();
		$fid = $row->fid;
		
		// this is a variable, not an array
		return $fid;
	}

	public function getInitiator($data) {
		$pid = $data['pid'];
		
		$query = $this->db->query("SELECT username from User join Project on initiator=uid where pid='$pid'");
		$row = $query->row();
		$username = $row->username;
		
		// this is a variable, not an array
		return $username;
	}
	
	public function makeDonation($data) {
		
		// get the uid from the session
		$uid = $this->session->userdata('uid');
		
		$pid = $data['pid'];
		$username = $data['username'];
		
		// get the donation info
		$dollars = $data['dollars'];
		$cents = $data['cents'];
		$amount = $dollars.".".$cents;
		
		// make the donation
		$queryString = "INSERT INTO Fund (uid, pid, date, amount, active) VALUES (".$uid.", ".$pid.", now(), '".$amount."', 1);";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	public function rateProject($dataIn) {
		
		//$data['pid'] = $PID;
		//$data['rating'] = $rating;
		
		$uid = $this->session->userdata('uid');
		$pid = $dataIn['pid'];
		$rating = $dataIn['rating']; // either one of [1, 2, 3, 4, 5]
		
		$queryString = "INSERT INTO RateProj (uid, pid, date, rating) VALUES (".$uid.", ".$pid.", now(), ".$rating.");";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function updateProj($dataIn) {
		
		$uid = $dataIn['uid'];
		$pid = $dataIn['pid'];
		$description = $dataIn['description'];
		
		$queryString = "INSERT INTO UpdateProj (uid, pid, date, description, active) VALUES (".$uid.", ".$pid.", now(), '".$description."', 1);";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function commentProj($dataIn) {
		
		$uid = $dataIn['uid'];
		$pid = $dataIn['pid'];
		$description = $dataIn['description'];
		
		$queryString = "INSERT INTO Comment (uid, pid, date, description, active) VALUES (".$uid.", ".$pid.", now(), '".$description."', 1);";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getUpdates($dataIn) {
		$pid = $dataIn['pid'];
		
		$queryString = "SELECT UPR.uid, UPR.date, UPR.description, US.username FROM UpdateProj UPR JOIN User US ON UPR.uid=US.uid WHERE UPR.pid=".$pid." AND UPR.active=1 AND US.active=1;";
		
		$query = $this->db->query($queryString);
		
		return $query->result();
	}
	
	public function canUpdate($dataIn) {
		
		$uid = $this->session->userdata('uid');
		$pid = $dataIn['pid'];
		
		$queryString = "SELECT FD.uid, FD.pid, PR.initiator FROM Fund FD JOIN Project PR ON FD.pid=PR.pid WHERE FD.active=1 AND PR.active=1 AND (FD.uid=".$uid." OR PR.initiator=".$uid.");";
		
		$query = $this->db->query($queryString);
		
		if ($query->num_rows() >= 1) {
			// user can update
			return true;
		} else {
			// user cannot update
			return false;
		}
	}
	
	public function getComments($dataIn) {
		
		$uid = $this->session->userdata('uid');
		$pid = $dataIn['pid'];
		
		$queryString = "SELECT US.username, CM.description, CM.date FROM User US JOIN Comment CM ON US.uid=CM.uid WHERE CM.pid=".$pid." AND CM.active=1 AND US.active=1;";
		
		$query = $this->db->query($queryString);
		
		return $query->result();
	}
	
}