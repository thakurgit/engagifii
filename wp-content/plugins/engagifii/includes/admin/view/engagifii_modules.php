<div class="wrap">
  <h1>Engagifii Modules</h1>
  <form class="" action='options.php' method='post'>
  	<?php settings_fields( 'engagifiiModules' );
        	do_settings_sections( 'engagifiiModules' );
	?>
    <table class="form-table">
    	<tbody>
        	<tr>
            	<th>Select Modules</th>
                <td>
                	<fieldset>
                   <?php
					  $checkedModules = get_option('engagifii_modules', []);
					  $modules = [
						  'Training & Accreditation' => 'training-and-accreditation',
						  'Legislation' => 'legislation',
						  'Events' => 'events',
						  'Dashboard' => 'dashboard'
					  ];
					  
					  foreach ($modules as $label => $value) {
						  $checked = in_array(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'), $checkedModules) ? ' checked' : '';
					  
						  $checkBoxField = sprintf(
							  '<label for="%s"><input name="engagifii_modules[]" type="checkbox" value="%s" id="%s" %s />%s</label><br>',
							  htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
							  htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
							  htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
							  $checked,
							  htmlspecialchars($label, ENT_QUOTES, 'UTF-8')
						  );
					  
						  echo $checkBoxField;
					  }
					  ?>
                    </fieldset>
                </td>
          </tr>
            
        </tbody>
    </table>
	<?php submit_button(); ?>
  </form>
</div>