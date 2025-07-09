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
        if (li.classList.contains("toggleAll")) return; // Skip <li> with class "toggleAll"

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
    const page = $dropdown.attr('data-option');

    $wrapper.find('li:not(.toggleAll) input[type="checkbox"]').on('change', function() {
      const $checkbox = $(this);
      const colOrder = $checkbox.parent().attr('data-order');
      const colVal = $checkbox.val();
      const labelText = $checkbox.siblings('label').text();
	  let colView;
	  if ($checkbox.attr('id').includes('_grid')) {
         colView = 'grid';
	  }else{
         colView = 'list';
	  }
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
            <li data-order="${colOrder}" class="ui-sortable-handle"><input type="hidden" value='${colVal}' name="ebt_api_settings[${page}][${colView}][visible_column_list][]" /><span class="dashicons dashicons-sort"></span>
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
			showAlert("This column can't be deleted");
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
function decodeHtmlEntities(str) {
  const txt = document.createElement("textarea");
  txt.innerHTML = str;
  return txt.value;
}
jQuery(document).ready(function($) {
    const maxAllowed = 5;

    function updateCheckboxState(wrapper) {
        const checkboxes = wrapper.find('input[type="checkbox"]:not([readonly])');
        const checkedCount = checkboxes.filter(':checked').length;

        checkboxes.each(function () {
            const isChecked = $(this).is(':checked');
            $(this).prop('disabled', !isChecked && checkedCount >= maxAllowed);
        });
    }

    // Attach change event handler to checkboxes inside each .cols-list-wrapper
    $('.org-grid .cols-list-wrapper, .groups-grid .cols-list-wrapper').each(function () {
        const wrapper = $(this);
		const checkboxesli = wrapper.find('li');
        wrapper.find('input[type="checkbox"]').on('change', function () {
            updateCheckboxState(wrapper);
        });

        // Run on page load in case some checkboxes are already checked
        updateCheckboxState(wrapper);
		checkboxesli.on('mousedown', function (e) {
            if ($(this).find('input').is(':disabled')) {
                showAlert('You can select up to ' + (maxAllowed + 1) + ' columns only.');
                e.preventDefault();
            }
        });
    });
       $('.groups-list .cols-list-wrapper').each(function () {
    const wrapper = $(this);
    const checkboxesli = wrapper.find('li');

    function updateCustomCheckboxState(wrapper) {
        // Find all custom field checkboxes (li with .cfield)
        const customCheckboxes = wrapper.find('li').filter(function() {
            return $(this).find('.cfield').length > 0;
        }).find('input[type="checkbox"]:not([readonly])');
        const checkedCustom = customCheckboxes.filter(':checked').length;

        customCheckboxes.each(function () {
            const isChecked = $(this).is(':checked');
            $(this).prop('disabled', !isChecked && checkedCustom >= 5);
        });
    }

    wrapper.find('input[type="checkbox"]').on('change', function () {
        updateCustomCheckboxState(wrapper);
    });

    // Run on page load in case some checkboxes are already checked
    updateCustomCheckboxState(wrapper);

    checkboxesli.on('mousedown', function (e) {
        // Only show alert for custom fields
        if (
            $(this).find('.cfield').length > 0 &&
            $(this).find('input').is(':disabled')
        ) {
            alert('You can select up to 5 custom fields only.');
            e.preventDefault();
        }
    });
});
});
