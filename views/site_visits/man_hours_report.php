<h2>Man-Hour Report</h2>
<p>
    Total man-hours spent at each school since 
    <?php echo date('F j, Y', strtotime($date_limit));
        echo " for $user_focus_name.";
    ?>
</p>

<table border="0">
    <tr>
        <th>Location</th>
        <th>Man Hours</th>
    </tr>
    
    <?php if ($school_list): ?>
    <?php foreach ($school_list as $school): ?>
    <tr>
        <td class="list_view">
            <?php echo $school['school_name']; ?>
        </td>
        <td class="list_view" style="text-align: right">
            <?php 
               
                // Get Man Hours from dbase
                if (isset($lookup_id))
                {
                    $time_query = $this->site_visits_model->calculate_man_hours($school['school_id'], $date_limit, $lookup_id);  
                }
                else
                {
                    $time_query = $this->site_visits_model->calculate_man_hours($school['school_id'], $date_limit);    
                }
                
               // Format the time
               if($time_query && $man_hours = $time_query->man_hours)
               {
                   if (substr($man_hours,0, 1 ) == '0')
                   {
                       echo substr($man_hours, 1, -3); 
                   }
                   else
                   {
                   echo substr($man_hours,0, -3);
                   }
               }
               else 
               {
                   echo "-";
               }
            ?>   
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php //else {echo "No schools";} ?>
</table>


