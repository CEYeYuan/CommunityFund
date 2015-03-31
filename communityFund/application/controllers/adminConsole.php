<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Controller for the admin console page

class AdminConsole extends CI_Controller {
	
	public function index() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			$this->loadView();
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function loadView() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			$this->load->view('adminConsoleMain_view');
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function addAdmin() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			// load all the current admins
			$this->load->model('admin_console_model');
			
			$data['admin'] = $this->admin_console_model->getAdmins();
			
			$this->load->view('adminConsoleAddAdmin_view', $data);
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function makeAdmin($uid = null) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			if (isset ($uid)) {
				// class entry function
				$this->load->model('admin_console_model');
				
				$this->admin_console_model->addAdmin($uid);
				
				$data['admin'] = $this->admin_console_model->getAdmins();
				
				$this->load->view('adminConsoleAddAdmin_view', $data);
			} else {
				echo "Need to specify a uid to the makeAdmin function";
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function removeAdmin($uid = null) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			
			if (isset ($uid)) {
				// class entry function
				$this->load->model('admin_console_model');
				
				$this->admin_console_model->deleteAdmin($uid);
				
				$data['admin'] = $this->admin_console_model->getAdmins();
				
				$this->load->view('adminConsoleAddAdmin_view', $data);
				
			} else {
				echo "Need to specify a uid to the deleteAdmin function";
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function deactivate($uid = null) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			if (isset ($uid)) {
				// class entry function
				$this->load->model('admin_console_model');
				
				$this->admin_console_model->deactivateAccount($uid);
				
				$data['admin'] = $this->admin_console_model->getAdmins();
				
				$this->load->view('adminConsoleAddAdmin_view', $data);
				
			} else {
				echo "Need to specify a uid to the deleteAdmin function";
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function aggregate() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->model('admin_console_model');
			
			$data['graph'] = $this->admin_console_model->getCategoryBreakdownData();
			
			$data['fullFunded'] = $this->admin_console_model->getCompletelyFunded();
			
			$data['partFunded'] = $this->admin_console_model->getPartFunded();
			
			$data['numCategories'] = $this->admin_console_model->getNumCategories();
			
			$this->load->view('adminConsoleAggregate_view', $data);
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function category() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->model('admin_console_model');
			
			$data['catInfo'] = $this->admin_console_model->getCategoryInfo();
			
			$this->load->view('adminCategory_view', $data);
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function disableCategory($cid = null) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->model('admin_console_model');
			
			$this->admin_console_model->disableCat($cid);
			
			$data['catInfo'] = $this->admin_console_model->getCategoryInfo();
			
			$this->load->view('adminCategory_view', $data);
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function enableCategory($cid = null) {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->model('admin_console_model');
			
			$this->admin_console_model->enableCat($cid);
			
			$data['catInfo'] = $this->admin_console_model->getCategoryInfo();
			
			$this->load->view('adminCategory_view', $data);
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function createCategory() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('name', 'Name', 'required|trim');
			$this->form_validation->set_rules('description', 'Description', 'required|trim');
			
			if ($this->form_validation->run()) {
				
				// get all the input values
				$data['name'] = quotes_to_entities($this->input->post('name'));
				$data['description'] = quotes_to_entities($this->input->post('description'));
				
				$this->load->model('admin_console_model');
				
				$value = $this->admin_console_model->createCat($data);
				
				if ($value) {
					
					$data['catInfo'] = $this->admin_console_model->getCategoryInfo();
					
					$this->load->view('adminCategory_view', $data);
					
				} else {
					echo "Error when adding Category to the database";
				}
				
			} else {
				$this->category();
			}
			
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function feedback() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->view('adminConsoleFeedback_view');
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	public function createFeedback() {
		// session check
		if ($this->session->userdata('is_logged_in')) {
			// class entry function
			
			$this->load->library('form_validation');
			
			//$this->form_validation->set_rules('name', 'Name', 'required|trim');
			
			$this->form_validation->set_rules('title', 'Title', 'required|trim');
			$this->form_validation->set_rules('description', 'Description', 'required|trim');
			
			if ($this->form_validation->run()) {
				
				// get all the input values
				$data['title'] = quotes_to_entities($this->input->post('title'));
				$data['type'] = $this->input->post('type');
				$data['description'] = quotes_to_entities($this->input->post('description'));
				
				$this->load->model('admin_console_model');
				
				$value = $this->admin_console_model->newFeedback($data);
				
				if ($value) {
					echo "<p>";
					echo "Feedback successfully submitted to the database!";
					echo "<br>";
					echo "<br>";
					echo '<a href="'.base_url().'home">Back</a> to home page.';
				} else {
					echo "Error submitting feedback to database";
				}
				
			} else {
				$this->feedback();
			}
			
		} else {
			$this->load->view('pleaseLogin');
		}
	}
	
	
}