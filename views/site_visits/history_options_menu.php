<div class="side_menu">
   <?php 
        echo form_open('site_visits/show_history'); 
   ?>
    <p>
        Time:
        <?php
            $date_options = array(
                '7' => 'Week',
                '14' => '2 Weeks',
                '30' => 'Month',
                '60' => '2 Months',
                '365' => 'Year',
            );
            echo form_dropdown('date_range', $date_options);
        ?>
    </p>
    <p>
        Location: 
    <?
        $options = array();
        $options['0000'] = '- All Schools -'; 
        foreach ($assigned_schools as $school): 
            $school_id = $school['school_id'];
            $school_name = $school['school_name'];
            $options[$school_id] = $school_name;
        endforeach;
        $options['9999'] = 'Other';
        echo form_dropdown('location_id', $options); 
    ?>
    </p>    
    
    <?php 
    if ($user_type == 'A'): ?>
    <p>
        Select User: 
    <?php
        $user_option = array();
        $user_options['0000'] = "- All Users -";
        foreach ($cluster_users as $user): 
            $menu_user_id = $user['user_id'];
            $user_name = $user['first_name'].' '.$user['last_name'];
            $user_options[$menu_user_id] = $user_name;
        endforeach;
        
        echo form_dropdown('user_id', $user_options, $user_id)
    ?>
    </p> 
    <?php endif; 
    ?>
    <input type="submit" name="submit" value="Show History">
</form>
        
</div>