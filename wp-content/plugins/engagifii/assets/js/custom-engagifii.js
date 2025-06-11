  window.$ = jQuery.noConflict();
    function __addExtraDiv(title)
    {
      var span_Ext = $(document).find(".select2-search").find("h6.engwarpper").length;
      if(span_Ext<1)
      {  
          $(document).find(".select2-search").prepend("<h6 class=\"engwarpper\"> "+title+" </h5>");
      }
    }

     
  function format(item, state) {
     
  var profilePic = ""; 
  var img = ''  ;
  var tagid = '';
  var tagname = '';
  var tag_url = '';
   if (typeof item.element !== 'undefined')   
   {
      profilePic = item.element.dataset.profilepic;
      tagid      = item.element.dataset.tagid;
      tagname    = item.element.dataset.tagtext;

  if (!item.id) {
    return item.text;
  }
   if(tagid && tagname)
    {
        var current_url = document.URL;
        tag_url = current_url+'?tag='+tagid+'&'+tagname;

    }

  img = $("<img>", {
    class: "img-flag",
    width: 26,
    src: profilePic
  });
  if(tag_url)
  {
        var span = $("<a>", {
        class: "tag-redirect",
        text: " " + item.text,
        href: tag_url
    });
  }
  else
  {
        var span = $("<p>", {
    text: " " + item.text
  });
  }
  
  if(profilePic)
    span.prepend(img);



  return span;
    }
}

