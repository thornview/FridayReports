<?php $this->load->helper('url'); ?> 

<ul class="menu">
            <li class="menu">
                <a href="<?php echo site_url('site_visits/create_entry'); ?>" 
                   class="<?php if($highlighted == 'create') echo 'menu_selected'; else echo 'menu';?>">
                    Entry Form
                </a>
            </li>
            <li class="menu">
                 <a href="<?php echo site_url('site_visits/show_history'); ?>" 
                    class="<?php if ($highlighted == 'show_history') echo 'menu_selected'; else echo 'menu';?>">
                    History
                </a>
            </li>
            <li class="menu">
                <a href="<?php echo site_url('site_visits/show_last_visit'); ?>" 
                    class="<?php if ($highlighted == 'last_visit') echo 'menu_selected'; else echo 'menu';?>">
                    Last Visit
                </a>    
            </li>

            <li class="menu">
                <a href="<?php echo site_url('site_visits/show_calendar'); ?>"
                   class="<?php if ($highlighted == 'calendar') echo 'menu_selected'; else echo 'menu'; ?>">
                Calendar
                </a>
            </li>
      
            <li class="menu">
               <a href="<?php echo site_url('site_visits/show_reports_menu'); ?>" 
                    class="<?php if ($highlighted == 'reports') echo 'menu_selected'; else echo 'menu';?>">
                    Reports
                </a>    
            </li>
            
            <li class="menu">
               <a href="<?php echo site_url('survey/show_report_menu'); ?>" 
                    class="<?php if ($highlighted == 'survey') echo 'menu_selected'; else echo 'menu';?>">
                    Surveys
                </a>    
            </li>
            
        </ul>
