jQuery(document).ready(function(){
   jQuery('.engagifii-color-picker').wpColorPicker();

   jQuery("#engagii_custom_css").on("click", function(){
      var isChecked= jQuery(this).is(":checked");
      if(isChecked==true){
         jQuery(".engagifi_style_group").show();
      }
      else
      {

         jQuery(".engagifi_style_group").hide();
         jQuery('#font-family-tz').val('');
         jQuery('#font-size-tz').val('');
         jQuery('#table-size-tz').val('');
         jQuery('#detail-size-tz').val('');
         jQuery('#ebt-size-tz').val('');
         jQuery('.wp-picker-clear').click();
      }

   });
    const facheckbox = document.getElementById('include_fontawesome');
    const faversionWrapper = document.getElementById('fa-version-wrapper');
	if(facheckbox){
	  facheckbox.addEventListener('change', function () {
		  if (this.checked) {
			  faversionWrapper.style.display = '';
		  } else {
			  faversionWrapper.style.display = 'none';
		  }
	  });
	}
});

document.addEventListener("DOMContentLoaded", function() {  
  //cols search
    const searchInputs = document.querySelectorAll(".cols-list-search");
  searchInputs.forEach(input => {
    input.addEventListener("input", function () {
      const filter = this.value.toLowerCase();
      // Find the next sibling UL (assumes structure: input > hidden > ul)
      const ul = this.parentElement.querySelector("ul");
      if (!ul) return;

      const listItems = ul.querySelectorAll("li");
      listItems.forEach((li, index) => {
        if (index === 0) return; // Ignore first <li> (toggleAll)

        const text = li.textContent.toLowerCase();
        li.style.display = text.includes(filter) ? "list-item" : "none";
      });
    });
  });

}); 
jQuery(document).ready(function($) {
//cols dropdown show/hide
 $('.cols-dropdown > button').on('click', function(e) {
    e.stopPropagation();
    const $wrapper = $(this).next('.cols-list-wrapper');
    $('.cols-list-wrapper').not($wrapper).hide();
    $wrapper.toggle();
  });
  $(document).on('click', function(e) {
    if (!$(e.target).closest('.cols-dropdown').length) {
      $('.cols-list-wrapper').hide();
    }
  });
  
  	//columns dropdown events
  $('.cols-dropdown').each(function() {
    const $dropdown = $(this);
    const $checkedList = $dropdown.siblings('.checked-cols');
    const $popupList = $dropdown.siblings('.colsOrderModal').find('.colsListBody');
    const $listOrder = $popupList.siblings('.cls');
    const $wrapper = $dropdown.find('.cols-list-wrapper');

    $wrapper.find('li:not(.toggleAll) input[type="checkbox"]').on('change', function() {
      const $checkbox = $(this);
      const colOrder = $checkbox.parent().attr('data-order');
      const colVal = $checkbox.val();
      const labelText = $checkbox.siblings('label').text();
      if ($checkbox.is(':checked')) {
        // Add to checked-cols if not already there
        if ($checkedList.find('[data-order="' + colOrder + '"]').length === 0) {
          const $li = $(`
            <li data-order="${colOrder}">
              ${labelText}
              <button title="Delete Column" class="uncheck-cols">
                <i class="dashicons dashicons-no-alt"></i>
              </button>
            </li>
          `);
		  const $popli = $(`
            <li data-order="${colOrder}" class="ui-sortable-handle"><input type="hidden" value="${colVal}" name="ebt_api_settings[group_members_settings][visible_column_list][]" /><span class="dashicons dashicons-sort"></span>
			<div class="bdrs">${labelText}</div>
            </li>
          `);
		  const newOrder = parseInt(colOrder, 10);
		  const $children = $checkedList.children('li');
		  const $popchildren = $popupList.children('li');
		  let inserted = false;
		  
		  $children.each(function() {
			const existingOrder = parseInt($(this).attr('data-order'), 10);
			if (newOrder < existingOrder) {
			  $(this).before($li);
			  inserted = true;
			  return false; // break the loop
			}
		  });
		  $popchildren.each(function() {
			const existingOrder = parseInt($(this).attr('data-order'), 10);
			if (newOrder < existingOrder) {
			  $(this).before($popli);
			  inserted = true;
			  return false; // break the loop
			}
		  });
		  
		  if (!inserted) {
			$checkedList.append($li);
			$popupList.append($popli);
		  }
		  updateOrder($listOrder, colOrder, 'append');
        }
      } else {
        // Remove from checked-cols and popup columns
        $checkedList.find('[data-order="' + colOrder + '"]').remove();
		$popupList.find('[data-order="' + colOrder + '"]').remove();
		  updateOrder($listOrder, colOrder, 'remove');
      }
    });
	function updateOrder($input, value, action = 'append') {
		if($input.val()==''){
			return;	
		}
	  let current = $input.val().split(',').filter(Boolean);
	  if (action === 'append') {
		if (!current.includes(value)) {
		  current.push(value);
		}
	  } else if (action === 'remove') {
		current = current.filter(item => item !== value);
	  }
	  $input.val(current.join(','));
	}

    // Handle delete button in checked-cols (event delegation)
    $checkedList.on('click', '.uncheck-cols', function(e) {
      e.preventDefault();
      const $li = $(this).closest('li');
      const colOrder = $li.data('order');
		const $input = $wrapper.find('ul li[data-order="' + colOrder + '"] input');
		if (!$input.prop('readonly')) {
		  $input.prop('checked', false).trigger('change');
		} else {
			showAlert();
		}
    });
  });
  
  //columns order manage pop up
  $('.manageColOrder').on('click', function() {
    $(this).siblings('.colsOrderModal').stop(true, true).fadeIn(300);
  });
  $('.colsOrderModal .close').on('click', function() {
    $(this).parents('.colsOrderModal').stop(true, true).fadeOut(300);
  });
  
  //Sorting saved columns as per order
  jQuery('.sortable-cols').each(function(){
		var colsList=[];	
		if(jQuery(this).siblings('.cls').val()!==''){
		  colsList= (jQuery(this).siblings('.cls').val()).split(',');
		}
	  if(colsList.length>0){
		var i;
		var outerHtml=[];
		for (i = 0; i < colsList.length; ++i) {
		   const $item = jQuery(this).children('li[data-order="' + colsList[i] + '"]');
			if ($item.length) {
			  outerHtml.push($item[0]); // DOM element
			}
		}
		  jQuery(this).html(outerHtml);
		}
	
	//initialize sorting
	  jQuery(this).sortable({
	  items : 'li',
	   placeholder: "ui-state-highlight",
	   axis: 'y',
	   update: function( event, ui ) {
			 var col_order=[];
			jQuery(this).children("li" ).each(function(){
				col_order.push(jQuery(this).attr('data-order'));
			});
			jQuery(this).siblings('.cls').val(col_order);
		   }
	  });
	});
	
  //reset order
  function sortCols(a, b) {
	  return parseInt(a.dataset.order) - parseInt(b.dataset.order);
	}
  	jQuery('.resetOrder').click(function(){
		jQuery(this).parent().siblings('.cls').val('');	
		jQuery(this).parent().siblings('.colsListBody').find('li.ui-sortable-handle').sort(sortCols).each(function() {
			var elem = jQuery(this);
			jQuery(elem).appendTo(jQuery(this).parent('ul'));
		});
	});

});
