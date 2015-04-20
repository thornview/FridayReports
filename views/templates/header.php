<!DOCTYPE html>

<?php $this->load->helper('url'); ?> 

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    
    <!--
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.2/themes/pepper-grinder/jquery-ui.css" />
    -->
    <script type="text/javascript" src="http://www.thornview.com/friday/scripts/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="http://www.thornview.com/friday/scripts/jquery-ui-1.10.2.custom.min.js"></script>
    <link rel="stylesheet" type="text/css"  href="http://www.thornview.com/friday/styles/style.css" />
    <link rel="stylesheet" type="text/css" href="http://www.thornview.com/friday/styles/pepper-grinder/jquery-ui-1.10.2.custom.min.css" />
    

	<title>The Friday Reports</title>
</head>
<body>
    <div class="user_info">
        <?php if(isset($username)) 
            {
            $logout_url = site_url('logout');
            echo $username.' | <a href="'.$logout_url.'" class="user_link">Log Out</a>';
            $time = time();
            echo '<div class="date">'.mdate('%l, %M %j', $time).'</div>';
            
            }
      ?>
    </div>	
    <div class="logo">
            <img src="<?php echo base_url('media/friday_report_logo.png'); ?>" 
                 alt="The Friday Reports" />
        </div>

    <div class="container">    
    <div class="menu">
        <?php 
        if(isset($username)) 
        {
        //$data['user_type'] = $user_type;
            $this->load->view('templates/menu');
        }
        ?>
        </div>
        <?php 
        // If a page is supposed to have a submenu, like the "reports" submenu, 
        // identify that submenu using "$data['sub_menu'] = 'templates/reports_submenu';" 
        // in the controller, then pass $data to the header when calling the view.  
        
        if(isset($sub_menu)){
            echo '<div class="sub_menu">';
            $this->load->view($sub_menu);
            echo '</div>';
        }
        ?>
        
        <div class="content">
