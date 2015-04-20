<h2>Time Between Visits to <?php echo $location_name; ?></h2>

<table>
    <tr>
        <th>Week</th>
        <th>Visit Date</th>
        <th>Days Since Last Visit</th>
    </tr>    

<?php
foreach ($visit_lag_times as $visit) 
{
    echo "<tr><td align='center'>".
            $visit['week'].
            "</td><td>".
            $visit['visit_date'].
            "</td><td align='center'>".
            $visit['lag_time'].
            "</td></tr>";
}
?>
</table>

<?php
echo "<p>Average Business Days Between Visits:  $average_lag_time</p>";
?>
