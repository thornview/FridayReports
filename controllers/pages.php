<?php
class Pages extends CI_Controller {
	public function view($page = 'home')
	{
            $this->load->helper('url');
                
                if ($page == 'home')
                {
                    redirect('login');
                }
                
                elseif (file_exists('application/views/pages/'.$page.'.php'))
                {
                    $this->load->library('session');
                    $data['username'] = $this->session->userdata('username');
                    $data['highlighted'] = 'none';
                    $this->load->helper('date');
                    $this->load->view('templates/header', $data);
                    $this->load->view("pages/$page");
                    $this->load->view('templates/footer');
                }
                
                //elseif(!file_exists('application/views/pages/'.$page.'.php'))
                else
		{
                    show_404();
		}
                
                

	}
}

