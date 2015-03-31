<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Controller for the Project Description page

class Project extends CI_Controller {
	
	public function index($valueIn) {
		$var1 = $valueIn;
		
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			$this->loadView($var1);
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function loadView($dataIn) {
		
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			$pidIn['pid'] = $dataIn;
			
			// load the model
			$this->load->model('project_model');
			
			// get the project title
			$data['pTitle'] = $this->project_model->getTitle($pidIn);
			
			// get the project description
			$data['description'] = $this->project_model->getDescription($pidIn);
			
			// get the number of funders for the project
			$data['numFunders'] = $this->project_model->getNumFunders($pidIn);
			
			// get the number of days to go before the refund
			$data['daysToGo'] = $this->project_model->getDaysToGo($pidIn);
			
			// get the project's rating
			$data['prating'] = $this->project_model->getProjectRating($pidIn);
			
			// get the money raised so far
			$data['cashFunded'] = $this->project_model->getCashSoFar($pidIn);
			
			// get the total amount of money needed
			$data['cashNeeded'] = $this->project_model->getCashNeeded($pidIn);
			
			// pass in the pId
			$data['pid'] = $dataIn;
			
			// get the updates that were made to the project
			$data['updates'] = $this->project_model->getUpdates($pidIn);
			
			// check if the current user can update the project
			$data['canUpdate'] = $this->project_model->canUpdate($pidIn); // this is a boolean value
			
			// get the comments that were made about the project
			$data['comments'] = $this->project_model->getComments($pidIn);
			
			// get the initiator of the project
			$data['initiator'] = $this->project_model->getInitiator($pidIn);
			//load the view
			$this->load->view('project_view', $data);
			
		} else {
			$this->load->view('pleaseLogin');
		}
		
	}
	
	public function fundProject($valueIn) {
		
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			// get the pid
			$data['pid'] = $valueIn;
			
			// get the POST values from the form
			$data['dollars'] = $this->input->post('dollars');
			$data['cents'] = $this->input->post('cents');
			
			$pidIn['pid'] = $valueIn;
			
			// get all the other stuff needed for the page
			// load the model
			$this->load->model('project_model');
			
			$data['username'] = $this->session->userdata('email');
			
			// add funds made into the db
			$data['donationQuery'] = $this->project_model->makeDonation($data);
			
			// ---------------------------------
			// The following code is the same as in the initial
			// project->loadView() function on this page
			// ---------------------------------
			
			// get the project title
			$data['pTitle'] = $this->project_model->getTitle($pidIn);
			
			// get the project description
			$data['description'] = $this->project_model->getDescription($pidIn);
			
			// get the number of funders for the project
			$data['numFunders'] = $this->project_model->getNumFunders($pidIn);
			
			// get the number of days to go before the refund
			$data['daysToGo'] = $this->project_model->getDaysToGo($pidIn);
			
			// get the project's rating
			$data['prating'] = $this->project_model->getProjectRating($pidIn);
			
			// get the money raised so far
			$data['cashFunded'] = $this->project_model->getCashSoFar($pidIn);
			
			// get the total amount of money needed
			$data['cashNeeded'] = $this->project_model->getCashNeeded($pidIn);
			
			$data['pid'] = $pidIn['pid'];
			
			// get the updates that were made to the project
			$data['updates'] = $this->project_model->getUpdates($pidIn);
			
			// check if the current user can update the project
			$data['canUpdate'] = $this->project_model->canUpdate($pidIn); // this is a boolean value
			
			// get the comments that were made about the project
			$data['comments'] = $this->project_model->getComments($pidIn);
			$data['initiator'] = $this->project_model->getInitiator($pidIn);
			
			//load the view
			$this->load->view('project_view', $data);
			
		} else {
			$this->load->view('pleaseLogin');
		}
		
	}
	
	public function rate($PID) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$check = array("1", "2", "3", "4", "5");
			$rating = $this->input->get('rating');
			
			if (in_array($rating, $check)) {
				// rating is of acceptable value
				$data['pid'] = $PID;
				$data['rating'] = $rating;
				
				$this->load->model('project_model');
				
				$result = $this->project_model->rateProject($data);
				
				if ($result) {
					$this->load->view('templates/projectRateSuccess');
				} else {
					echo "Error with entering project rating into the database";
				}
				
			} else {
				// rating is not acceptable value... throw error
				echo "Rating is not of a correct value";
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function update($PID) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('updateText', 'text', 'required');
			
			if ($this->form_validation->run()) {
				
				$this->load->helper('string');
				
				$dataIn['uid'] = $this->session->userdata('uid');
				$dataIn['pid'] = $PID;
				$dataIn['description'] = quotes_to_entities(ucfirst(strtolower($this->input->post('updateText'))));
				
				$this->load->model('project_model');
				
				$result = $this->project_model->updateProj($dataIn);
				
				if ($result) {
					// successfully created project update in database
					$this->loadView($PID);
				} else {
					echo "Error entering update data into database";
				}
				
			} else {
				
				$this->loadView($PID);
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
		
	}
	
	public function comment($PID) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('commentText', 'text', 'required');
			
			if ($this->form_validation->run()) {
				
				$this->load->helper('string');
				
				$dataIn['uid'] = $this->session->userdata('uid');
				$dataIn['pid'] = $PID;
				$dataIn['description'] = quotes_to_entities(ucfirst(strtolower($this->input->post('commentText'))));
				
				$this->load->model('project_model');
				
				$result = $this->project_model->commentProj($dataIn);
				
				if ($result) {
					// successfully created project comment in database
					$this->loadView($PID);
				} else {
					echo "Error entering comment data into database";
				}
				
			} else {
				$this->loadView($PID);
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
}