<style> 
@media screen and (min-width: 55em) {
  .calendar-contain {
    margin: auto;
    top: 5%;
  }
}


.calendar__sidebar .content {
  padding: 2rem 1.5rem 2rem 4rem;
  color: #040605;
}

.sidebar__list {
  list-style: none;
  margin: 0;
  padding-left: 1rem;
  padding-right: 1rem;
}

.sidebar__list-item {
  margin: 1.2rem 0;
  color: #2d4338;
  font-weight: 100;
  font-size: 1rem;
}

.list-item__time {
  display: inline-block;
  /*width: 60px;*/
}
@media screen and (min-width: 55em) {
  .list-item__time {
    margin-right: 1rem;
  }
}

.sidebar__list-item--complete {
  color: rgba(4, 6, 5, 0.3);
}
.sidebar__list-item--complete .list-item__time {
  color: rgba(4, 6, 5, 0.3);
}




.calendar__heading-highlight {
  color: #2d444a;
  font-weight: 900;
}

.top-bar__days {
	max-width: calc(100%/7);
	color: #2d4338;
	flex: 0 0 calc(100%/7);
}


.calendar__week .inactive .calendar__date,
.calendar__week .inactive .task-count {
  color: #c6c6c6;
}
.calendar__day {
	max-width: calc(100%/7);
	flex: 0 0 calc(100%/7);
	height: 105px;
	cursor: pointer;
}
.calendar-dark .calendar__day:not(.today), .calendar-dark .top-bar__days, .calendar-dark h3, .calendar-dark h4, .calendar-dark .box div{
	color:white;
}
.calendar-dark .box span.text-muted {
	color: white;
	opacity: 0.6;
}
.calendar__day.no-event {
	cursor: auto;
	pointer-events: none;
}

.calendar__day.no-event * {
	opacity: 0.5;
}
#calendar_div .loader {
	z-index: 2;
	background: rgba(255,255,255,0.8);
	left: 0;
	top: 0;
}

.view-mode {
	z-index: 2;
}
.mCustomScrollBox {
	width: 100%;
}
@media screen and (min-width: 1200px) {
.calendar__day:not(.today):hover {
    background-color:#F7F9FC ;
}
.calendar-dark .calendar__day:not(.today):hover {
    color:inherit ;
}
}
@media screen and (min-width: 768px) {
.scroll.mCustomScrollbar {
height: 605px;	
}
}
@media screen and (max-width: 992px) {
.top-bar__days, .calendar__day {
	font-size: 13px;
}
.calendar__day {
	height: 70px;
}
aside .box {
	font-size: 13px;
}
}
@media screen and (max-width: 767px) {
#monthView {
	max-width: 369px;
	margin: auto;
}
#monthView .calendar__day {
	height: 49px;
}
.calendar__task {
	font-size: 10px;
	white-space: nowrap;
	width: 90%;
	text-overflow: ellipsis;
	overflow: hidden;
	margin-left: auto;
	margin-right: auto;
}

}
#event_list .scroll > div.last ~ div {
	display: none;
}


