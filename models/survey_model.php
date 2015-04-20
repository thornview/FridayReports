<?php

class Survey_model extends CI_Model {
            
    
    public function __construct()
	{
	    $this->load->database();
	}
       
        
     public function record_response()
     {
         $created_date = date('Y-m-d', strtotime('now'));
         
         $data = array(
	    'location_id' => $this->input->post('location_id'),
            'user_id' => $this->input->post('technician_id'),
	    'date' => $created_date, 
            'known' => $this->input->post('known'), 
            'courteous' => $this->input->post('courteous'), 
            'prompt' => $this->input->post('prompt'), 
            'competent' => $this->input->post('competent'),
            'comments' => $this->input->post('comments'), 
            'signature' => $this->input->post('signature'),
             'survey_set' => $this->input->post('survey_set')
	);

	return $this->db->insert('survey', $data);
     }
     
     public function get_school_response_count($survey_set, $cluster) 
     {
         // Returns count of total reponses for a survey
         // $survey_set and $cluster both required
         
         $survey_table = $this->db->dbprefix('survey');
         $locations_table = $this->db->dbprefix('locations');
         
            $query_text = "
                SELECT $locations_table.name as school_name, 
                    responses.response_count as response_count
                FROM $locations_table 
                        LEFT JOIN 
                        (SELECT
                        location_id, count(id) as response_count
                     FROM $survey_table
                     WHERE survey_set = '$survey_set'
                     GROUP BY location_id
                     ) as responses

                ON $locations_table.id = responses.location_id
                WHERE $locations_table.cluster = '$cluster'
                GROUP BY $locations_table.id
                ORDER BY school_name ASC
                "; 
            
            $query = $this->db->query($query_text);
            return $query->result_array();
     }
     
     public function get_averages_by_school($survey_set, $user = NULL)
     {
         // Returns a table with average scores for all schools owned by $user
         // Requires $survey_set and $user
         // For admin user who wants to see all his schools, $user should be "0000"
         //
         
         $survey_table = $this->db->dbprefix('survey');
         $locations_table = $this->db->dbprefix('locations');
         
         $query_text = "
            SELECT $locations_table.name as school_name,
            location_id,
            ROUND(AVG($survey_table.known), 2) as known_score, 
            ROUND(AVG($survey_table.courteous), 2) as courteous_score, 
            ROUND(AVG($survey_table.prompt),2) as prompt_score, 
            ROUND(AVG($survey_table.competent), 2) as competent_score, 
            COUNT(*) as response_count
            FROM
            $survey_table, $locations_table
            WHERE $survey_table.location_id = $locations_table.id 
            AND survey_set = '$survey_set'
            ";
          if ($user != '0000')
          {
              $query_text .= " AND $survey_table.user_id = '$user' ";
          }
          
         $query_text .= " GROUP BY location_id
             ORDER BY school_name";
          
        $query = $this->db->query($query_text);
        return $query->result_array();
     }
     
     public function get_comments_by_school($survey_set, $user = NULL)
     {
          $survey_table = $this->db->dbprefix('survey');
          $locations_table = $this->db->dbprefix('locations');
         
         $query_text = "
            SELECT 
            date,
            location_id, 
            user_id, 
            comments, 
            signature
            FROM
            $survey_table
            WHERE survey_set = '$survey_set' 
            ";
          if ($user != '0000')
          {
              $query_text .= " AND $survey_table.user_id = '$user' ";
          }
          
         $query_text .= " ORDER BY date";
          
        $query = $this->db->query($query_text);
        return $query->result_array();
     }
}

?>
