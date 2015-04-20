<h2>Entry Form</h2>

<?php echo validation_errors(); ?>

<!-- Javascript -->
<script type="text/javascript">
    function validateVisitForm()
     {
    // SCHOOL CHECK - Check if they selected the "Select School" option
    var school = document.forms["visit_form"]["location_id"].value;
    if (school == '0000')
        {
            alert("INDECISION ALERT: \n I know there are lots of choices, but you need to pick a school.");
            return false;
        }

    // TIME CHECK - Check end time is AFTER start time
     var start_hour = document.forms["visit_form"]["start_hour"].value;
     var start_min = document.forms["visit_form"]["start_minute"].value;
     var end_hour = document.forms["visit_form"]["end_hour"].value;
     var end_min = document.forms["visit_form"]["end_minute"].value;

    if ((end_hour < start_hour) ||(end_hour == start_hour && end_min < start_min))
        {
            alert("TIME TRAVEL ALERT: \n Did you really finish your work before you started it?");
            return false;
        }

     // SIGNATURE CHECK - Check for Signature
     var sig=document.forms["visit_form"]["user_signature"].value;
      if (sig==null || sig=="")
       {
       alert("NOT REALLY ALERT: \n Don't forget to sign your name to the end of this day.");
       return false;
       }
       
     // MILEAGE CHECK - numbers and decimal only   
     var miles = document.forms["visit_form"]["mileage"].value;
     if (isNaN(miles))
         {
            alert("TRAVEL ALERT: \n Your mileage doesn't look like a number to me.  One of us may need glasses.");
            return false; 
         }
     
     }
      function validateRadio(radios)
    {
        for (i = 0; i < radios.length; ++i)
            {
                if (radios[i].checked) return true;
            }
        return false;
    }
     
     function validateLeaveForm()
     {
    // TIME CHECK - Check end time is AFTER start time
     var start_hour = document.forms["leave_form"]["start_hour"].value;
     var start_min = document.forms["leave_form"]["start_minute"].value;
     var end_hour = document.forms["leave_form"]["end_hour"].value;
     var end_min = document.forms["leave_form"]["end_minute"].value;

    if ((end_hour < start_hour) ||(end_hour == start_hour && end_min < start_min))
        {
            alert("TIME TRAVEL ALERT: \n Did you really finish your work before you started it?");
            return false;
        }
        
     // LEAVE SELECT CHECK
      if (!validateRadio(document.forms["leave_form"]["leave_type"]))
        {
            alert("LEAVE TYPE: \n  Pick a leave.  Any leave.");
            return false;
        }

     // SIGNATURE CHECK - Check for Signature
     var sig=document.forms["leave_form"]["user_signature"].value;
      if (sig==null || sig=="")
       {
       alert("NOT REALLY ALERT: \n Don't forget to sign your name to the end of this day.");
       return false;
       }
       

     }
 </script>

 <!-- JQuery to hide forms -->
 <script>
    $(document).ready(function() {
        $("<?php if (isset($visit_data) && $visit_data->location_id =='9900') echo "#form1"; else echo "#form2"; ?>").hide();
      //$("#visit_date").hide();
        
   $("input[name$='display_form']").click(function() {
       var choice = $(this).val();
       
       $("div.jquery_box").hide(400);
       $("#form" + choice).show(400);
   });
   
     });
        
 </script>       
 
<?php
  // Getting the date to use in the DatePicker UI

                if (isset($visit_data))
                {
                    $visit_date = $visit_data->date;
                    $display_date = date('l, F j, Y', strtotime($visit_date));
                }
                else
                {  
                    $visit_date = date('Y-m-d');
                    $display_date = date('l, F j, Y');
                }
?>   
             
     
 <script>
     // Cool DatePicker calendar tool
     //---- DATE PICKER ------------------------------------
  $(function() {
      $("#calendar_display").datepicker({
          defaultDate: <?php echo $visit_date; ?>,
          altField: "#visit_date",
          altFormat: 'yy-mm-dd', 
          dateFormat: 'DD, MM d, yy'
            });
            
        $("#calendar_display2").datepicker({
          defaultDate: <?php echo $visit_date; ?>,
          altField: "#leave_date",
          altFormat: 'yy-mm-dd', 
          dateFormat: 'DD, MM d, yy'
            });
  })      
   
</script>

<?php
// <editor-fold defaultstate="collapsed" desc="Time Selectors">
// TIME SELECTORS
                                 
                     $hour_options = array(
                        '05' => 'AM 5',
                        '06' => 'AM 6',
                        '07' => 'AM 7',
                        '08' => 'AM 08',
                        '09' => 'AM 09',
                        '10' => 'AM 10',
                        '11' => 'AM 11',
                        '12' => '12',
                        '13' => 'PM 1',
                        '14' => 'PM 2', 
                        '15' => 'PM 3',
                        '16' => 'PM 4',
                        '17' => 'PM 5', 
                        '18' => 'PM 6',
                        '19' => 'PM 7',
                        '20' => 'PM 8'
                    );
                    $minute_options = array(
                        '00' => '00',
                        '05' => '05',
                        '10' => '10',
                        '15' => '15',
                        '20' => '20',
                        '25' => '25',
                        '30' => '30',
                        '35' => '35',
                        '40' => '40',
                        '45' => '45',
                        '50' => '50',
                        '55' => '55'
                    );
                    
               
                // START TIME    
                if (isset($visit_data)) {
                    $start_hour = substr($visit_data->start_time, 0, 2);
                    //echo "<p>Hour: $start_hour</p>";
                }
                else {
                    $start_hour = '07';
                }
                
                if (isset($visit_data)) {
                    $start_minute = substr($visit_data->start_time, 3, 2);
                    //echo "<p>Minute: $start_minute</p>";
                }
                else {
                    $start_minute = '45';
                }
                
                
                //END TIME
                
                 if (isset($visit_data)) {
                    $end_hour = substr($visit_data->end_time, 0, 2);
                    //echo "<p>Hour: $end_hour</p>";
                }
                else {
                    $end_hour = '15';
                }
                
                if (isset($visit_data)) {
                    $end_minute = substr($visit_data->end_time, 3, 2);
                    //echo "<p>Minute: $end_minute</p>";
                }
                else {
                    $end_minute = '45';
                }
                
                // </editor-fold>                               
?>

<!-- FORM SELECTION TABLE -->
<table border="0">
    <tr>
        <td class="form_label">
            Entry Type
        </td>
        <td>
             <div id="radio_group">
             <input type="radio" name="display_form" value="1" 
                 <?php if (!isset($visit_data) || $visit_data->location_id  != '9900') echo "checked"?> />Visit &nbsp;
            <input type="radio" name="display_form" value="2" 
                <?php if (isset($visit_data) && $visit_data->location_id =='9900') echo "checked";?> />Leave
 </div>

        </td>
</table>
 


 <?php
    // <editor-fold defaultstate="collapsed" desc="Visit Form">
    // -------------------------------------------------------------------------------
    // VISIT FORM
 ?>
<div class="jquery_box" id="form1">
    <?php 

    $form_attributes = array('id'=>'visit_form', 'name'=>'visit_form', 'onsubmit'=> 'return validateVisitForm()');
    
    // Determine if the form is being used to edit a visit or create a new one
    if (isset($visit_data)) {
        $form_link = 'site_visits/edit_visit/'.$visit_data->id;
        echo form_open($form_link, $form_attributes);
    }
    else {
        echo form_open('site_visits/create_entry', $form_attributes); 
    }
?>
    
    <!-- HIDDEN INPUTS ----------------------------------------- -->
	<input type="hidden" name="user_id" value="<?php echo $this->session->userdata('userid'); ?>" />
        <?php if(isset($visit_data)) {
          echo '<input type="hidden" name="id" value="'.$visit_data->id.'" />';  
        }
        ?>
        
        <input type="hidden" name="leave_type" value="NULL">
        
   <table border="0">
       
        <!-- LOCATION --> 
        <tr>
            <td class="form_label">
                <label for="location_id">Location</label>
            </td>
            <td>
                <select name="location_id">
                <option value='0000'>- Select Location -</option>
                <?php 
                if ($user_type != 'A'){
                    foreach ($assigned_schools as $school){
                        echo '<option value="'.$school['school_id'].'">';
                        echo $school['school_name'];
                        echo '</option>';
                    }
                    echo '<option value="9000">@ Team Meeting</option>';
                    echo '<option disabled>~~~~~~~~~~~~~~~~~~~~</option>';
                }

                foreach ($cluster_schools as $school) {
                    echo '<option value="'.$school['school_id'].'" ';
                    if (isset($visit_data)) 
                    {
                        if ($school['school_id'] == $visit_data->location_id) echo "selected ";
                    }
                    echo '>';
                    echo $school['school_name'];
                    echo '</option>';
                }
                
                echo '<option value="9999" ';
                if (isset($visit_data)) 
                    {
                        if ($visit_data->location_id == '9999') echo "selected ";
                    }
                echo '>- Other -</option>';
                ?>
                </select>
                <br />
            </td>
        </tr>
        
        <!-- DATE -->
       
        <tr>
            <td class="form_label">
                Date
            </td>
            <td>
                <input type="text" id="calendar_display" value="<?php echo $display_date; ?>">
                <input type="hidden" id="visit_date" name="visit_date" value="<?php echo $visit_date;?>">
              
            </td>
        </tr>
        
        <!-- START TIME -->
        

        <tr>
            <td class="form_label">
                <label for="time_in">Start Time</label>
            </td>
            <td>
                <?php 
                echo form_dropdown('start_hour', $hour_options, $start_hour);
                echo " : ";
                echo form_dropdown('start_minute', $minute_options, $start_minute);
                ?>
            </td>
        </tr>
        
        <!-- END TIME -->
        <tr>
            <td class="form_label">
                <label for="time_out">End Time</label>
            </td>
            <td>
                <?php 
                echo form_dropdown('end_hour', $hour_options, $end_hour);
                echo " : ";
                echo form_dropdown('end_minute', $minute_options, $end_minute);
                ?>
            </td>
        </tr>
        
        <!-- NOTES -->
        <tr>
            <td class="form_label"> 
                <label for="notes">Notes</label>
            </td>
            <td>
                 <textarea name="notes" rows="5" cols="50"><?php if (isset($visit_data)) echo $visit_data->notes; ?></textarea>
            </td>
        </tr>
        
        <!-- MILEAGE -->
        <tr>
            <td class="form_label"> 
                <label for="mileage">Mileage</label>
            </td>
            <td>
                 <input type="text" size="4" maxlength="4" name="mileage"
                     <?php if (isset($visit_data)) echo 'value="'.$visit_data->mileage.'"'; ?>
                 > miles
            </td>
        </tr>
        <!-- SIGNATURE -->    
        <tr>
            <td class="form_label">
                <label for="user_signature">Signature</label>
            </td>
            <td>
                By entering my initials below I affirm that I was working<br /> 
                at the location listed above at the times noted on this form.
                <br />
                 <input type="text" name="user_signature" size="3" maxlength="3">
            </td>
        </tr>
        
        <!-- SUBMIT -->
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <br /><br />
                 <input type="submit" name="submit" value="Record Site Visit" />
            </td>
        </tr>
        
   </table>
   
</form>
<?php
// </editor-fold>
    ?>
</div>

<?php
// --------------------------------------------------------------------------------------
// LEAVE FORM
// <editor-fold>
?>
<div class="jquery_box" id="form2">
    <?php
    $leave_form_attributes = array('id'=>'leave_form', 'name'=>'leave_form', 'onsubmit'=> 'return validateLeaveForm()');
    
    // Determine if the form is being used to edit a visit or create a new one
    if (isset($visit_data)) {
        $form_link = 'site_visits/edit_visit/'.$visit_data->id;
        echo form_open($form_link, $leave_form_attributes);
    }
    else {
        echo form_open('site_visits/create_entry', $leave_form_attributes); 
    }
    ?>
 <table border="0">
     
     <!-- HIDDEN INPUTS ----------------------------------------- -->
     <input type="hidden" name="user_id" value="<?php echo $this->session->userdata('userid'); ?>" />
        <?php if(isset($visit_data)) {
          echo '<input type="hidden" name="id" value="'.$visit_data->id.'" />';  
        }
        ?>
        <input type="hidden" name="location_id" value="9900">        
     
        <!-- DATE -->
        <tr>
            <td class="form_label">
                <label for="date">Date</label>
            </td>
            <td>
                
                <input type="text" id="calendar_display2" value="<?php echo $display_date; ?>">
                <input type="hidden" id="leave_date" name="visit_date" value="<?php echo $visit_date;?>">
            </td>
        </tr>
        
        <!-- START TIME -->
         <tr>
            <td class="form_label">
                <label for="time_in">Start Time</label>
            </td>
            <td>
                <?php 
                echo form_dropdown('start_hour', $hour_options, $start_hour);
                echo " : ";
                echo form_dropdown('start_minute', $minute_options, $start_minute);
                ?>
            </td>
        </tr>
        
        <!-- END TIME -->
        <tr>
            <td class="form_label">
                <label for="time_out">End Time</label>
            </td>
            <td>
                <?php 
                echo form_dropdown('end_hour', $hour_options, $end_hour);
                echo " : ";
                echo form_dropdown('end_minute', $minute_options, $end_minute);
                ?>
            </td>
        </tr>
        
        <!-- LEAVE TYPE -->
        <tr> 
            <td class="form_label">
                Type
            </td>
            <td>
                <input type="radio" name="leave_type" value="Vacation"
                       <?php if(isset($visit_data) && $visit_data->leave_type == "Vacation") echo "checked"?> >Vacation<br />
                <input type="radio" name="leave_type" value="Sick Leave"
                       <?php if(isset($visit_data) && $visit_data->leave_type == "Sick Leave") echo "checked"?> >Sick Leave<br />
                <input type="radio" name="leave_type" value="Personal Leave"
                       <?php if(isset($visit_data) && $visit_data->leave_type == "Personal Leave") echo "checked"?> >Personal Leave<br />
                <input type="radio" name="leave_type" value="Comp Time Off"
                       <?php if(isset($visit_data) && $visit_data->leave_type == "Comp Time Off") echo "checked"?> >Comp Time<br />
                <input type="radio" name="leave_type" value="Other Leave"
                       <?php if(isset($visit_data) && $visit_data->leave_type == "Other Leave") echo "checked"?> >Other<br />
            </td>
        </tr>
        
        <!-- NOTES -->
        <tr> 
            <td class="form_label">
                Note
            </td>
            <td>
                <textarea name="notes" rows="2" cols="50"><?php if (isset($visit_data)) echo $visit_data->notes; ?></textarea>
            </td>
        </tr>
        
        <!-- SIGNATURE -->
        <tr>
            <td class="form_label">
                Signature
            </td>
            
            <td>
                By entering my initials below I affirm that the information I am entering<br /> 
                is true and accurate to the best of my knowledge.
                <br />
                 <input type="text" name="user_signature" size="3" maxlength="3">
            </td>
        </tr>
         
        <tr>
            <td>
                &nbsp;
            </td>
            <td>
                <br /><br />
                 <input type="submit" name="submit" value="Record Leave" />
            </td>
        </tr>
 </table>
</form>
</div>



<?php
// </editor-fold>
?>
