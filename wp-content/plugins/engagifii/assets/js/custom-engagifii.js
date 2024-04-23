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
  $(this).html( '<div class="position-relative input-group search-dt flex-nowrap"><input type="text" id="searchTitle" placeholder="'+placeholder+'" class="form-control form-control-sm pr-4 shadow-none" value=""/><div class="input-group-append"><span class="input-group-text px-1 bg-white rounded-right" ><i class="fal fa-search d-none"></i></span></div><button type="button" class="clear-search btn position-absolute p-1 px-2 shadow-none h-100" style="right:21px; top:0px; z-index:5;display:none"><i class="fal fa-times"></i></button></div>' );
	$( 'input', this ).keyup(delay(function (e) {
		var titlesearch = this.value;
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