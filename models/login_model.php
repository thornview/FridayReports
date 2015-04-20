<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jorge Torres
 * Description: Login model class
 */
class Login_model extends CI_Model{
	function __construct(){
		parent::__construct();
                $this->load->database();
                $this->load->library('session');
	}
	
	public function validate(){
                $users_table = $this->db->dbprefix('users');
            
		// grab user input
		$username = $this->security->xss_clean($this->input->post('username'));
		$password = $this->security->xss_clean($this->input->post('password'));
		
		// Prep the query
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		
		// Run the query
		$query = $this->db->get($users_table);
		// Let's check if there are any results
		if($query->num_rows == 1)
		{
			// If there is a user, then create session data
			$row = $query->row();
			$data = array(
					'userid' => $row->id,
					'fname' => $row->first_name,
					'lname' => $row->last_name,
					'username' => $row->username,
                                        'user_type' => $row->user_type,
                                        'cluster' => $row->cluster,
					'validated' => true
					);
			$this->session->set_userdata($data);
			return true;
		}
		// If the previous process did not validate
		// then return false.
		return false;
	}
}
?>
