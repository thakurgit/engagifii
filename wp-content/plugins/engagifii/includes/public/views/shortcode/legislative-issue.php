<?php
$sessionid    = $_REQUEST['sessionId'] ;
print_r($sessionid );
//die;
 $obj =  new Engagifii_API();
$tags = $obj->legislationTagsFilter($sessionid);
$options = get_option( 'ebt_api_settings' );
 $lbt_visib_legislative_list   = $options['lbt_visib_legislative_list']  ?? array();

 if(site_url() == 'https://engagifiiweb.com'){
  $legislative_tags_list             = $options['legislative_tags_list'] ?? array();

    foreach ($tags as $tag) {
      if($tag->count > 0){
        if(!in_array($tag->tagId, $legislative_tags_list)){
            array_push($legislative_tags_list, (string)$tag->tagId);
            array_push($lbt_visib_legislative_list, (string)$tag->tagId);
        }
      }
    }

    global $wpdb;
    $prepared_query = $wpdb->prepare("SELECT option_value FROM ".$wpdb->prefix."options WHERE option_name = 'ebt_api_settings' ");
    $results = $wpdb->get_results( $prepared_query );
    $option_value = $results[0]->option_value;
    $decode_option_value = unserialize($option_value);
    if(count($legislative_tags_list) > count($decode_option_value['legislative_tags_list'])){
        $decode_option_value['legislative_tags_list'] = $legislative_tags_list;
        $decode_option_value['lbt_visib_legislative_list'] = $lbt_visib_legislative_list;
        update_option('ebt_api_settings', $decode_option_value);
    }


}

function sort_associative_array($a, $b) {
    return strcmp(ucfirst(trim($a->text)), ucfirst(trim($b->text)));
}

usort($tags, "sort_associative_array");

?>

 <div class=" mb-4 mb-md-0">
  <div class="row">
      <div class="col-sm-12">
        <select class="form-control eq-height" size="5" name="issue_tags">

   <?php foreach($tags as $tag){
          if(in_array($tag->tagId, $lbt_visib_legislative_list))
            {
        ?>
        <option class="text-break pb-1" data-title="<?php echo base64_encode($tag->text);?>" data-id="<?php echo $tag->tagId;?>" onclick="filterIssues('<?php echo $tag->tagId; ?>')">
        
        <?php  

         echo $tag->text.' ('.$tag->count.')';
      ?>
        </option>
        <?php } }?>
    </select>
    </div>
    </div>
  </div>

  <script type="text/javascript">
    function filterIssues(id) {
      $("body").removeClass('loaded');
     var tag = $('select[name="issue_tags"]').find(':selected').data('title');
      var redirect_url = '<?php echo get_site_url(); ?>/bill-tracking/?tag='+id+'&'+tag;
      window.location.href= redirect_url;

    }
</script>