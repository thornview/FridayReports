<h2>Last Visit Report</h2>
<p>
   Last recorded school visit for 
    <?php echo $user_focus_name; ?>.

</p>
<table border="0">
    <tr>
        <th>Location</th>
        <th>Date Last Visit</th>
        <th>Days Since Last Visit</th>
    </tr>
    

<?php foreach ($school_list as $school): ?>
    <?php
    if (isset($lookup_id)) {
        $visit = $this->site_visits_model->calculate_last_visit($school['school_id'], $lookup_id);
    }
    else {
        $visit = $this->site_visits_model->calculate_last_visit($school['school_id']);
    }
    
    
    if ($visit->days_out >= ($school['days_btwn_visits'] * 2)  || !isset($visit->days_out)) {
        $class = 'warning';
    }
    elseif ($visit->days_out >= $school['days_btwn_visits']){
        $class = 'caution';
    }
    else {
        $class = 'clear';
    }
    ?>
    <tr class="<?php echo $class; ?>">
        <td class="list_view"><?php echo $school['school_name']?></td>
        <td class="list_view">
            <?php    
                if (isset($visit->last_visit)) {
                    $last_visit = $visit->last_visit; 
                    echo date('D, M j',strtotime($last_visit));
                }
                else {
                    echo "-";
                }
            ?>
        </td>
        <td class="list_view">

            <?php echo $visit->days_out; ?>
        </td>
       
    </tr>
<?php endforeach; ?> 
    
    
</table>

