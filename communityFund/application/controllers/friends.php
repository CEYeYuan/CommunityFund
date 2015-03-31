<?php
class friends extends CI_Controller{
	public function index(){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('model_users');
			$data['communities']=$this->model_users->query_community();
			$this->load->model('model_network');
			$data['friends']=$this->model_network->query_friend();

			$this->load->view('friend_view',$data);
		}else{
			$this->load->view('pleaseLogin');
		}
	}

}