<?php
class Site_visits extends CI_Controller {
    public function __construct()
    {
	parent::__construct();
	$this->load->model('site_visits_model');
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('date');
        
        $this->check_isvalidated();
    }
    
    public function create_entry() {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $user_id = $this->session->userdata('userid');
        $cluster = $this->session->userdata('cluster');
        
        $data['user_type'] = $this->session->userdata('user_type');
        
        $data['title'] = 'Enter a site visit';
        $data['username'] = $this->session->userdata('username');

        $data['highlighted'] = 'create';
        $data['cluster_schools'] = $this->site_visits_model->list_cluster_schools($cluster);
        $data['assigned_schools'] = $this->site_visits_model->list_assigned_schools($user_id);           
   
        $this->form_validation->set_rules('user_signature', 'Signature', 'required');
        $this->form_validation->set_rules('notes', 'notes', 'trim|xss_clean');
        $this->form_validation->set_rules('user_signature', 'user_signature', 'trim|xss_clean');
  
        
        if ($this->form_validation->run() === FALSE)
	{
	    $this->load->helper('date');
            $this->load->view('templates/header', $data);
	    $this->load->view('site_visits/entry_form');
	    $this->load->view('templates/footer');
	}
        
        else 
        {
           $this->site_visits_model->set_visit();
           redirect('site_visits/show_history');
        }
    }
    
    public function delete_visit($visit_id) {
         $this->site_visits_model->delete_visit($visit_id);
         redirect('site_visits/show_history');
    }
    
    public function edit_visit($visit_id){
        $this->load->helper('form');
        $this->load->library('form_validation');        

        // Menu Settings
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'create';

        $data['title'] = 'Edit a Site Visit';
                
        $user_id = $this->session->userdata('userid');
        $cluster = $this->session->userdata('cluster');
        
        $data['user_type'] = $this->session->userdata('user_type');
        $data['cluster_schools'] = $this->site_visits_model->list_cluster_schools($cluster);
        $data['assigned_schools'] = $this->site_visits_model->list_assigned_schools($user_id);            
   
        $this->form_validation->set_rules('user_signature', 'Signature', 'required');
        $this->form_validation->set_rules('notes', 'notes', 'trim|xss_clean');
        $this->form_validation->set_rules('user_signature', 'user_signature', 'trim|xss_clean');
        
        
        $data['visit_data'] = $this->site_visits_model->retrieve_visit($visit_id);
        
        if ($this->form_validation->run() === FALSE)
	{
	    $this->load->helper('date');
            $this->load->view('templates/header', $data);
	    $this->load->view('site_visits/entry_form', $data);
	    $this->load->view('templates/footer');
	}
        
        else 
        {
           $visit_data = array();
           $visit_data['id'] = $this->input->post('id');
           $visit_data['location_id'] = $this->input->post('location_id');
           $visit_data['date'] = $this->input->post('visit_date');
           $visit_data['start_time'] = $this->input->post('start_hour').
                   ':'.
                   $this->input->post('start_minute');
           $visit_data['end_time'] = $this->input->post('end_hour').
                   ':'.
                   $this->input->post('end_minute');
            
            // Manually calculating the time difference.  ((H2-H1)*60)+(M2-M1).  Format for time field
            $minutes_worked = (($this->input->post('end_hour') - $this->input->post('start_hour'))*60) + 
                ($this->input->post('end_minute') - $this->input->post('start_minute'));

            $time_hours_1 = floor($minutes_worked/60); 
            $time_hours = str_pad($time_hours_1, 2, "0", STR_PAD_LEFT);
            $time_minutes_1 = $minutes_worked % 60; 
            $time_minutes = str_pad($time_minutes_1, 2, "0", STR_PAD_LEFT); 
            $visit_data['time_worked'] = $time_hours.':'.$time_minutes;
            
           $visit_data['notes'] = $this->input->post('notes', TRUE);
           $visit_data['mileage'] = $this->input->post('mileage');
           $visit_data['user_signature'] = $this->input->post('user_signature', TRUE);
           $visit_data['leave_type'] = $this->input->post('leave_type');
                   
           $this->site_visits_model->update_visit($visit_data);
           redirect('site_visits/show_history');
        }
        
        
    }
    
