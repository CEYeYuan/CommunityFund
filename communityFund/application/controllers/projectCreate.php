<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Controller for the Create Project Page

class ProjectCreate extends CI_Controller {
	
	public function index() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			$this->loadView();
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function loadView() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			// load the model
			$this->load->model('projectcreate_model');
			
			// get all the existing categories from the DB
			$data['categories'] = $this->projectcreate_model->getCategories();
			
			$this->load->view('projectCreate_view', $data);
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	
	public function create() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			//$this->form_validation->set_rules('title', 'Title', 'required|trim');
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('title', 'Title', 'required|trim');
			$this->form_validation->set_rules('dollars', 'Dollars', 'required|trim');
			$this->form_validation->set_rules('cents', 'Cents', 'required|trim');
			$this->form_validation->set_rules('description', 'Description', 'required|trim');
			
			if ($this->form_validation->run()) {
				
				// get all the form data values
				$inputData['title'] = $this->input->post('title');
				$inputData['category'] = $this->input->post('category');
				$inputData['year'] = $this->input->post('year');
				$inputData['month'] = $this->input->post('month');
				$inputData['day'] = $this->input->post('day');
				$inputData['endYear'] = $this->input->post('endYear');
				$inputData['endMonth'] = $this->input->post('endMonth');
				$inputData['endDay'] = $this->input->post('endDay');
				$inputData['description'] = $this->input->post('description');
				$inputData['dollars'] = $this->input->post('dollars');
				$inputData['cents'] = $this->input->post('cents');
				
				// get user info from the session
				$inputData['username'] = $this->session->userdata('email');
				
				// load the model
				$this->load->model('projectcreate_model');
				
				// run the queries
				$result = $this->projectcreate_model->insertProject($inputData);
				
				// redirect to home page
				if ($result) {
					$this->load->view('templates/projectCreateSuccess');
				} else {
					echo "Error in adding the project to the database";
				}
				
			} else {
				// redirect to projectCreate_view with validation errors
				$this->loadView();
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	
}