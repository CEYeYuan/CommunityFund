<?php

class Projectcreate_model extends CI_Model {
	
	// return all existing categories
	public function getCategories() {
		
		$categories = $this->db->query("SELECT cid, name, description FROM Category WHERE active=1 ORDER BY name ASC;");
		
		return $categories->result();
	}
	
	// insert a new project into the database
	public function insertProject($inputData) {
		
		// get the uid
		$uid = $this->session->userdata('uid');
		
		// get all data from $inputData
		$title = $inputData['title'];
		$category = $inputData['category'];
		$year = $inputData['year'];
		$month = $inputData['month'];
		$endDay = $inputData['endDay'];
		$endYear = $inputData['endYear'];
		$endMonth = $inputData['endMonth'];
		$day = $inputData['day'];
		$description = $inputData['description'];
		$dollars = $inputData['dollars'];
		$cents = $inputData['cents'];
		
		$username = $inputData['username'];
		
		
		$fundsNeeded = $dollars . "." . $cents;
		$startDate = $year . "-" . $month . "-" . $day . " 00:00:00";
		$endDate = $endYear . "-" . $month . "-" . $day . " 00:00:00";
		
		/*
		 *  WE ALREADY GET THE UID ABOVE
		// get the uid
		$query0 = $this->db->query("SELECT uid FROM User WHERE active=1 AND username='" . $username . "';");
		$row = $query0->row();
		$uid = $row->uid;
		* ***/
		
		// create the project
		$queryString = "INSERT INTO Project (pname, description, fundsNeeded, startDate, endDate, postDate, category, initiator, pic, active) VALUES ('" . $title . "', '" . $description . "', " . $fundsNeeded . ", '" . $startDate . "', '" . $endDate . "', now(), ".$category.", ".$uid.", '', 1);";
		$query = $this->db->query($queryString);
		
		// get the pid of the project we just created
		$queryString = "SELECT pid FROM Project WHERE pname='".$title."' AND description='".$description."' AND fundsNeeded='".$fundsNeeded."' AND category=".$category." AND initiator=".$uid." AND active=1;";
		$query1 = $this->db->query($queryString);
		
		$row = $query1->row();
		$pid = $row->pid;
		
		if ($query1) {
			return true;
		} else {
			return false;
		}
	}
	
}