    public function show_history () {
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        $cluster = $this->session->userdata('cluster');
        $user_type = $this->session->userdata('user_type');
        
        // Menu Settings
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'show_history';

        
        // Set User ID
        if ($this->input->post('user_id'))
        {
            $user_id = $this->input->post('user_id');
        }
        else {
            $user_id = $this->session->userdata('userid');
        }

         // Database Settings
        if ($user_type == 'A') {
            $data['assigned_schools'] = $this->site_visits_model->list_cluster_schools($cluster);
        }
        else {
            $data['assigned_schools'] = $this->site_visits_model->list_assigned_schools($user_id);            
        }
        
        // Set Date Range
        if ($this->input->post('date_range'))
        {
            $date_range = $this->input->post('date_range');
        }
        else 
        {
            $date_range = '5';
        }
        
        //$end_date = date('Y-m-d', strtotime("today"));
        $end_date = NULL;
        // Had problems with not being able to edit dates posted in the future
        // so I am pulling out the end-date parameter.
        
        $start_date = date('Y-m-d', strtotime("$date_range days ago"));
        
        // Set Location ID        
        if ($this->input->post('location_id'))
        {
           $location_id = $this->input->post('location_id');
        }
        else 
        {
            $location_id = NULL;
        }
        
        // Identify the person who is subject of this lookup
        if ($user_id == '0000')
        {
            // Looking up everybody
            $data['user_focus_name'] = 'all users';
        }
            
        else 
        {
            // Looking up a particular user
            $user_focus = $this->site_visits_model->lookup_user_details($user_id); 
            $data['user_focus_name'] = "$user_focus->first_name $user_focus->last_name";
        }
      
        
        $data['date_range'] = $date_range;
        $data['recent_visits'] = $this->site_visits_model->get_visits($user_id, $start_date, $end_date, $location_id, $cluster);
        $data['cluster'] = $cluster;
        $data['user_type'] = $user_type;
        $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
        $data['user_id'] = $user_id;
        
        $this->load->view('templates/header', $data);
        $this->load->view('site_visits/history_options_menu', $data);
	$this->load->view('site_visits/show_history', $data);
	$this->load->view('templates/footer');        
    }
    
    public function show_last_visit() {
        $this->load->helper('form');
        
        $user_id = $this->session->userdata('userid');
        $user_type = $this->session->userdata('user_type');
        $cluster = $this->session->userdata('cluster');
        
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'last_visit';
        
        // Determine what list of schools to show
        
        if ($lookup_id = $this->input->post('user_id')) 
        // Only admins have access to the lookup form, so if we have a posting, then it is an admin
        { 
            $user_data = $this->site_visits_model->lookup_user_details($lookup_id); 
            
            
            if ($lookup_id == $user_id) 
            {
                // If admin is looking up himself, list all schools in his cluster
                // but show only his site visits
                $data['school_list'] = $this->site_visits_model->list_cluster_schools($cluster);
                $data['lookup_id'] = $user_id; 
            }
            
            elseif ($lookup_id == '0000') 
            {
                // '0000' means look up everyone's stuff, so we do the default admin lookup
                $data['school_list'] = $this->site_visits_model->list_cluster_schools($cluster);
            }
            
            elseif ($user_data->user_type == 'A')
            {
                //If the user is an admin and is looking up details on another admin
                // Lookup the cluster for the other admin
                $cluster = $user_data->cluster; 
                $data['school_list'] = $this->site_visits_model->list_cluster_schools($cluster);
                $data['lookup_id'] = $lookup_id;
            }
                
            else    
            {
                // We assume the admin is looking up an individual tech
                $data['school_list'] = $this->site_visits_model->list_assigned_schools($lookup_id);
                $data['lookup_id'] = $lookup_id;
            }
        }
        
        elseif ($user_type == 'A')
        {
            // If no post data, then give the administrator the cluster summary
            $data['school_list'] = $this->site_visits_model->list_cluster_schools($cluster); 
        }
        
        else 
        {
            // Everyone else sees only their school list.
            $data['school_list'] = $this->site_visits_model->list_assigned_schools($user_id);
            $data['lookup_id'] = $user_id;
        }
        
        // Identify the person who is subject of this lookup
        if (isset($data['lookup_id'])) {
            $user_focus = $this->site_visits_model->lookup_user_details($data['lookup_id']); 
            $data['user_focus_name'] = "$user_focus->first_name $user_focus->last_name";
        }
        else {
            $data['user_focus_name'] = 'all users';
        }
        
   // Call the views
        $this->load->view('templates/header', $data);
        if ($user_type == 'A') {
            $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
            $this->load->view('site_visits/last_visit_submenu', $data);
        }
        $this->load->view('site_visits/last_visit_report', $data);
        $this->load->view('templates/footer');
        
    }
 
