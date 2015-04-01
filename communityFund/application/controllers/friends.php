<?php
class friends extends CI_Controller{
	public function index(){

		// first view for friends. loading the community info and friends info
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

	public function chat($withWhom){
		if ($this->session->userdata('is_logged_in')){
			$this->load->model('model_network');
			$fn=$this->model_network->query_firstname($withWhom);
			$data['withWhomfn']=$fn;
			$data['withWhomuid']=$withWhom;
			$msg=$this->input->post('msg');
			
			//if it's load from friend list view, it has nothing to do with db
			if (!$msg){
				//echo "no msg";
			}else{
			//if it's load from the chatting view, it must first insert current msg to db
				if ($this->model_network->insert_msg($withWhom,$msg)){
					//echo "good!";
				}else{
					echo "Error sending the message; Please try again";
				}
			}
			
			$data['history']=$this->model_network->query_history($withWhom);
			//before user load that page, all the msg before now should been marked as read
			$this->model_network->mark_As_Read($withWhom);
			$this->load->view('chat_view',$data);
		}else{
			$this->load->view('pleaseLogin');
		}
	}

}