.modal-header {
        padding: 1rem .4rem;
        justify-content: flex-start;
}
	</style>
  <?php
    $obj      =  new Engagifii_API();
    $date           =   date('Y-m-d');
    $tags = $obj->eventsAllTags($date);
    $options = get_option('ebt_api_settings');
	$events_visible_column_list   =  array();
	if($options['events_visible_column_list']){
    $events_visible_column_list = $options['events_visible_column_list'];
	}
   // print_r($tags);

    
   
  ?>


 <div id="calendar_div" class="position-relative container-fluid gg">
        
    </div>
  	<div id="calendarsearch_div" class="position-relative container-fluid" style="display:none">
    
    <?php echo do_shortcode('[events-calendar-search]'); ?>
    
        </div>
    <script>
      
        //var courses = '';
        var tags = '';
        var fv = 0;
        var day = '';
        var calendar_view = 'month';
        $('.filter-icon-cal').click(function(e){
        e.stopPropagation();
        $('.filter-border-cal').show();
        $('.filter-area-cal').toggleClass('d-none');
        
    });
        function getEventsCalendar(target_div, year, month, day){
            $.ajax({
                type:'POST',
                url: engagifiiUrl_ajaxurl,
         
                data:{
              	action:'geteventscalendar',  
               	year: year,
               	month: month,
               	day:day,
                //courses : courses,
                tags : tags,  

          		},
                success:function(html){
                    $('#'+target_div).html(html);
                    $('.calendar__days').hide();
                    $('#'+calendar_view).addClass('bg-primary text-white').removeClass('bg-white');
                    var date = year+'-'+month+'-'+day;
                    if(calendar_view == 'day'){ $('#event_list').hide();}
                    getEvents(date);
           			    $('#'+calendar_view+'View').show();


                }
            });
        }

        function getEvents(date){
          //console.log(date);
            
            $(".calendar__day:not(.today)").removeClass('active bg-primary text-white');
            $("[data-event="+date+"]:not(.today)").addClass('active bg-primary text-white');
            var response = $("[data-event="+date+"]").data('start');
            const options = { weekday: 'long', month: 'long', day: 'numeric', year:'numeric' };
            
            var event_date = new Date(date).toUTCString(); 
		        day = event_date.slice(5,7);
            // var event_date = new Date(date);
            // day = String(event_date.getDate()).padStart(2, '0');
            // event_date = event_date.toLocaleDateString(undefined, options);
            var class_html = '<h4 class="sidebar__heading text-center py-3">'+event_date.slice(0,17)+'</h4>';
            
            //Get Class data and print here
            if(response!= 'no-data') {
                var obj = JSON.parse(JSON.stringify(response));
                //console.log(obj);
                var overflow = '';
                if(calendar_view == 'month') {
                  var overflow = 'scroll position-relative';	
                }
				        class_html += '<div class="row '+overflow+'">';
                obj.forEach(function(value, index) {
                	var tags = '';
                    if(value['endorsementTag'].length > 1)  {
                    	var more = parseInt(value['endorsementTag'].length) - 1;
                    	tags += '<div class="btn-group">';
						          tags += '<div class="px-1">'+value['endorsementTag'][0]+'</div><div class="tag text-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> ..'+more+' more</div>';
						          tags += '<ul class="dropdown-menu dropdown-menu-right w-100">';
						  	     value['endorsementTag'].forEach(function(tag, key) {
						    		    tags += '<li class="dropdown-item text-break" style="white-space:normal;">'+tag+'</li>';
						 	        })
						          tags += '</ul>';
						          tags += '</div>';
                    } else { 
						          tags = value['endorsementTag']; 
					         }

                   var html_class = 'col-12';
                   if(calendar_view == 'day')
                   {
                      html_class = 'col-lg-6';
                      
                   }
                   else if(calendar_view == 'month'){
                      $('#event_list').removeClass('col-md-3 col-12');
                      $('#event_list').addClass('col-md-3');
                   }
                   else if(calendar_view == 'week')
                   {
					   html_class = 'col-lg-6';
                      $('#event_list').removeClass('col-md-3');
                      $('#event_list').addClass('col-12');
                   }
                    class_html += '<div class="'+html_class+' mb-3  "><div class="box border rounded h-100 class-text bg-light"><div class="col-12 m-auto p-1 text-left d-flex align-items-center"><img src="'+value['icon']+'" class="img-fluid img-icon-lg mr-2">'+value['title']+'</div><?php if(in_array('startDateTime', $events_visible_column_list)) { ?><div class="col-12 py-1 text-left">'+value["schedule"]+'</div><?php } if(in_array('eventType', $events_visible_column_list)){ ?><div class="col-12 py-1 text-left"><span class="text-muted">Type: </span><span>'+value["objectType"]+'</span></div><?php } ?> <div class="col-12 py-1 text-left"><span class=" text-muted">Price: $</span><span>'+value["price"]+'</span></div><?php if(in_array('tags', $events_visible_column_list)){ ?><div class="col-12 py-1 text-left"><span class=" text-muted">Tags: </span><span>'+tags+'</span></div><?php } ?><div class="col-12 text-center py-3">'+value['viewdetails']+' <?php if(in_array('register', $events_visible_column_list)){ ?> '+value['register']+'</div><?php } ?></div></div>';

                });
				class_html += '</div>';
            } else{ 
				class_html += '<div class="box"><h5 class="col-12 text-center opacity-50">Oops! No events found for selected date.</h5></div>'; 
			}
            
            $('#event_list').html(class_html);
            $('#today_event').html(class_html);
			if ($(window).width() > 767) {
    jQuery("#event_list .scroll").mCustomScrollbar({
			 scrollButtons:{enable:true},
					theme:"minimal-dark",
					scrollbarPosition:"outside"
					});
} else {
	var shown = 4;
	if(jQuery("#event_list .scroll > div").length>shown){
		$("#event_list .scroll").append('<button class="btn bg-primary text-white border w-100 sm mx-3">Show more...</button>');
		jQuery("#event_list .scroll > div").eq(shown-1).addClass('last');	
	}
	jQuery("#event_list .scroll> .btn").on('click', function(){
		var last = jQuery("#event_list .scroll > .last").index();
		jQuery("#event_list .scroll > div").removeClass('last').eq(last+shown).addClass('last');
		if(!jQuery("#event_list .scroll > div").hasClass('last')){
			jQuery(this).hide();	
		}
	});
}
		

            
        }
		
		

       /* $('.clear-all-cal').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#countFilterResultCal').html(' ');
            fv = 0;
          $('.filter-icon-cal').removeClass('active');  

            courses = '';
            tags = '';
            getEventsCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), '');


      })*/

        $(document).on({
    		ajaxStart: function(){
				$("#calendar_div").prepend('<div class="loader "><div class="spinner" role="status"><span class="sr-only">Loading...</span></div></div>');
    		},
    		ajaxStop: function(){ 
				$("#calendar_div > .loader").remove();
    		}    
		});

        $(document).ready(function(){
           
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;

           	$.ajax({
                type:'POST',
                url: engagifiiUrl_ajaxurl,
                data:{
              		action:'geteventscalendar', 
              		year: yyyy,
               		month: mm,
               		day:dd,

          		},
                success:function(html){
                  //console.log(html);
                    $('#calendar_div').html(html);
                    
                    getEvents(today);
           			$('button#month').addClass('bg-primary text-white ').removeClass('bg-white');
           			$('#dayView').hide();
                $('#weekView').hide();


                }
            });
        });

        $('body').on('change', '.month-dropdown', function(){
          getEventsCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
            });
        $('body').on('change', '.year-dropdown', function(){
          getEventsCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
            });

        $('body').on('click', '#month', function(){
        	calendar_view = 'month';
        	getEventsCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
        	
        })
        $('body').on('click', '#day', function(){
        	calendar_view = 'day';
          getEventsCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
        })

        $('body').on('click', '#week', function(){
          calendar_view = 'week';
          
          getEventsCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
        })

       <?php /*?>  $('#apply-filter-data-cal').click(function(){
            //courses = $.map($('input[name="courseClassCal[]"]:checked'), function(c){return c.value; });
            tags = $.map($('input[name="endorsementTags[]"]:checked'), function(c){ return c.value; });
            
            $(".filter-area-cal").toggleClass('d-none');
            getEventsCalendar('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), day);

        });
        $('.heading-title').click(function(){$(this).next('.content-area-cal').toggleClass('d-none')});
        $(document).on('click', function (e) {
            var container = $(".filter-border-cal");
            // If the target of the click isn't the container
            console.log(e.target.className);
            if(!container.is(e.target) && container.has(e.target).length === 0  && (e.target.className == 'prev available' || e.target.className == 'next available' )){
              container.hide();
              $('.filter-area-cal').addClass('d-none');
            }
        });

      	$('.filter-list-cal input[type=checkbox]').change(function(){
        	countFilterDataCal();
      	})
    	function countFilterDataCal()
    	{

      		//var courses = $.map($('input[name="courseClassCal[]"]:checked'), function(c){return c.value; });
      		var tags = $.map($('input[name="endorsementTags[]"]:checked'), function(c){alert (c.value); return c.value; });
          	$.ajax({
          		type : "post",
          		url: engagifiiUrl_ajaxurl,
          		data:{
              		action:'eventfiltercountdata',
              		//courses : courses,
              		tags : tags,  
          		},
          		success: function(response) {      
               console.log(tags); 
            		var element  = document.getElementById("countFilterResultCal");
            		if(element)
            		{
              			element.innerHTML = " ("+response.api_response +")";
                    console.log(response.api_response);
            		}    
          		}
        	});
      	}

       	$("#apply-filter-data-cal").click(function () {
 			        $('.filter-list-cal').each(function() {
                  if ($(this).find('input[type=checkbox]').is(':checked')) {
                    $(this).addClass('checked');
                  } else {
                    $(this).removeClass('checked');
                  }
  		      	});
  			    fv = $('.filter-list-cal.checked').length;
              if(fv>0){
                $('.filter-icon-cal').addClass('active'); 
                $('.filter-icon-cal span').text(fv); 
              } else {
                $('.filter-icon-cal').removeClass('active');  
              }
 		});<?php */?>



    </script>