    public function show_time_between_visits()
    {
        $this->load->helper('form');
        
        $user_id = $this->session->userdata('userid');
        $user_type = $this->session->userdata('user_type');
        $cluster = $this->session->userdata('cluster');
        
      
        // Get list of assigned schools for the submenu
        if ($user_type == 'A') {
            $data['assigned_schools'] = $this->site_visits_model->list_cluster_schools($cluster);
        }
        else {
            $data['assigned_schools'] = $this->site_visits_model->list_assigned_schools($user_id);            
        }
        
        // So, what are we doing here?
        if ($location_id = $this->input->post('location_id'))
        {
            // If a location has been selected, then we run the report
            // Otherwise we just display a blank page.  
            
            $show_results = TRUE;
            if ($user_lookup_id = $this->input->post('user_id'))
            {
              $user_id = $user_lookup_id; 
            }
            
            // Set the start date to be in the range selected on the form
            $date_range = $this->input->post('date_range');
            $start_date = date('Y-m-d', strtotime("$date_range days ago"));
            
            // Get the name of the location
            $location_details = $this->site_visits_model->get_location_details($location_id);
            $data['location_name'] = $location_details->name;
            
            $days_visited = $this->site_visits_model->get_visit_days($user_id, $location_id, $start_date);
                        
            // Create array list of dates and "business days since last date"
            $visit_lag_times = array();
            $weeks_visited = array();
            $counter = 0;
            $lag_days = 0; 
            $last_date = NULL;
            foreach ($days_visited as $visit )
            {
                $counter++;
                $current_date = $visit['date'];
                $week = date('W', strtotime($current_date));
                
                //$weeks_visited[] = $week; 
                
                $visit_lag_times[$counter]['visit_date'] = $current_date;
                
                $visit_lag_times[$counter]['week'] = $week;
                
                $lag_time = $this->site_visits_model->calculate_business_days_between_dates($last_date, $current_date);
                $visit_lag_times[$counter]['lag_time'] = $lag_time;
                
                // calculate average lag time
                $lag_days += $lag_time; 
                
                // reset the date for comparison on next trip through the cycle
                $last_date = $current_date;
            }
           
            $data['visit_lag_times'] = $visit_lag_times;
            $data['average_lag_time'] = round($lag_days / ($counter - 1)); //Subtract one because first entry has no lag time
            
            //TO DO:  Figure out weeks not visited
            //$data['weeks_visited'] = $weeks_visited;
            
        }
        
        else 
        {
            $show_results = FALSE;
        }

        // Package data to pass to the views
        $data['user_id'] = $user_id;
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'reports'; 
        $data['user_type'] = $user_type;
        $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
        $data['sub_menu'] = 'templates/reports_submenu';
        
        
         // Call the views
        $this->load->view('templates/header', $data);        
        $this->load->view('site_visits/time_between_visits_submenu', $data);
        if ($show_results) 
        {
            $this->load->view('site_visits/time_between_visits', $data);
        }
        
        else
        {
            $this->load->view('site_visits/selection_note');
        }
        $this->load->view('templates/footer');
        
        
    }
    
