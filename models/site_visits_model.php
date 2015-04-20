<?php

class Site_visits_model extends CI_Model {
            
    
    public function __construct()
	{
	    $this->load->database();
	}

    public function list_assigned_schools($user_id = FALSE) 
    {
        // List all schools assigned to a given technician
        // $user_id required
        
        if ($user_id === FALSE) {
        }
        else {
            $assignments_table = $this->db->dbprefix('assignments');
            $locations_table = $this->db->dbprefix('locations');
            
            $query_text = "
                SELECT $locations_table.id as school_id, 
                    $locations_table.name as school_name, 
                    $locations_table.days_btwn_visits
                FROM $locations_table, 
                    $assignments_table
                WHERE $assignments_table.location_id = $locations_table.id
                AND $assignments_table.user_id = '$user_id'
                AND $locations_table.active = 'Y'
                ORDER BY school_name ASC"; 
            
            $query = $this->db->query($query_text);
            return $query->result_array();
        }
    }
    
    public function list_cluster_schools($cluster, $type_exclusion = NULL)
    {
        // returns list of all schools in a given cluster
        //$cluster required
        //if $type_exlusion is set, excludes certain locations based on type codes E, M, H and O.
        $locations_table = $this->db->dbprefix('locations');
        
        $query_text = "SELECT id as school_id, name as school_name, days_btwn_visits
            FROM $locations_table
            WHERE cluster = '$cluster'
            AND $locations_table.active = 'Y'";
        if ($type_exclusion) 
        {
            $query_text .= " AND type NOT LIKE '%$type_exclusion%' ";
        }
        
        $query_text .= " ORDER BY name"; 
        $query = $this->db->query($query_text);
        return $query->result_array();        
    }
    
    public function list_visited_schools($user_id = NULL, $date_limit = NULL)
    {
        //returns array of school IDs for each school TSS has visited in given time period
        // $user_id and $date_limit required.  $date_limit must be in date format
        
        $site_visits_table = $this->db->dbprefix('site_visits');
        $locations_table = $this->db->dbprefix('locations');
            
        $query_text = "
            SELECT DISTINCT $site_visits_table.location_id as school_id, $locations_table.name as school_name  
            FROM $site_visits_table, $locations_table
            WHERE $site_visits_table.user_id = $user_id
            AND $site_visits_table.date >= '$date_limit'
            AND $site_visits_table.location_id = $locations_table.id
            ORDER BY school_name ASC"; 

        $query = $this->db->query($query_text);
        return $query->result_array();
    }
    
    public function set_visit() 
    {
        // Inserts site visit into database
        
        $this->load->helper('url');
        
        
        // ----  SHOULD BE IN THE CONTROLLER ---
        $submitted_date = $this->input->post('visit_date');
        $submitted_start_time = $this->input->post('start_hour').':'.
                $this->input->post('start_minute');
        $submitted_end_time = $this->input->post('end_hour').':'.
                $this->input->post('end_minute');
        $submitted_mileage = round($this->input->post('mileage'), 1);
        
        // Manually calculating the time difference.  ((H2-H1)*60)+(M2-M1).  Format for time field
        $minutes_worked = (($this->input->post('end_hour') - $this->input->post('start_hour'))*60) + 
            ($this->input->post('end_minute') - $this->input->post('start_minute'));
 
        $time_hours_1 = floor($minutes_worked/60); 
        $time_hours = str_pad($time_hours_1, 2, "0", STR_PAD_LEFT);
        $time_minutes_1 = $minutes_worked % 60; 
        $time_minutes = str_pad($time_minutes_1, 2, "0", STR_PAD_LEFT); 
        $formatted_time = $time_hours.':'.$time_minutes;
        // ----  ABOVE SHOULD BE IN THE CONTROLLER ---
       
        $data = array(
            'user_id' => $this->input->post('user_id'),
	    'location_id' => $this->input->post('location_id'), 
	    'date' => $submitted_date, 
            'start_time' => $submitted_start_time, 
            'end_time' => $submitted_end_time, 
            'notes' => $this->input->post('notes'),
            'mileage' => $submitted_mileage,
            'user_signature' => $this->input->post('user_signature'),
            'time_worked' => $formatted_time,
            'leave_type' => $this->input->post('leave_type')
	);

	return $this->db->insert('site_visits', $data);
    }
       
