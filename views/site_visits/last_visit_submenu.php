<div class="side_menu">
   <?php 
        $form_attributes = array('name' => 'last_visit_submenu', 'onchange' => 'document.last_visit_submenu.submit()');
        echo form_open('site_visits/show_last_visit', $form_attributes); 
   ?>
    <p>
        Select User: 
    <?php
        $user_option = array();
        $user_options['0000'] = "- All Users -";
        foreach ($cluster_users as $user): 
            $user_id = $user['user_id'];
            $user_name = $user['first_name'].' '.$user['last_name'];
            $user_options[$user_id] = $user_name;
        endforeach;
        
        echo form_dropdown('user_id', $user_options)
    ?>
    </p> 
</form>
        
</div>