    public function show_man_hours() {
        $this->load->helper('form');
        
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'reports';
        $data['sub_menu'] = 'templates/reports_submenu';
        
        $user_id = $this->session->userdata('userid');
        $user_type = $this->session->userdata('user_type');
        $data['user_type'] = $user_type;
        $cluster = $this->session->userdata('cluster');
        
       // Determine the earliest date for this summary
        if ($date_diff = $this->input->post('date_range')) {
            //if user entered a date range, $date_limit accordingly
            $end_date = strtotime("-$date_diff days");
        }
        
        else 
        {
            $end_date =  strtotime('-7 days');
        }
        
        $date_limit = date('Y-m-d', $end_date);
        $data['date_limit'] = $date_limit;
        
        // Determine which list of schools to show
        if ($lookup_id = $this->input->post('user_id')) 
        // Only admins have access to the user lookup form, so if we have a posting, then it is an admin
        { 
            $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
            
            if ($lookup_id == $user_id) 
            {
                // If admin is looking up himself, list all schools in his cluster
                // but show only his site visits
                $data['school_list'] = $this->site_visits_model->list_visited_schools($user_id, $date_limit);
                $data['lookup_id'] = $user_id; 
            }
            
            elseif ($lookup_id == '0000') 
            {
                // '0000' means look up everyone's stuff, so we do the default admin lookup
                $data['school_list'] = $this->site_visits_model->list_cluster_schools($cluster);
            }
            
            else 
            {
                // We assume the admin is looking up an individual tech
                $data['school_list'] = $this->site_visits_model->list_visited_schools($lookup_id, $date_limit);
                $data['lookup_id'] = $lookup_id;
            }
        }
        
        elseif ($user_type == 'A')
        {
            // If no post data, then give the administrator the cluster summary
            $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
            $data['school_list'] = $this->site_visits_model->list_cluster_schools($cluster); 
        }
        
        else 
        {
            // Everyone else sees only the schools they have visited.
            $data['school_list'] = $this->site_visits_model->list_visited_schools($user_id, $date_limit);
            $data['lookup_id'] = $user_id;
        }
        // ----------  END SCHOOL LIST ------------------------------------------------------------
        
        // Identify the person who is subject of this lookup
        if (isset($data['lookup_id'])) {
            $user_focus = $this->site_visits_model->lookup_user_details($data['lookup_id']); 
            $data['user_focus_name'] = "$user_focus->first_name $user_focus->last_name";
        }
        else {
            $data['user_focus_name'] = 'all users';
        }
        
        
   // Call the view
        $this->load->view('templates/header', $data);
        $this->load->view('site_visits/man_hours_submenu', $data);
        $this->load->view('site_visits/man_hours_report', $data);
        $this->load->view('templates/footer');
        
    }
  
    public function show_mileage() {
        // Display the total daily mileage for a given month
        
        // Load helpers
        $this->load->helper('form');
        
        // Capture user information
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'reports';
        $data['sub_menu'] = 'templates/reports_submenu';
        
        $data['user_type'] = $this->session->userdata('user_type');
        $cluster = $this->session->userdata('cluster');
        
        // Set user_id
        if ($user_id = $this->input->post('user_id')) 
        {
            $user_id = $this->input->post('user_id');
        }
        
        else 
        {
            $user_id = $this->session->userdata('userid');
        }
        
        // Set start_date and end_date
        if ($start_date = $this->input->post('start_date')) 
        {
            $start_date = $this->input->post('start_date');
            $start_date_timestamp = strtotime($start_date);
            $end_date = date('Y-m-d',strtotime('-1 minute',strtotime('+1 month',$start_date_timestamp)));
        }
        
        else 
        {
            $start_date = date('Y-m-d', strtotime("first day of this month"));
            $end_date = date('Y-m-d', strtotime("last day of this month"));
        }
        
        // Database calls
        $data['mileage_array'] = $this->site_visits_model->get_mileage_report($user_id, $start_date, $end_date);
        $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
        
        // Get user name
        $user_focus = $this->site_visits_model->lookup_user_details($user_id); 
        $data['user_focus_name'] = "$user_focus->first_name $user_focus->last_name";
        
        // Make data available to the view
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['selected_user_id'] = $user_id;
        
        
         // Call the views
        $this->load->view('templates/header', $data);
        $this->load->view('site_visits/mileage_submenu', $data);
        $this->load->view('site_visits/mileage_report', $data);
        $this->load->view('templates/footer');
        
        
    }
 