    public function get_visits($user_id, $start_date, $end_date = NULL, $location_id = NULL, $cluster = NULL)
    {
        // Return array site_visits for given criteria
        // $user_id, $start_date and $end_date required
        
       $site_visits_table = $this->db->dbprefix('site_visits');
       $locations_table = $this->db->dbprefix('locations');
       $users_table = $this->db->dbprefix('users');
       
       $query_text = "
           SELECT 
                $site_visits_table.date AS date, 
                $site_visits_table.start_time AS start_time, 
                $site_visits_table.end_time AS end_time,
                $site_visits_table.notes AS notes,
                $site_visits_table.mileage as mileage,
                $site_visits_table.id AS visit_id,
                $site_visits_table.leave_type AS leave_type,    
                $users_table.first_name AS first_name, 
                $site_visits_table.location_id AS location_id,
                $locations_table.name AS location_name

            FROM $site_visits_table, $locations_table, $users_table
            WHERE 
                $site_visits_table.location_id = $locations_table.id
                AND $site_visits_table.user_id = $users_table.id 
                AND ";
       
       if ($user_id == '0000') {
           $query_text .= "$locations_table.cluster = '$cluster' "; //AND ";
       }
       else {
            $query_text .=" $site_visits_table.user_id = '$user_id' "; 
            //AND ";
       }
       if (isset($location_id) && $location_id != '0000') {
           $query_text .= " AND location_id = '$location_id' ";// AND ";
       }
       
       $query_text .= " AND $site_visits_table.date >= '$start_date' ";
       
        
       if ($end_date) 
       {
           $query_text .= " AND  $site_visits_table.date <= '$end_date'";
       }
       
       $query_text .= " ORDER BY date DESC, start_time";
       
       //echo "<p>$query_text</p>";
       $query = $this->db->query ($query_text);
       return $query->result_array();
    }

    public function get_visit_days($user_id, $location_id, $start_date)
    {
        // returns an array of all the days a technician has visited a particular site
        // Requires: $user_id and $location_id
        
        $site_visits_table = $this->db->dbprefix('site_visits');
        
        $query_text = "
            SELECT DISTINCT date, user_id, location_id
            FROM $site_visits_table
            WHERE user_id = $user_id
            AND location_id = $location_id
            AND date >= '$start_date'
            ORDER BY date ASC
        ";
        
       $query = $this->db->query($query_text);
       
       return $query->result_array();
    }
    
    public function get_location_details($location_id)
    {
        $locations_table = $this->db->dbprefix('locations');
        
        $query_text = "
            SELECT * 
            FROM $locations_table
            WHERE id = $location_id";
        
        $query = $this->db->query($query_text);
        
        return $query->row();    
    }
    
    public function calculate_last_visit($site_id = NULL, $user_id = NULL) {
        $site_visits_table = $this->db->dbprefix('site_visits');
        
        $query_text = "SELECT id, location_id, 
            MAX(date) AS last_visit, 
            DATEDIFF(CURRENT_DATE, max(date)) AS days_out
            FROM $site_visits_table
            WHERE location_id = '$site_id'";
        if ($user_id) {
            $query_text .= "AND user_id = '$user_id' ";
        }
        $query_text .= " LIMIT 1";
        $query = $this->db->query($query_text);
        return $query->row();
    }
    
    public function calculate_man_hours($site_id = NULL, $date_limit = NULL, $user_id = NULL) {
        // returns single row of total minutes at site
        // Requires: $site_id and $date_limit.  $date_limit must be in MySQL date format
        // $user_id is optional.  
        
        $site_visits_table = $this->db->dbprefix('site_visits');
         
        // query looks up man_hours for individual school only
        $query_text = "SELECT $site_visits_table.location_id, 
            SEC_TO_TIME(SUM(TIME_TO_SEC($site_visits_table.time_worked))) as man_hours
            FROM $site_visits_table
            
            WHERE 
               $site_visits_table.location_id = '$site_id' 
        ";
        // If there is a date limit, use it.  Date limit must be in date format.
        if ($date_limit)
        {
            $query_text .= " AND date >= '$date_limit'";
        }
        
        if ($user_id)
        {
            $query_text .= " AND $site_visits_table.user_id = '$user_id' ";
        }
        $query_text .= " GROUP BY $site_visits_table.location_id";
        
        //echo "<p>Query = $query_text</p>";
        $query = $this->db->query($query_text);
        return $query->row();
    }
    
