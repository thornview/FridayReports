

<div id='login_form'>
		<?php 
                    echo form_open('login/process'); 
                 ?>
			<h2>User Login</h2>
			<br />
                       <?php if(! is_null($msg)) echo $msg;?>
                        
                        <p>
			<label for='username'>Username</label>
			<input type='text' name='username' id='username' size='25' /><br />
                        </p>
                        <p>
			<label for='password'>Password</label>
			<input type='password' name='password' id='password' size='25' /><br />							
                        </p>
			<input type='Submit' value='Login' />			
		</form>
	</div>