$("[data-toggle = 'tooltip']").tooltip(); 
function dt_dropdown() {
  $('div.td-dropdown').each(function() {   
$(this).mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:'minimal-dark',
		 			scrollbarPosition:'outside'
});
});
  $('.search-dropdown').each(function() { 
  $(this).on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $(this).parent().siblings('li, a, li a').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });
	  if($(this).parent().siblings('li:visible, a:visible').length<1){
		  $(this).parent().siblings('span').addClass('d-block').removeClass('d-none');
	  } else {
		  $(this).parent().siblings('span').addClass('d-none').removeClass('d-block');
	  }
  });
  });
   
}
function dt_scroll(){
 		$('.dataTables_wrapper ').append('<span title="Scroll ight" class="nxt position-absolute bg-primary text-white rounded-circle d-none d-xl-inline-flex align-items-center justify-content-center"><i class="far fa-angle-right"></i></span>');
		$('.dataTables_wrapper ').prepend('<span title="Scroll Left" class="prv position-absolute bg-primary text-white rounded-circle d-none d-xl-inline-flex align-items-center justify-content-center disabled"><i class="far fa-angle-left"></i></span>');
				function dtScrollButton(){
					var divWidth = parseInt($('.custom-scroll').outerWidth());
					var tablewidth = parseInt($('#ebtmaintable').outerWidth());
				   if(tablewidth<=divWidth){
						$('.nxt,.prv').removeClass('d-xl-inline-flex');  
						$('.engagifii-main-cotainer').removeClass('px-xl-5');  
				   }else{
						$('.nxt,.prv').addClass('d-xl-inline-flex');  
						$('.engagifii-main-cotainer').addClass('px-xl-5');  
				   }
				}
				dtScrollButton();
				$(window).resize(function(){
					dtScrollButton();
			   	});
				$('.nxt').click(function () {
				   $('.custom-scroll').animate({
					  scrollLeft: "+=500px"
				   }, "slow"); 
				   
				});  
				$('.prv').click(function () {
				   $('.custom-scroll').animate({
					  scrollLeft: "-=500px"
				   }, "slow");
				});  
				$('.custom-scroll').on('scroll', function(){
				  var $this = $(this);
				  if ($this.scrollLeft() + $this.outerWidth() >= $this[0].scrollWidth) {
					 $('.nxt').addClass('disabled'); 
				  }else if($this.scrollLeft()>0){
					 $('.prv, .nxt').removeClass('disabled');  
				  }else{
					$('.prv').addClass('disabled');   
				  }
				});
}
function dt_filterActivate(){
    $('body').on('click', '.heading-title' ,function(){
        $(this).parent().toggleClass('active');
		 $(this).parent().siblings('.filter-list').removeClass('active');
		$(this).next('.content-area').toggleClass('d-none');
		$(this).parent().siblings('.filter-list').find('.content-area').addClass('d-none');
    });
	$('.filter-icon').on('click').click(function(e){
        e.stopPropagation();
        $(this).siblings('.filter-border').show();
        $(this).siblings('.filter-border').find('.filter-area').toggleClass('d-none');
		$(this).siblings('.filter-border').find(".filter-area .list-group, .list-box").mCustomScrollbar({
		 	 scrollButtons:{enable:true},
					theme:"minimal-dark",
		 			scrollbarPosition:"outside"
		 			});
		
    });
}
function dt_titleSearch(placeholder = 'Search..'){

$('#ebtmaintable thead tr th:eq('+titleColumn+')').each( function (i) {
$('.list-search-btn').click(function(e){
	var ttitle= $('.list-search').val();
	if(ttitle!=''){
		$('#list').trigger('click');	
		table.column(titleColumn).search(ttitle).draw();
		 $( '#searchTitle' ).val($('.list-search').val());
		$('.clear-search').show();
	} else {
		alert("search field can't be empty");	
	}
	e.stopPropagation();
 });
$('.list-search').on("keydown", function(event) {
  if(event.which == 13){
	$('.list-search-btn').trigger('click');  
  }  
});
 
         var title = $(this).text();
        $(this).html( '<div class="position-relative input-group search-dt flex-nowrap"><input type="text" id="searchTitle" placeholder="'+placeholder+'" class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search d-none"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none" style="right:21px; top:-1px; z-index:5;display:none"><i class="fal fa-times"></i></button></div>' );

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
  $( 'input', this ).keyup(delay(function (e) {
	  var titlesearch = this.value;
            if ( table.column(titleColumn).search() !== titlesearch ) {
				table.column(titleColumn).search(titlesearch).draw();
            }
}, 500));


 $( 'input', this ).keyup(function(e){
	if(this.value.length!=0){
				$(this).siblings('.clear-search').show();
			} else {
				$(this).siblings('.clear-search').hide();
			} 
 });
$(this).find('.clear-search').click(function(e){
	 $(this).siblings('#searchTitle').val('');
	$(this).hide();
	e.stopPropagation();
	table.column(titleColumn).search('').draw();
 });

    } );	
 $('#searchTitle, .search-dt span').on('click', function(e){
       e.stopPropagation();    
    });
$('#searchTitle').on("keydown", function(event) {
  if(event.which == 13){
       return false;   
  }  
});

}
function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
function dt_columnSearch(key,placeholder){
  $('#ebtmaintable thead tr th:eq('+key+')').each( function () { 
  var title = $(this).text();
  $(this).html( '<div class="position-relative input-group search-dt flex-nowrap"><input type="text" id="searchTitle" placeholder="'+placeholder+'" class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search d-none"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none h-100" style="right:0px; top:0px; z-index:5;display:none"><i class="fal fa-times"></i></button></div>' );
	$( 'input', this ).keyup(delay(function (e) {
		var titlesearch = this.value;
		if($(this).parents('th').hasClass('billNumber')){
			if (/\d/.test(titlesearch)) {
			 var number = titlesearch.match(/\d+/)[0];
			 if (number.length == 3) {
				number = '0' + number;
			  } else if (number.length == 2) {
				number = '00' + number;
			  }else if(number.length == 1) {
				number = '000' + number;
			  }
		   titlesearch = titlesearch.replace(/\d+/, ' '+number);
		   titlesearch = titlesearch.replace(/  +/g, ' '); 
		  }	
		}
			  if ( table.column(key).search() !== titlesearch ) {
				  table.column(key).search(titlesearch).draw();
			  }
  }, 500));
   $( 'input', this ).keyup(function(e){
	  if(this.value.length!==0){
				  $(this).siblings('.clear-search').show();
			  } else {
				  $(this).siblings('.clear-search').hide();
			  } 
   });
  	$(this).find('.clear-search').click(function(e){
	   $(this).siblings('#searchTitle').val('');
	  $(this).hide();
	  e.stopPropagation();
	  table.column(key).search('').draw();
   });
    });	
  $('#searchTitle, .search-dt span').on('click', function(e){
		 e.stopPropagation();    
	  });
  $('#searchTitle').on("keydown", function(event) {
	if(event.which == 13){
		 return false;   
	}  
  });
} 
$('.input-group-append').click(function() {
  // Trigger click event of the date input element
  $(this).siblings('input').click();
});

// Adjust the position of the calendar icon in the date input group
$('.input-group-append').css('cursor', 'pointer');
window.addEventListener("resize", function() {
    $(window).resize();
});
$(document).on('click', '.dropdown-menu', function (e) {
    e.stopPropagation();
});

