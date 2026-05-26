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
 /* $('.cols-dropdown').each(function() {
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
        <button title="Delete Column" class="uncheck-cols" ${labelText === 'Name' ? 'disabled style="opacity: 0.5;"' : ''}>
          <i class="dashicons dashicons-no-alt"></i>
        </button>
      </li>
    `);
    const $popli = $(`
      <li data-order="${colOrder}" class="ui-sortable-handle">
        <input type="hidden" value='${colVal}' name="ebt_api_settings[${page}][${colView}][visible_column_list][]" />
        <span class="dashicons dashicons-sort"></span>
        <div class="bdrs">${labelText}</div>
      </li>
    `);
    
    // If it's the Name field, always add at the beginning
    if (labelText === 'Name') {
      $checkedList.prepend($li);
      $popupList.prepend($popli);
    } else {
      // For other fields, insert based on order but after Name field
      const newOrder = parseInt(colOrder, 10);
      const $children = $checkedList.children('li').not(':contains("Name")');
      const $popchildren = $popupList.children('li').not(':contains("Name")');
      let inserted = false;
      
      $children.each(function() {
        const existingOrder = parseInt($(this).attr('data-order'), 10);
        if (newOrder < existingOrder) {
          $(this).before($li);
          inserted = true;
          return false;
        }
      });
      $popchildren.each(function() {
        const existingOrder = parseInt($(this).attr('data-order'), 10);
        if (newOrder < existingOrder) {
          $(this).before($popli);
          inserted = true;
          return false;
        }
      });
      
      if (!inserted) {
        $checkedList.append($li);
        $popupList.append($popli);
      }
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
  
  if ($(this).is(':disabled')) {
    showAlert("This column can't be deleted");
    return;
  }
  
  const $li = $(this).closest('li');
  const colOrder = $li.data('order');
  const $input = $wrapper.find('li[data-order="' + colOrder + '"] input[type="checkbox"]');
  
  if ($input.length) {
    $input.prop('checked', false).trigger('change');
  }
});
  });*/
  
  //columns order manage pop up
  $('.manageColOrder').on('click', function() {
    $(this).siblings('.colsOrderModal').stop(true, true).fadeIn(300);
  });
  $('.colsOrderModal .close').on('click', function() {
    $(this).parents('.colsOrderModal').stop(true, true).fadeOut(300);
  });
  
  //Sorting saved columns as per order
  jQuery('.sortable-cols').each(function(){
		/*var colsList=[];	
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
		}*/
	
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
/*jQuery(document).ready(function($) {
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
            //alert('You can select up to 5 custom fields only.');
              showAlert('You can select up to 5 columns only.');
            e.preventDefault();
        }
    });
});
});*/
//ajax columns
  jQuery(document).ready(function () {
  jQuery('.accordion-btn').on('click', function () {
    const $header = jQuery(this);
    const $contentSection = $header.next('.accordion-content');
    $contentSection.find('ul[data-endpoint]').each(function () {
      const $ul = jQuery(this);
      const endpoint = $ul.data('endpoint');
     // const colsArray = $ul.data('colsArray');
      const visibleCols = JSON.parse($ul.attr('data-visiblecols') || '[]');
      if (!$ul.data('loaded') && endpoint) {
		ajaxCols(endpoint,visibleCols,$ul);
      }
    });
  });
  });
//execute ajax
function ajaxCols(endpoint,visibleCols,$ul){
	jQuery.ajax({
          url: engagifiiAjax.ajax_url,
          type: "post",
          data: { action: endpoint },
          success: function (response) {
            try {
              let html = '';
			  let counter = 0;
			  const seenColNames = new Set();
              jQuery.each(response, function (i, item) {
				counter++;
               const colName = item.colName || item.id || item.value || item.key || item.tagId || item.personId || item.sessionId || '';
			  if (seenColNames.has(colName)) return;
			  seenColNames.add(colName);
			  const displayName = item.displayName || item.name || item.text || item.fullName || item.sessionName || colName;
			  const colOrder = item.colOrder ?? counter;
			  const valueData = {
				colName: colName,
				displayName: displayName,
				colOrder: colOrder
			  };
			  if ('fieldId' in item) {
				valueData.fieldId = item.fieldId;
			  }
			  if ('controlTypeId' in item) {
				valueData.controlTypeId = item.controlTypeId;
			  }
				const ind = $ul.parents('.wrap').index()+'_'+$ul.parents('.cols-wrapper').index();
				let attrs = '';
				const isMandatoryOrgField = (endpoint === 'orgColumns' && colName === 'Name');
				if (isMandatoryOrgField) {
				  attrs += ' checked disabled';
				} else if (colName === 'name' || colName === 'sectionname' || colName === 'title' || colName === 'billNumber') {
				  if (visibleCols.includes(colName)) {
					attrs += ' checked disabled';
				  }
				} else if (visibleCols.includes(colName)) {
				  attrs += ' checked';
				}
				let customBadge = '';
				let cFieldClass = '';
				if ('fieldId' in valueData && valueData.fieldId && valueData.fieldId.toLowerCase() !== colName.toLowerCase()) {
				  customBadge = `<span class="cfield">Custom Field</span>`;
				  cFieldClass = 'cField';
				}
				// For mandatory fields, the sortable-cols modal already contains the value
				// via PHP rendering, so no extra hidden input is needed here.
				// (Adding one here caused 'Name' to be duplicated on every WP form save.)
				const hiddenInput = '';
                html += `<li  data-order="${counter}">
                    <input class="${cFieldClass}" id="${colName}-${ind}" type="checkbox" value='${JSON.stringify(valueData)}'${attrs}>
                    <label for="${colName}-${ind}">${displayName}</label>${customBadge}${hiddenInput}
                  </li>`;
              });
			  if(html==''){
				  html='<b><i style="color:var(--e-context-error-color)">No data found! Try again.</i></b>'
			  }
              $ul.html(html).removeClass('loading').siblings('.refreshCols').removeClass('loading');
			 toggleAll($ul);
			 checkboxEvents($ul);
              $ul.data('loaded', true);

            } catch (e) {
               $ul.html('<li><b><i style="color:var(--e-context-error-color)">Invalid JSON response OR failed to render HTML</i></b></li>').removeClass('loading').siblings('.refreshCols').removeClass('loading');
            }
          },
          error: function () {
            $ul.html('<li><b><i style="color:var(--e-context-error-color)">Error loading content. Refresh again.</i></b></li>').removeClass('loading').siblings('.refreshCols').removeClass('loading');
          }
        });
}
//refresh ajax columns
jQuery(document).ready(function($) {
  $('.refreshCols').on('click', function(e) {
	e.preventDefault();
	const $button = $(this);
	const $ul = $button.siblings('ul[data-endpoint]');
	const endpoint = $ul.data('endpoint');
     const visibleCols = JSON.parse($ul.attr('data-visiblecols') || '[]');
    const $checkedList = $ul.parents('.cols-dropdown').siblings('.checked-cols');
    const $popupList = $ul.parents('.cols-dropdown').siblings('.colsOrderModal').find('.colsListBody')
	if($ul && endpoint){
		$ul.addClass('loading');
		$button.addClass('loading');
		const visibleCols = [];
		console.log(visibleCols);
		$checkedList.html('<span class="placeholder">No columns selected.</span>');
		$popupList.html('');
		ajaxCols(endpoint,visibleCols,$ul);
	}
  });
});
//select/deselect all checkbox
function toggleAll($list) {
  let tid = $list.parents('.wrap').index()+'_'+$list.parents('.cols-wrapper').index();
    const $checkboxes = $list.find('li:not(.toggleAll) input[type="checkbox"]:not(.cField)');
    if ($checkboxes.length === 0) return;

    const toggleId = `toggleAll_${tid}`;
    const $toggleAllItem = jQuery(`
      <li class="toggleAll">
        <input type="checkbox" id="${toggleId}" />
        <label for="${toggleId}"><b><u>Select/Deselect all</u></b></label>
      </li>
    `);

    $list.prepend($toggleAllItem);
    const $toggleAll = $toggleAllItem.find('input');

    $toggleAll.prop('checked', $checkboxes.length === $checkboxes.filter(':checked').length);

    $toggleAll.on('change', function () {
      const isChecked = jQuery(this).is(':checked');
      $checkboxes.not('[disabled], .cField').prop('checked', isChecked).trigger('change');
    });

    $checkboxes.on('change', function () {
      const allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;
      $toggleAll.prop('checked', allChecked);
    });
	//for custom fields
	const $checkboxesCField = $list.find('li:not(.toggleAll) input[type="checkbox"].cField');
	//setTimeout(function() {
	  if ($checkboxesCField.first().parent('li').length) {
		jQuery('<span class="custom-fields-separator">Custom Fields</span>').insertBefore($checkboxesCField.first().parent());
	  }
	  jQuery('.groups-grid').find($list).find('.toggleAll').remove();
	  if($checkboxesCField.filter(':checked').length>5){
		 $checkboxesCField.filter(':not(:checked)').attr('disabled',''); 
	  }
	//}, 1000);
    $checkboxesCField.on('change', function () {
	  if($checkboxesCField.filter(':checked').length>5){
		 $checkboxesCField.filter(':not(:checked)').attr('disabled',''); 
		showAlert('Max 6 Custom Fields allowed.');  
	  } else {
		 $checkboxesCField.filter(':not(:checked)').removeAttr('disabled'); 
	  }
    });
	jQuery('.groups-grid').find('[data-endpoint] input[type=checkbox]').on('change', function () {
	  const $changedCheckbox = jQuery(this);
	  const $container = $changedCheckbox.closest('.groups-grid'); // Scope to the current group
	  const $gridcheckboxes = $container.find('[data-endpoint] input[type=checkbox]');
	  const checkedCount = $gridcheckboxes.filter(':checked').length;
	
	  if (checkedCount > 6) {
		$gridcheckboxes.filter(':not(:checked)').attr('disabled', true);
		showAlert('Max 6 Fields allowed.');
	  } else {
		$gridcheckboxes.removeAttr('disabled');
	  }
	});

}
//cols dropdown events after ajax
function checkboxEvents($list){
	const colsArray = $list.data('colsArray');
    const $checkedList = $list.parents('.cols-dropdown').siblings('.checked-cols');
    const $popupList = $list.parents('.cols-dropdown').siblings('.colsOrderModal').find('.colsListBody')

	// Seed mandatory (checked+disabled) fields into checked-cols and popupList immediately.
	// This ensures they survive a dropdown refresh, since no 'change' event fires for pre-checked items.
	$list.find('li:not(.toggleAll) input[type="checkbox"]:checked:disabled').each(function() {
		const $checkbox = jQuery(this);
		const colOrder = $checkbox.parent().attr('data-order');
		const colVal = $checkbox.val();
		const labelText = $checkbox.siblings('label').text();
		if ($checkedList.find('[data-order="' + colOrder + '"]').length === 0) {
			$checkedList.find('span.placeholder').remove();
			const $li = jQuery(`
				<li data-order="${colOrder}">
				  ${labelText}
				  <button title="Delete Column" class="uncheck-cols" disabled style="opacity:0.5;cursor:not-allowed;">
					<i class="dashicons dashicons-no-alt"></i>
				  </button>
				</li>
			`);
			$li.attr('title', "This field can't be removed");
			const $popli = jQuery(`
				<li data-order="${colOrder}" class="ui-sortable-handle">
				  <input type="hidden" value='${colVal}' name="${colsArray}" />
				  <span class="dashicons dashicons-sort"></span>
				  <div class="bdrs">${labelText}</div>
				</li>
			`);
			updateOrder($checkedList, $li, parseInt(colOrder, 10));
			updateOrder($popupList, $popli, parseInt(colOrder, 10));
		}
	});

    $list.find('li:not(.toggleAll) input[type="checkbox"]').on('change', function() {
      const $checkbox = jQuery(this);
      const colOrder = $checkbox.parent().attr('data-order');
      const colVal = $checkbox.val();
      const labelText = $checkbox.siblings('label').text();
	  if ($checkbox.is(':checked')) {
		if ($checkedList.find('[data-order="' + colOrder + '"]').length === 0) {
		  const isLocked = $checkbox.prop('disabled');
		  const $li = jQuery(`
			<li data-order="${colOrder}">
			  ${labelText}
			  <button title="Delete Column" class="uncheck-cols" ${isLocked ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : ''}>
				<i class="dashicons dashicons-no-alt"></i>
			  </button>
			</li>
		  `);
		  if (isLocked) $li.attr('title', "This field can't be removed");
		  const $popli = jQuery(`
			<li data-order="${colOrder}" class="ui-sortable-handle">
			  <input type="hidden" value='${colVal}' name="${colsArray}" />
			  <span class="dashicons dashicons-sort"></span>
			  <div class="bdrs">${labelText}</div>
			</li>
		  `);
		  const newOrder = parseInt(colOrder, 10);
		  $checkedList.prepend($li);
			$popupList.prepend($popli);
			updateOrder($checkedList, $li, newOrder);
			updateOrder($popupList, $popli, newOrder);
		}
	  } else {
		// Never remove a mandatory (disabled) field even if unchecked programmatically
		if ($checkbox.prop('disabled')) { $checkbox.prop('checked', true); return; }
        $checkedList.find('[data-order="' + colOrder + '"]').remove();
		$popupList.find('[data-order="' + colOrder + '"]').remove();
      }
	  if($checkedList.find('li').length==0){
		 $checkedList.html('<span class="placeholder">No columns selected.</span>');
	  }else {
		 $checkedList.find('span.placeholder').remove(); 
	  }
  });

    // Handle delete button in checked-cols (event delegation)
  $checkedList.on('click', '.uncheck-cols', function(e) {
  e.preventDefault();
  const $li = jQuery(this).closest('li');
  const colOrder = $li.data('order');
  const $input =$list.find('li[data-order="' + colOrder + '"] input[type="checkbox"]');
  if ($input.length) {
	  if($input.prop('disabled')) {
		showAlert("This column can't be deleted"); 
		return; 
	  } else{
    	$input.prop('checked', false).trigger('change');
	  }
  }
});
}
//update list after checkbox selected
function updateOrder($list, $item, newOrder) {
  let inserted = false;

  $list.children().each(function () {
    const existingOrder = parseInt(jQuery(this).attr('data-order'), 10);
    if (newOrder < existingOrder) {
      jQuery(this).before($item);
      inserted = true;
      return false; // exit loop
    }
  });

  if (!inserted) {
    $list.append($item); // add to end if no smaller order found
  }
}
//save columns ajax
jQuery(document).ready(function($) {
$('.colsSave').on('click', function(e) {
e.preventDefault();
const $button = $(this);
const $container = $button.parent().siblings('.colsListBody');
const originalText = $button.text();
const columnsName = $button.data('columnsname');
$button.text('Updating...').prop('disabled', true);
const visibleCols = [];
$container.find('li input').each(function() {
  visibleCols.push(decodeHtmlEntities($(this).val()));
});
$.ajax({
  url: engagifiiAjax.ajax_url,
  type: 'POST',
  data: {
	action: 'save_cols',
	visible_column_list: visibleCols, 
	column_namearray: columnsName,
	security: engagifiiAjax.nonce
  },
  success: function(response) {
	console.log(response.data?.message);
	$('<p style="color:var(--e-context-success-color)"><strong><em>Columns saved successfully!</em></strong></p>').insertAfter($button);
	setTimeout(() => {
		$button.siblings('p').remove();
	}, 2000);
  },
  error: function() {
	alert('Error saving column settings.');
  },
  complete: function() {
	$button.text(originalText).prop('disabled', false);
  }
});
});
});



