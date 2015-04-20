<h2>Survey Averages by School</h2>


<table border="0">
    <tr>
        <th>School</th>
        <th>Known</th>
        <th>Courteous</th>
        <th>Prompt</th>
        <th>Competent</th>
        <th>Total Responses</th>
        <th>Comments</th>
    </tr>
    
<?php foreach ($result_averages as $school_data): ?>
    <tr>
        <td class="list_view">
            <?php echo $school_data['school_name']; ?>
        </td>
        <td class="list_view" style="text-align: right">
            <?php echo $school_data['known_score']; ?>
        </td>
        <td class="list_view" style="text-align: right">
            <?php echo $school_data['courteous_score']; ?>
        </td>
        <td class="list_view" style="text-align: right">
            <?php echo $school_data['prompt_score']; ?>
        </td>
        <td class="list_view" style="text-align: right">
            <?php echo $school_data['competent_score']; ?>
        </td>
        <td class="list_view" style="text-align: right; font-weight:bold">
            <?php echo $school_data['response_count']; ?>
        </td>
        <td class="list_view">
            <button id="hide_<?php echo $school_data['location_id']; ?>">Hide</button>
            <button id="show_<?php echo $school_data['location_id']; ?>">Show</button>
            <div id="comments_<?php echo $school_data['location_id']; ?>">
                <?php 
                    foreach ($result_comments as $user_comment)
                    {
                        if($user_comment['location_id'] == $school_data['location_id'])
                        {
                            if ($user_comment['comments'])
                            {
                            echo '<p>'.$user_comment['comments'].'<span class="signature"> - '.
                                    $user_comment['signature'].'</span></p>';
                            }
                        }
                    }
                ?>
            </div>
            
            <script>
                $(document).ready(function() {
                    $("#hide_<?php echo $school_data['location_id']; ?>").hide();
                    $("#comments_<?php echo $school_data['location_id']; ?>").hide();
                })
                $("#hide_<?php echo $school_data['location_id']; ?>").click(function() {
                    $("#comments_<?php echo $school_data['location_id']; ?>").hide(); 
                    $("#hide_<?php echo $school_data['location_id']; ?>").hide();
                    $("#show_<?php echo $school_data['location_id']; ?>").show();
                });
                $("#show_<?php echo $school_data['location_id']; ?>").click(function() {
                    $("#comments_<?php echo $school_data['location_id']; ?>").show();
                    $("#hide_<?php echo $school_data['location_id']; ?>").show();
                    $("#show_<?php echo $school_data['location_id']; ?>").hide();
                });
                
            </script>
        </td>
        
    </tr>

<?php endforeach; ?>
</table>