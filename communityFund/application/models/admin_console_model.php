<?php

class Admin_console_model extends CI_Model {
	
	public function getAdmins() {
		$queryString = "SELECT uid, username, admin FROM User WHERE active=1;";
		
		$query = $this->db->query($queryString);
		
		return $query->result();
	}
	
	public function addAdmin($UID) {
		
		$uid = $UID;
		
		$queryString = "UPDATE User SET admin=1 WHERE uid=".$uid." AND active=1;";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deleteAdmin($UID) {
		$uid = $UID;
		
		$queryString = "UPDATE User SET admin=0 WHERE uid=".$uid." AND active=1;";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	public function deactivateAccount($UID) {
		
		$uid = $UID;
		
		$queryString = "UPDATE User SET active=0 WHERE uid=".$uid." AND active=1;";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function getCategoryBreakdownData() {
		
		// create view showing cid, name for all active categories
		$viewString = "CREATE VIEW tmpView AS (SELECT cid, name FROM Category WHERE active=1);";
		
		$queryView = $this->db->query($viewString);
		
		// count the number of projects in each category
		// OLD STRING
		//$queryString = "SELECT CAT.cid AS cid, CAT.name AS cname, COUNT(PC.cid) AS total FROM Category CAT JOIN ProjCat PC ON CAT.cid=PC.cid WHERE CAT.active=1 GROUP BY PC.cid;";
		$queryString = "SELECT CAT.cid AS cid, CAT.name AS cname, COUNT(PR.category) AS total FROM Category CAT JOIN Project PR ON CAT.cid=PR.category WHERE CAT.active=1 GROUP BY PR.category;";
		
		$query = $this->db->query($queryString);
		
		$drop = "DROP VIEW tmpView;";
		
		$dropQuery = $this->db->query($drop);
		
		return $query->result();
		
	}
	
	public function getNumCategories() {
		$queryString = "SELECT COUNT(cid) AS total FROM Category WHERE active=1;";
		
		$query = $this->db->query($queryString);
		
		$row = $query->row();
		$numCategories = $row->total;
		
		return $numCategories;
		
	}
	
	public function getCompletelyFunded() {
		
		$fullFunded = 0;
		
		$viewString = "CREATE VIEW tmpView AS (SELECT pid, SUM(amount) AS total FROM Fund GROUP BY pid);";
		
		$viewQuery = $this->db->query($viewString);
		
		$dropString = "DROP VIEW tmpView;";
		
		$queryString = "SELECT TP.pid, TP.total, PR.fundsNeeded FROM Project PR JOIN tmpView TP ON PR.pid=TP.pid WHERE PR.active=1 AND TP.total >= PR.fundsNeeded GROUP BY pid;";
		
		$query = $this->db->query($queryString);
		
		$dropQuery = $this->db->query($dropString);
		
		$fullFunded = $query->num_rows();
		
		return $fullFunded;
	}
	
	public function getPartFunded() {
		
		$partFunded = 0;
		
		$viewString = "CREATE VIEW tmpView AS (SELECT pid, SUM(amount) AS total FROM Fund GROUP BY pid);";
		
		$viewQuery = $this->db->query($viewString);
		
		$dropString = "DROP VIEW tmpView;";
		
		$queryString = "SELECT TP.pid, TP.total, PR.fundsNeeded FROM Project PR JOIN tmpView TP ON PR.pid=TP.pid WHERE PR.active=1 AND TP.total < PR.fundsNeeded GROUP BY pid;";
		
		$query = $this->db->query($queryString);
		
		$dropQuery = $this->db->query($dropString);
		
		$partFunded = $query->num_rows();
		
		return $partFunded;
		
	}
	
	public function getCategoryInfo() {
		
		$queryString = "SELECT cid, name, description, active FROM Category;";
		
		$query = $this->db->query($queryString);
		
		return $query->result();
	}
	
	public function disableCat($CID) {
		
		$cid = $CID;
		
		$queryString = "UPDATE Category SET active=0 WHERE cid=".$cid." AND active=1;";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function enableCat($CID) {
		$cid = $CID;
		
		$queryString = "UPDATE Category SET active=1 WHERE cid=".$cid." AND active=0;";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
	}
	
	public function createCat($dataIn) {
		
		$name = $dataIn['name'];
		$description = $dataIn['description'];
		
		$queryString = "INSERT INTO Category (name, description, pic, active) VALUES ('".$name."', '".$description."', '', 1);";
		
		$query = $this->db->query($queryString);
		
		// return whether the query was successful
		if ($query) {
			return true;
		} else {
			return false;
		}
		
	}
	
	public function newFeedback($dataIn) {
		
		$title = $dataIn['title'];
		$type = $dataIn['type'];
		$description = $dataIn['description'];
		
		$queryString = "INSERT INTO Feedback (title, type, description) VALUES ('".$title."', '".$type."', '".$description."');";
		
		$query = $this->db->query($queryString);
		
		if ($query) {
			return true;
		} else {
			return false;
		}
		
	}
	
}
