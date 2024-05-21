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
					  $enabledModules = is_array(get_option('engagifii_modules')) ? get_option('engagifii_modules') : [];
					  $moduleOptions = [
						  'Training & Accreditation' => 'training-and-accreditation',
						  'Legislation' => 'legislation',
						  'Events' => 'events',
						  'Dashboard' => 'dashboard'
					  ];
					  
					  foreach ($moduleOptions as $moduleLabel  => $moduleValue) {
						  $isChecked = in_array(htmlspecialchars($moduleValue, ENT_QUOTES, 'UTF-8'), $enabledModules) ? ' checked' : '';
						  $checkboxHtml = sprintf(
							  '<label for="%s"><input name="engagifii_modules[]" type="checkbox" value="%s" id="%s" %s />%s</label><br>',
								htmlspecialchars($moduleValue, ENT_QUOTES, 'UTF-8'),
								htmlspecialchars($moduleValue, ENT_QUOTES, 'UTF-8'),
								htmlspecialchars($moduleValue, ENT_QUOTES, 'UTF-8'),
								$isChecked,
								htmlspecialchars($moduleLabel, ENT_QUOTES, 'UTF-8')
						  );
					  
						  echo $checkboxHtml;
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