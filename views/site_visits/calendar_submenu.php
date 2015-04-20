<div class="side_menu">
   <?php
    $form_attributes = array('name' => 'calendar_submenu', 'onchange' => 'document.calendar_submenu.submit()');
        echo form_open('site_visits/show_calendar', $form_attributes); 
   ?>
  
    <p>
        Select User: 
    <?php
        $user_option = array();
        foreach ($cluster_users as $user): 
            $user_id = $user['user_id'];
            $user_name = $user['first_name'].' '.$user['last_name'];
            $user_options[$user_id] = $user_name;
        endforeach;
        
        echo form_dropdown('user_id', $user_options, $selected_user_id);
    ?>
    </p> 

</form>
        
</div>