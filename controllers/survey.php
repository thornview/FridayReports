<?php

class Survey extends CI_Controller {
    public function __construct()
    {
	parent::__construct();
	$this->load->model('survey_model');
        $this->load->model('site_visits_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('date');
        
        //$this->check_isvalidated();
    }
   
    
   // Every password-protected controller needs this private function to 
   // check if the user is validated.
    
   private function check_isvalidated(){
		if(! $this->session->userdata('validated')){
			redirect('login');
		}
	}
            
    public function show_report_menu()
     {
          // Set $data for the view
        $user_id = $this->session->userdata('userid');
        $user_type = $this->session->userdata('user_type');
        $cluster = $this->session->userdata('cluster');
        
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'survey';
        $data['sub_menu'] = 'templates/survey_submenu';
        
        $this->load->view('templates/header', $data);
        $this->load->view('survey/home', $data);
        $this->load->view('templates/footer');
     }
 
    public function record_response() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $data['title'] = 'Technology Customer Survey';
        
        $data['cluster_schools'] = $this->site_visits_model->list_cluster_schools('2', 'O');
        $data['cluster_technicians'] = $this->site_visits_model->list_cluster_users('2', 't');
        
        $this->form_validation->set_rules('comments', 'comments', 'trim|xss_clean');
        $this->form_validation->set_rules('signature', 'signature', 'trim|xss_clean');
          
        if ($this->form_validation->run() === FALSE)
	{
            $this->load->view('templates/header', $data);
            
            if ($this->input->cookie('mnps_tech_survey')) 
            {
                $this->load->view('survey/time_out');
            }
            else
            {
            $this->load->view('survey/survey_form', $data);
            }
            
            $this->load->view('templates/footer', $data);
        }
        
        else 
        {
           if ($this->survey_model->record_response()) {
               $this->load->helper('cookie');
               $cookie = array(
                 'name' => 'mnps_tech_survey',
                 'value' => 'Completed Survey',
                 'expire' => '86400'
               );
               //'expire' = '86400' = 1 day in seconds
               //'expire' = '604800' = 1 week in seconds
               $this->input->set_cookie($cookie);
               
               redirect('survey/thanks');
           }
           
           else {
               echo "<h2>ERROR: Result not posted.</h2>";
           }
           
        }
    }
    
    public function thanks()
    {
        $this->load->view('templates/header');
        $this->load->view('survey/thanks');
        $this->load->view('templates/footer');
    }
   
    public function show_school_response_count()
    {
        // Set $data for the view
        $user_id = $this->session->userdata('userid');
        $user_type = $this->session->userdata('user_type');
        $cluster = $this->session->userdata('cluster');
        
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'survey';
        
        // Get the data from the database
        $data['response_count'] = $this->survey_model->get_school_response_count('2014-A', '2');
        $data['sub_menu'] = 'templates/survey_submenu';
        
        $this->load->view('templates/header', $data);
        $this->load->view('survey/school_response_count', $data);
        $this->load->view('templates/footer');
    }
    
    public function show_average_scores()
    {
        // Set $data for the view
        $user_id = $this->session->userdata('userid');
        $user_type = $this->session->userdata('user_type');
        $cluster = $this->session->userdata('cluster');
        
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'survey';
        $data['sub_menu'] = 'templates/survey_submenu';
        
        
        // Whose schools are we looking up?
        if ($this->input->post('user_id'))
        {
            $user_selected = $this->input->post('user_id');
        }
        
        elseif($user_type == 'A') 
        {
            $user_selected = '0000';
        }
        
        else 
        {
            $user_selected = $user_id;
        }
        
        
        // Load data for the submenu
        $this->load->helper('form');
        $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
        $data['selected_user_id'] = $user_selected;
        
         // Get the data from the database
        $data['result_averages'] = $this->survey_model->get_averages_by_school('2014-A', $user_selected);
        $data['result_comments'] = $this->survey_model->get_comments_by_school('2014-A', $user_selected);
        
        // Call the views
        $this->load->view('templates/header', $data);
        if ($user_type == 'A')
        {
            $this->load->view('survey/average_submenu', $data);
        }
        $this->load->view('survey/average_results', $data);
        $this->load->view('templates/footer');
    }
    

}
?>
