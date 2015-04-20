<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login controller class
 */
class Login extends CI_Controller{
	
	function __construct(){
	parent::__construct();
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('encrypt');
	}
	
 public function index($msg = NULL){
        // Load our view to be displayed
        // to the user
        $data['msg'] = $msg;
        $this->load->view('templates/header');
        $this->load->view('login_view', $data);
        $this->load->view('templates/footer');
    }
    
    public function process(){
        $this->load->model('login_model');
        // Validate the user can login
        $result = $this->login_model->validate();
        // Now we verify the result
        if(! $result){
            // If user did not validate, then show them login page again
            $msg = '<font color=red>Invalid username and/or password. </font><br />';
            $this->index($msg);
        }else{
            // If user did validate, 
            // Send them to members area
            redirect('site_visits/create_entry');
        }        
    }

}
?>