    public function show_calendar($start_date = NULL) {
        // Display a month calendar summarizing visits
               
        // Load helpers
        $this->load->helper('form');
        
        // Capture user information
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'calendar';
        $data['user_type'] = $this->session->userdata('user_type');
        
                
        // Set user_id
        if ($this->input->get('user_id'))
        {
            $user_id = $this->input->get('user_id');
        }
        
        elseif ($this->input->post('user_id'))
        {
            $user_id = $this->input->post('user_id');
        }
        
        else 
        {
            $user_id = $this->session->userdata('userid');
        }
        $data['selected_user_id'] = $user_id;
        
        // Set start_date and end_date
        if ($start_date) 
        {
            //$start_date = $this->input->post('start_date');
            $start_date_timestamp = strtotime($start_date);
            $end_date = date('Y-m-d',strtotime('-1 minute',strtotime('+1 month',$start_date_timestamp)));
        }
        
        else 
        {
            $start_date = date('Y-m-d', strtotime("first day of this month"));
            $start_date_timestamp = strtotime($start_date);
            $end_date = date('Y-m-d', strtotime("last day of this month"));
        }
               
        // Links to next and previous month
        $next_month_date = date('Y-m-d', strtotime('+1 month',$start_date_timestamp));
        $last_month_date = date('Y-m-d', strtotime('-1 month',$start_date_timestamp));
        
        $data['next_month_url'] = site_url('site_visits/show_calendar').'/'.$next_month_date."/?user_id=$user_id";
        $data['last_month_url'] = site_url('site_visits/show_calendar').'/'.$last_month_date."/?user_id=$user_id";

        // Get list of cluster users for admin submenu
        if ($data['user_type'] == 'A')
        {
          $cluster = $this->session->userdata('cluster');
          $data['cluster_users'] = $this->site_visits_model->list_cluster_users($cluster);
        }
        
        // Get Data for Calendar Display
        $visit_data = $this->site_visits_model->get_visits($user_id, $start_date, $end_date);
            
        $calendar_data = array();
        foreach ($visit_data as $visit_detail) {
            $day = substr($visit_detail['date'], -2);
            if (substr($day,-2, 1) == '0') $day = substr($day, -1);
            if (array_key_exists($day, $calendar_data))
            {
                if ($visit_detail['location_id']=='9900')
                {
                    $calendar_data[$day] .= '<div class="leave_text">'.$visit_detail['leave_type']."</div>";
                }
                else
                {
                    $calendar_data[$day] .= $visit_detail['location_name']."<br />";
                }
            }
            
            else 
            {
                if ($visit_detail['location_id']=='9900')
                {
                    $calendar_data[$day] = '<div class="leave_text">'.$visit_detail['leave_type']."</div>";
                }
                else
                {
                    $calendar_data[$day] = $visit_detail['location_name']."<br />";
                }
            }
        }
        
        $data['calendar_data'] = $calendar_data;
        $data['calendar_month'] = date('m', $start_date_timestamp);
        $data['calendar_year'] = date('Y', $start_date_timestamp);
        
        // Set calendar preferences
        $calendar_prefs = array(
                'day_type' => 'long',
                'month_type' => 'long',
                'show_next_prev' => TRUE, 
            );
        $calendar_prefs['template'] = $this->load->view('templates/calendar', $data, TRUE);
        $this->load->library('calendar', $calendar_prefs);
        
        // Call the views
        $this->load->view('templates/header', $data);
        if ($data['user_type'] == 'A')
        {
            $this->load->view('site_visits/calendar_submenu', $data);
        }
        $this->load->view('site_visits/calendar_view', $data);
        $this->load->view('templates/footer');
        
    }
    
    public function show_reports_menu()
        {
          // Set $data for the view
        $user_id = $this->session->userdata('userid');
        $user_type = $this->session->userdata('user_type');
        $cluster = $this->session->userdata('cluster');
        
        $data['username'] = $this->session->userdata('username');
        $data['highlighted'] = 'reports';
        $data['sub_menu'] = 'templates/reports_submenu';
        
        
        $this->load->view('templates/header', $data);
        $this->load->view('site_visits/reports_home', $data);
        $this->load->view('templates/footer');
           
    }
  

    
    private function check_isvalidated(){
		if(! $this->session->userdata('validated')){
			redirect('login');
		}
	}
        
    public function do_logout(){
        $this->session->sess_destroy();
        redirect('login');
    }
}

