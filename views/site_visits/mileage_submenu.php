<div class="side_menu">
   <?php 
        echo form_open('site_visits/show_mileage'); 
   ?>
   
    <?php 
    // Show user-select form only to administators
    if ($user_type == 'A'): 
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
    <?php endif; ?>
    
    <?php    // Everyone sees the date selection form ?>
        <p>
        Month:
        <select name="start_date">
        <?php
            $this_month_timestamp = strtotime(date('m/01/y'));    
            
            for ($i = 0; $i <= 6; $i++) {
                $this_timestamp = strtotime("$i months ago", $this_month_timestamp);
                $this_date = date('Y-m-d', $this_timestamp);
                $this_month = date("F Y", $this_timestamp);
                echo "<option value = '$this_date'";
                if ($this_date == $start_date) echo " selected";
                echo ">$this_month</option> \n";
            }    
        ?>
        </select>
    </p>

    <input type="submit" name="submit" value="Mileage Report">
</form>
        
</div>