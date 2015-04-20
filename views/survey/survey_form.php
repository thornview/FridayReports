<!-- Javascript -->
<script type="text/javascript">
    function validateRadio(radios)
    {
        for (i = 0; i < radios.length; ++i)
            {
                if (radios[i].checked) return true;
            }
        return false;
    }
    function validateForm()
     {
    // SCHOOL CHECK - Check if they selected the "Select School" option
    var school = document.forms["survey_form"]["location_id"].value;
    if (school == '0000')
        {
            alert("QUESTION 1: \n Please select your school.");
            return false;
        }

    // Technician Check
    var technician = document.forms["survey_form"]["technician_id"].value;
    if (technician == '0000')
        {
            alert("QUESTION 2: \n  Please select the technician who services your school.");
            return false;
        }

    // KNOWN check
    if (!validateRadio(document.forms["survey_form"]["known"]))
        {
            alert("QUESTION 3: \n  Please indicate how well you know the technician.");
            return false;
        }

    // COURTEOUS check
    if (!validateRadio(document.forms["survey_form"]["courteous"]))
        {
            alert("QUESTION 4: \n  Please rate how courteous your technician is.");
            return false;
        }
   
       // PROMPT check
    if (!validateRadio(document.forms["survey_form"]["prompt"]))
        {
            alert("QUESTION 5: \n  Please rate how prompt your technician is.");
            return false;
        }
   
       // COMPETENT check
    if (!validateRadio(document.forms["survey_form"]["competent"]))
        {
            alert("QUESTION 6: \n  Please rate how competent your technician is.");
            return false;
        }
   
     
     }
 </script>
 
 
<h2>MNPS Technology Support Survey - Spring 2014</h2>

<!--
<p>
    It's time for Technology's year-end evaluation.  
    What did we do right this year, 
    and what do you hope we'll do better next year?
</p>
<p>
    Thank you for taking a moment to share your experiences with 
    Tech Support.  
</p>
<p>
    Sincerely, <br /><br />
    Bryce Embry <br />
    Cluster Technology Manager
</p>

-->

 <?php
 $form_attributes = array('id'=>'survey_form', 'name'=>'survey_form', 'onsubmit'=> 'return validateForm()');
 echo form_open('survey/record_response', $form_attributes);  
 ?>
 
 <?php
 //
 // RESET THIS SURVEY SET VALUE WHEN RE-USING THE SURVEY
 //
 $survey_set = "2014-A";
 echo '<input type="hidden" name="survey_set" value="'.$survey_set.'">';
 //
 //
 //
 ?>


           <p> 
               <div class="survey_question">
                  1. Which school do you work in primarily?
               </div>
                <select name="location_id">
                <option value='0000'>- Select School -</option>
                <?php 
                foreach ($cluster_schools as $school) {
                    echo '<option value="'.$school['school_id'].'"> ';
                    echo $school['school_name'];
                    echo '</option>';
                }
                
                echo '<option value="9999">- Other -</option>';
                ?>
                </select>
           </p>

           <p>
               <div class="survey_question">
                2. Who is your Technology Support Specialist (TSS)?
               </div>

                  <?php
                    $technician_options = array();
                    $technician_options['0000'] = '- Select Technician -';
                    
                    foreach ($cluster_technicians as $technician): 
                        $technician_id = $technician['user_id'];
                        $technician_name = $technician['first_name'].' '.$technician['last_name'];
                        $technician_options[$technician_id] = $technician_name;
                    endforeach;
                    
                    $technician_options['999'] = '- Not sure -';

                    echo form_dropdown('technician_id', $technician_options, '0000');
                    ?>
           </p>
           
           <table>
               <tr>
                   <td width="65%">Please mark how much you agree with each of the following statements. </td>
                   <td width="7%">Disagree Strongly</td>
                   <td width="7%" class="odd">Disagree</td>
                   <td width="7%">Neutral</td>
                   <td width="7%" class="odd">Agree</td>
                   <td width="7%">Agree Strongly</td>
               </tr>
               <tr>
                   <td>
                    <div class="survey_question">
                     3. My TSS knows me and my technology needs.
                    </div>
                   </td>
                   <td>1<input type="radio" name="known" value="1" /></td>
                   <td class="odd">2<input type="radio" name="known" value="2" /></td>
                   <td>3<input type="radio" name="known" value="3" /></td>
                   <td class="odd">4<input type="radio" name="known" value="4" /></td>
                   <td>5<input type="radio" name="known" value="5" /></td>
               </tr>
            <tr>
                <td class="odd"> <div class="survey_question">
                4. My TSS is approachable and listens to my concerns.  
                 </div>
                   </td>
                   <td>1<input type="radio" name="courteous" value="1" /></td>
                   <td class="odd">2<input type="radio" name="courteous" value="2" /></td>
                   <td>3<input type="radio" name="courteous" value="3" /></td>
                   <td class="odd">4<input type="radio" name="courteous" value="4" /></td>
                   <td>5<input type="radio" name="courteous" value="5" /></td>
               </tr>
        
           <tr>
               <td>
                <div class="survey_question">
                      5. When I have technology problems, my TSS responds promptly.
                </div>
                   </td>
                   <td>1<input type="radio" name="prompt" value="1" /></td>
                   <td class="odd">2<input type="radio" name="prompt" value="2" /></td>
                   <td>3<input type="radio" name="prompt" value="3" /></td>
                   <td class="odd">4<input type="radio" name="prompt" value="4" /></td>
                   <td>5<input type="radio" name="prompt" value="5" /></td>
          </tr>
          <tr>
               <td class="odd">
                <div class="survey_question">
                 6. I trust my TSS to handle any technology problems I have. 
                </div>
                 </td>
                <td>1<input type="radio" name="competent" value="1" /></td>
                <td class="odd">2<input type="radio" name="competent" value="2" /></td>
                <td>3<input type="radio" name="competent" value="3" /></td>
                <td class="odd">4<input type="radio" name="competent" value="4" /></td>
                <td>5<input type="radio" name="competent" value="5" /></td>
            </tr>
</table>
           
            <p>
                <div class="survey_question">
                Comments<span style="font-style:italic; font-weight: normal">- Optional</span>
            </div>
            <div>
                <textarea name="comments" rows="5" cols="80"></textarea>
            </div>
        
        
            <p>
                
            <div class="survey_question">
                Signature <span style="font-style:italic; font-weight: normal">- Optional</span>
            </div>
                <input type="text" size="30" name="signature" maxlength="30">
           
           <p>
                <input type="submit" value="Submit Survey">
           </p>
            
</form>