    public function calculate_days_between_visits($user_id, $site_id)
    {
      // Unused function.  
       $query_text ="
           SELECT 
                name, 
                round(datediff( max( date ) , min( date ) ) / ( count( date ) -1 ))  as days_between
        FROM 
                (
             select distinct date, name, location_id 
             from 
                `friday_site_visits`, `friday_locations`
                WHERE 
                friday_site_visits.location_id = friday_locations.id
                AND
                user_id = '$user_id'
                AND 
                location_id = '$site_id'
             ) as dtable
        GROUP BY location_id
        ";
    }
    
    public function list_cluster_users($cluster = NULL, $user_type = NULL) {
        $users_table = $this->db->dbprefix('users');
        
        $query_text = "SELECT id as user_id, first_name, last_name, username 
            FROM $users_table
            WHERE cluster = $cluster
            AND active= 'Y'";
        if ($user_type) {
            $query_text .= " AND user_type ='$user_type'";
        }
        $query_text .= " ORDER BY last_name";
        $query = $this->db->query($query_text);
        return $query->result_array();
    }
    
    public function lookup_user_details ($user_id = NULL) {
        $users_table = $this->db->dbprefix('users');
        
        $query_text = "SELECT id as user_id, first_name, last_name, username, 
                user_type, cluster, signature, email as user_email
            FROM $users_table
            WHERE id = $user_id
            LIMIT 1";
        $query = $this->db->query($query_text);
        return $query->row();
    }
    
    public function delete_visit($visit_id) {
        $visit_table = $this->db->dbprefix('site_visits');
        $this->db->delete($visit_table, array('id'=>$visit_id));
    }
    
    public function retrieve_visit($visit_id) {
        $query = $this->db->get_where('site_visits', array('id' => $visit_id));
        return $query->row(); 
    }
    
    public function update_visit($visit_data) {
        //$visit_data is an array of the fields that need to be updated
        $visit_id = $visit_data['id'];
        $this->db->where('id', $visit_id);
        $this->db->update('site_visits', $visit_data);
    }
    
    public function get_mileage_report ($user_id, $start_date, $end_date) {
        // Returns an array of monthly total mileage for each date in given month
        // Requires $user_id, $month, and $year
        $site_visits_table = $this->db->dbprefix('site_visits');
        $locations_table = $this->db->dbprefix('locations');
        
        $query_text = "
            SELECT date, 
            SUM(mileage) as daily_miles, 
            GROUP_CONCAT($locations_table.abbreviation ORDER BY start_time ASC SEPARATOR ', ') as locations
            FROM $site_visits_table, $locations_table
            WHERE 
            $site_visits_table.location_id = $locations_table.id
            AND
            user_id = $user_id
            and date >= '$start_date'
            and date <= '$end_date'
            GROUP BY date";
         
        $query = $this->db->query ($query_text);
        return $query->result_array();
  
    }
   
   public function calculate_business_days_between_dates($start_date, $end_date)
    {
    // If there is a blank start date, return 0
        if (!$start_date)
        {
            return 0;
        }
     
     // Create date objects
    $datetime1 = new DateTime($start_date);
    $datetime2 = new DateTime($end_date);
    
    // Get the week number for each date
    $week1 = $datetime1->format('W');
    $week2 = $datetime2->format('W');
    
    // Calculate the total days different between the first and last date
    $interval = $datetime1->diff($datetime2);
    $days_between =  $interval->format('%a');
    
    // Subtract weekends from the total days
    $business_days = $days_between - (2*($week2-$week1));
    
    return $business_days;
    }
}
?>