function buildPopoverHtml(field, items) {
    if (!Array.isArray(items) || items.length === 0) return '--';

    // Helper to get label for the field type
   function getPluralLabel(field, count) {
        if (field === 'position') return count + ' Positions';
        if (field === 'department') return count + ' Departments';
        if (field === 'roles') return count + ' Roles';
        if (field === 'persontype') return count + ' Person Types';
        if (field === 'terms') return count + ' Terms';
        if (field === 'tags') return count + ' Tags';
        if (field === 'region') return count + ' Regions';
        return count + ' Items';
    }
     if (field === 'region') {
        var pluralLabel = getPluralLabel(field, items.length);
        var linkText = (items.length === 1) ? items[0].regionName : pluralLabel;
        var htmlList = '<div class="dropdown-menu show p-0" style="min-width:600px !important;">';
        items.forEach(function(it) {
            htmlList += '<div class="px-3 py-2 border-bottom small" style="white-space:normal;">' +
                '<strong>' + (it.regionName || '') + '</strong>' +
                '<br><span class="d-block" style="color:#2176d2;">' + (it.organizationName || '') + '</span>' +
                '<span class="d-block">Term: ' + (it.electionTermName || '') + '</span>' +
                '</div>';
        });
        htmlList += '</div>';
        return '<span class="d-inline-block pr-2">' +
            '<span tabindex="0" class="badge badge-primary" data-toggle="popover" data-html="true" data-trigger="focus" data-content="' +
            htmlList.replace(/"/g, '&quot;') +
            '">' + linkText + '</span></span>';
    }

    // Single item: show only the main label (no org/department)
    if (items.length === 1) {
        if (items[0].positionName) {
            return items[0].positionName;
        } else if (items[0].departmentName) {
            return items[0].departmentName;
        } else if (items[0].electionTermName) {
            return items[0].electionTermName;
        } else if (items[0].name) {
            return items[0].name;
        } else if (items[0].tagName) {
            return items[0].tagName;
        } else {
            return items[0].toString();
        }
    }

    // Multiple items: show "N Positions" (or similar) as clickable badge
    var pluralLabel = getPluralLabel(field, items.length);

    // Build HTML list for popover content (like list view)
    var htmlList = '<div class="dropdown-menu show p-0" style="min-width:600px !important;">';
    items.forEach(function(it, idx) {
        var label = '';
        if (it.positionName) {
            label = '<strong>' + it.positionName + '</strong>';
            if (it.organizationName) label += '<div class="text-muted small">' + it.organizationName + '</div>';
        } else if (it.departmentName) {
            label = '<strong>' + it.departmentName + '</strong>';
            if (it.organizationName) label += '<div class="text-muted small">' + it.organizationName + '</div>';
        } else if (it.electionTermName) {
            label = '<strong>' + it.electionTermName + '</strong>';
            if (it.position && it.position.organizationName) label += '<div class="text-muted small">' + it.position.organizationName + '</div>';
        } else if (it.name) {
            label = it.name;
        } else if (it.tagName) {
            label = it.tagName;
        } else {
            label = it.toString();
        }
        htmlList += '<div class="px-3 py-2 border-bottom small" style="white-space:normal;">' + label + '</div>';
    });
    htmlList += '</div>';

    // Popover trigger: show "N Positions" (or similar)
    var html = '<span class="d-inline-block pr-2">' +
        '<span tabindex="0" class="badge badge-primary" data-toggle="popover" data-html="true" data-trigger="hover" data-content="' +
        htmlList.replace(/"/g, '&quot;') +
        '">' + pluralLabel + '</span></span>';
    return html;
}

function renderPagination(totalCount, start, length, modulename) {
  const $pagination = $('.grid-pagination');
  const currentPage = Math.floor(start / length) + 1;
  const totalPages = Math.ceil(totalCount / length);

  // Clear existing page numbers (except First & Last <li>)
  $pagination.find('li.page-number').remove();

  const visiblePages = [];
  
  // Always show first page
  visiblePages.push(1);

  // Pages before current
  for (let i = currentPage - 2; i <= currentPage + 2; i++) {
    if (i > 1 && i < totalPages) {
      visiblePages.push(i);
    }
  }

  // Always show last page if not already in list
  if (totalPages > 1) {
    visiblePages.push(totalPages);
  }

  // Remove duplicates and sort
  const uniquePages = [...new Set(visiblePages)].sort((a, b) => a - b);

  // Render pages with ellipsis
  for (let i = 0; i < uniquePages.length; i++) {
    if (i > 0 && uniquePages[i] !== uniquePages[i - 1] + 1) {
      $pagination.find('li.page-item').last().before('<li class="page-item disabled page-number"><span class="page-link">...</span></li>');
    }

    const pageNum = uniquePages[i];
    const activeClass = pageNum === currentPage ? 'active' : '';
    const $pageItem = $('<li class="page-item page-number ' + activeClass + '"><a class="page-link" href="#">' + pageNum + '</a></li>');
    $pagination.find('li.page-item').last().before($pageItem);
  }

  // Enable/Disable Previous and Next
  $pagination.find('li:first-child').toggleClass('disabled', currentPage === 1);
  $pagination.find('li:last-child').toggleClass('disabled', currentPage === totalPages);

  // Click handlers
  $pagination.find('li.page-item a').off('click').on('click', function (e) {
    e.preventDefault();
    const text = $(this).text();
    let newPage = currentPage;

    if (text === 'Previous' && currentPage > 1) newPage = currentPage - 1;
    else if (text === 'Next' && currentPage < totalPages) newPage = currentPage + 1;
    else if (!isNaN(parseInt(text))) newPage = parseInt(text);

    if (newPage !== currentPage) {
      start = (newPage - 1) * length;
      if( modulename === 'organizations') {
      OrgList(start); // re-fetch new data
    }else if( modulename === 'groupMembers') {
      groupMembers(start); // re-fetch new data
    }
    }
  });
}

//check if url is valid
function isValidUrl(url) {
  try {
    new URL(url);
    return true;
  } catch (_) {
    return false;
  }
}