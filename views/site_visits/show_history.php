<h2>History Report </h2>
<p>
    History since 
    <?php echo date('F j, Y', strtotime("-$date_range days")); 
     echo " for $user_focus_name.";
    ?>
</p>
<table border="0">
    <tr>
        <th>Date</th>
        <th>Location</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Notes</th>
        <th>Mileage</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
<?php foreach ($recent_visits as $visit_entry): ?>

<tr>
    <!-- DATE -->
    <td class="list_view" style="font-weight:bold">
        <?php  
        // If date already displayed for today, don't display it again on this line. 
        if (! isset($visit_date)) {
            $visit_date = $visit_entry['date'];
            echo date('D, M j',strtotime($visit_entry['date']));
        }
            
        elseif ($visit_date == $visit_entry['date']) {
            echo "&nbsp;";
        }

        else 
        {
            $visit_date = $visit_entry['date'];
            echo date('D, M j',strtotime($visit_entry['date']));
        }
    ?>
        
    </td>
    
    <!-- LOCATION -->
    <td class="list_view">
        <?php 
            if ($visit_entry['location_id'] == '9900')
            {
                echo '<div class="leave_text">'.$visit_entry['leave_type'].'</div>';
            }
            else
            {
            echo $visit_entry['location_name']; 
            }
        ?>
    </td>
    
    
    
    <!-- START TIME -->
    <td class="list_view" style="text-align: center">
        <?php 
            echo date('g:i', strtotime($visit_entry['start_time']));
        ?>
    </td>
    
    <!-- END TIME -->
    <td class="list_view" style="text-align: center">
        <?php 
            echo date('g:i', strtotime($visit_entry['end_time']));
        ?>
    </td>
    
    <!-- NOTES --> 
    <td class="list_view">
        <?php
           if ($user_type == 'A')
           {
               echo '<span class="name_tag">'.$visit_entry['first_name'].':  </span>';
           }
           echo $visit_entry['notes'];
        ?>
    </td>
    
    <!-- MILEAGE -->
    <td class="list_view" style="text-align: right">
        <?php
            if ($visit_entry['mileage'] == '0.0') 
            {
                echo "&nbsp;";
            }
            else 
            {
                echo $visit_entry['mileage'];    
            }     
        ?>
    </td>
        
    <!-- DELETE BUTTON -->
     <td class="list_view" style="width:25px; text-align: center">
         <a href="<?php echo site_url('site_visits/delete'); ?>/<?php echo $visit_entry['visit_id']; ?>" 
            class="delete_hover"></a>
     </td>
     
     <!-- EDIT BUTTON -->
     <td class="list_view" style="width:25px; text-align: center">
         <a href="<?php echo site_url('site_visits/edit'); ?>/<?php echo $visit_entry['visit_id']; ?>" 
               class="edit_hover"></a>
     </td>

<?php 
    // Reset the $visit_date to see if needs to be displayed on the next loop
    $visit_date = $visit_entry['date']; 
?>
<?php endforeach ?>
</table>