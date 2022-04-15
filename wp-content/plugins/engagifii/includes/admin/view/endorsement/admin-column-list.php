<div class="<?php if($tab == 'settings'){ echo 'show';}else {echo 'hide'; }?>" >
<h2 class="m-tlr-20 bg-grey bordered">Endorsement Columns Visibility</h2>
<?php
    $obj =  new adminDataColumn();
    $response = $obj->getColumnData();
    $options = get_option( 'ebt_api_settings' );
    $ebt_visib_datacol_list = array();
    if(isset($options['ebt_visib_datacol_list']))
    	$ebt_visib_datacol_list = $options['ebt_visib_datacol_list'];   
    
    if(is_array ($response)){
    
    	echo '<div class="engagifii-setting">';
    	echo '<ul class="ebt-grid-column-list">';
    	$counter=0;
		foreach ($response as $key => $row) {
			$checked = "";
			if(in_array($row->colName, $ebt_visib_datacol_list))
			{
				$checked .= " checked";
			}
			if($row->colName=='endorsementName')
			{
				$checked .= " checked readonly";
			}
			if($counter >1 && $counter%3==0)
			{
				echo '</ul>';
				echo '<ul class="ebt-grid-column-list">'; 			 
			}			 
			echo '<li> <input id="'.$row->colName.'" class="'.$row->colName.'" type="checkbox" name="ebt_api_settings[ebt_visib_datacol_list][]" '.$checked.' value='.$row->colName.'><label for="'.$row->colName.'">'.$row->displayName.'</label></li>'		;
	    	$counter++;	  
    	}
    	echo '</ul></div>';				
    }
?>
</div>