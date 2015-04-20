<div class="side_menu">
   <?php 
        echo form_open('site_visits/show_man_hours'); 
   ?>
   
    <?php 
    // Show user-select form only to administators
    if ($user_type == 'A'): 
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
        
        echo form_dropdown('user_id', $user_options);
    ?>
    </p> 
    <?php endif; ?>
    
    <?php    // Everyone sees the date selection form ?>
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

    <input type="submit" name="submit" value="Show Man Hours">
</form>
        
</div>
