<h2>Mileage Report: <?php echo date('F Y', strtotime($start_date))?></h2>
<p>
    Mileage from
  <?php 
        echo date('F j', strtotime($start_date));
        echo " to ". date('F j, Y', strtotime($end_date));
        echo " for $user_focus_name.";
    ?>
</p>

<table border="0">
    <tr>
        <th>Date</th>
        <th>From Location</th>
        <th>To Locations</th>
        <th>Mileage</th>
    </tr>
    
    <?php $total_miles_for_month = 0; ?>
    <?php foreach ($mileage_array as $daily_mileage):
    // mileage_array: date, daily_miles, locations
        
        $total_miles_for_month += $daily_mileage['daily_miles'];
        // Separate all locations into first site, then other sites as a group
        $locations_array = explode(",", $daily_mileage['locations']); 
        $first_location = $locations_array[0];
        $num_locations = count($locations_array);
        if ($num_locations > 1)
        {   
            // If multiple locations, remove the first site and the ", ", then 
            // set $other_locations as the rest of the string
            $skip_length = strlen($first_location)+2;
            $other_locations = substr($daily_mileage['locations'], $skip_length);
        }
        else 
        {
            $other_locations = "&nbsp;";
        }
     ?>
    <tr>
         <td class="list_view">
            <?php 
            echo substr($daily_mileage['date'], 5)."-";
            echo substr($daily_mileage['date'],0, 4); 
            ?>
        </td>

        <td class="list_view">
            <?php echo $first_location; ?>
        </td>        

        <td class="list_view">
            <?php echo $other_locations; ?>
        </td>

        <td class="list_view" style="text-align:right">
            <?php echo $daily_mileage['daily_miles']; ?>
        </td>
    </tr>
   <?php endforeach; ?>

    <tr>
        <td>
            
        </td>
        <td>
            &nbsp;
        </td>
        <td style="text-align: right; font-weight: bold">
            Total Miles:
        </td>
        <td style="text-align: right; font-weight: bold">
            <?php echo $total_miles_for_month; ?>
        </td>
    </tr>
    
        <tr>
        <td>
            
        </td>
        <td>
            &nbsp;
        </td>
        <td style="text-align: right; font-weight: bold">
            Reimbursement: 
        </td>
        <td style="text-align: right; font-weight: bold">
            <?php 
            setlocale(LC_MONETARY, 'en_US');
            
            echo money_format('%.2n', $total_miles_for_month * 0.55); ?>
        </td>
    </tr>
</table>