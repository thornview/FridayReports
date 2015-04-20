<div class="side_menu">
   <?php
    $form_attributes = array('name' => 'results_submenu', 'onchange' => 'document.results_submenu.submit()');
        echo form_open('survey/show_average_scores', $form_attributes); 
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