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
	height: 95px;
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
height: 490px;	
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
        padding: 1rem 1rem 1rem .4rem;
}

	</style>
  <?php
    $obj      =  new Engagifii_API();
    $date           =   date('Y-m-d');
    $instructor = $obj->classAllInstructors($date);
    $classes = $obj->getAllClassCourses($date);
  ?>
  
	
 <div id="calendar_div" class="position-relative container-fluid">
        
    </div>
    <div id="calendarsearch_div" class="position-relative container-fluid" style="display:none">
    
    <?php echo do_shortcode('[classes-calendar-search]'); ?>
    
        </div>
    <script>
 if(localStorage.getItem("view_mode")=='list'){
		  $('#calendar_div').hide();
	} 
$(document).ready(function() {
	
            $('[data-toggle="tooltip"]').tooltip();
        });
    
        var courses = '';
        var instructor = '';
        var fv = 0;
        var day = '';
        var calendar_view = 'month';
        /*$('.filter-icon-cal').click(function(e){
        e.stopPropagation();
        $('.filter-border-cal').show();
        $('.filter-area-cal').toggleClass('d-none');
        
    });*/
        function getCalendarClassName(target_div, year, month, day){
            $.ajax({
                type:'POST',
                url: engagifiiUrl_ajaxurl,
         
                data:{
              	action:'getcalendarclassname',  
               	year: year,
               	month: month,
               	day:day,
                courses : courses,
                instructors : instructor,  

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

        function getEvents(date, data){
          var collection = data;
            $(".calendar__day:not(.today)").removeClass('active bg-primary text-white');
            $("[data-event="+date+"]:not(.today)").addClass('active bg-primary text-white');
            var response = $("[data-event="+date+"]").data('start');
            const options = { weekday: 'long', month: 'long', day: 'numeric', year:'numeric' };
            
		   var event_date = new Date(date).toUTCString(); 
		   const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];
		   const weekday = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
		   var selectedDate = '0'+new Date(date).getUTCDate();
		   selectedDate = weekday[new Date(date).getUTCDay()].slice(0,3)+' - '+month[new Date(date).getUTCMonth()].slice(0,3)+' '+selectedDate.slice(-2) +', '+ new Date(date).getUTCFullYear();
		  // console.log(selectedDate);
		   day = event_date.slice(5,7);
            var class_html = '<h4 class="sidebar__heading text-center py-3" title="'+event_date.slice(0,17)+'">'+selectedDate+'</h4>'; 
            
            if(response!= 'no-data') {
                var obj = JSON.parse(JSON.stringify(response));
                
						var overflow = '';
						if(calendar_view == 'month') {
							var overflow = 'scroll position-relative';	
						}
				        class_html += '<div class="row '+overflow+'">';
                obj.forEach(function(value, index) {
                	var tags = '';
                    if(value['classTag'].length > 1)  {
                    	var more = parseInt(value['classTag'].length) - 1;
						          tags += value['classTag'][0]+'<span class="dropup ml-2"><span style="cursor:pointer" class="tag badge badge-dark badge-pill" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> +'+more+'</span>';
						          tags += '<ul class="dropdown-menu dropdown-menu-left py-0">';
						  	     value['classTag'].forEach(function(tag, key) {
						    		    tags += '<li class="p-1 border-bottom small">'+tag+'</li>';
						 	        })
						          tags += '</ul></span>';
                    } else if(value['classTag'].length == 0) {
						
						tags = '<i class="small">N/A</i>';
						} else { 
						          tags = value['classTag']; 
					         }

                   var html_class = 'col-12';
                   if(calendar_view == 'day' || calendar_view == 'week')
                   {
                      html_class = 'col-md-4';
                      $('#event_list').removeClass('col-md-3').addClass('col-12');
                      
                   }
                   else if(calendar_view == 'month'){
                      $('#event_list').removeClass('col-12').addClass('col-md-3');
					   html_class = 'col-12';
                   }
                  
                    class_html += '<div class="'+html_class+' mb-3  "><div class="box border rounded h-100 class-text bg-light p-2"><div class=" text-left d-flex align-items-center pb-2"><img src="'+value['icon']+'" class="img-fluid img-icon-lg mr-2">'+value["title"]+'</div><div class=" text-left">'+value["classTime"]+'</div><table class="table table-borderless table-sm text-left"><tr><td class="text-muted">Duration: </td><td>'+value["classDuration"]+'</td></tr><tr><td class="text-muted">Type: </td><td>'+value["objectType"]+'</td></tr><tr><td class=" text-muted">Credit Hours: </td><td>'+value["hours"]+'</td></tr><tr><td class=" text-muted">Tags: </td><td>'+tags+'</td></tr></table><div class="text-center">'+value['viewdetails']+' '+value['register']+'</div></div></div>';
					

                });
				class_html += '</div>';
            } else{ 
				class_html += '<div class="box"><h5 class="col-12 text-center text-muted"><em>Oops! No classes available for selected date.</em></h5></div>'; 
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
		

           $('[data-toggle="tooltip"]').tooltip() ;  
        }
		
		

        /*$('.clear-all-cal').click(function(){
            $('input[type=checkbox]').prop('checked',false);
            $('#countFilterResultCal').html(' ');
            fv = 0;
          $('.filter-icon-cal').removeClass('active');  

            courses = '';
            instructor = '';
            getCalendarClassName('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), '');


      });*/

        $(document).on({
    		ajaxStart: function(){
				$("#calendar_div").prepend('<div class="loader"><span class="spinner"></span></div>');
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
              		action:'getcalendarclassname', 
              		year: yyyy,
               		month: mm,
               		day:dd,

          		},
                success:function(html){
                    $('#calendar_div').html(html);
                    
                    getEvents(today);
           			$('button#month').addClass('bg-primary text-white ').removeClass('bg-white');
           			$('#dayView').hide();
                $('#weekView').hide();


                }
            });
        });

        $('body').on('change', '.month-dropdown', function(){
                getCalendarClassName('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
            });
        $('body').on('change', '.year-dropdown', function(){
                getCalendarClassName('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
            });

        $('body').on('click', '#month', function(){
        	calendar_view = 'month';
        	getCalendarClassName('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
        	
        });
        $('body').on('click', '#day', function(){
        	calendar_view = 'day';
          getCalendarClassName('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
        });

        $('body').on('click', '#week', function(){
          calendar_view = 'week';
          
          getCalendarClassName('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(),day);
        });
		
		$('body').on('click', '#class-pop', function(e){
          $(this).parent().siblings('.class-pop').fadeIn();
          return false;
        });
		$(document).on('click', function (e) {
			if(!$('.modal').is(':visible')){
 				$('.class-pop').fadeOut();
			}
});
$(document).on('click', '.class-pop', function (e) {
  e.stopPropagation();
});

         /*$('#apply-filter-data-cal').click(function(){
            courses = $.map($('input[name="courseClassCal[]"]:checked'), function(c){return c.value; });
            instructor = $.map($('input[name="courseInstrutorCal[]"]:checked'), function(c){return c.value; });
            
            $(".filter-area-cal").toggleClass('d-none');
            getCalendarClassName('calendar_div', $('.year-dropdown').val(), $('.month-dropdown').val(), day);

        });*/

        // Calendar search //
        // $('#apply-filter-search-cal').click(function(e){
        //    e.preventDefault(); 
        //   var search = $('#calendar-search').val();
        //   $("#search-demo").val(search);  
        //     alert(search);
        //     $('#calendar_div').hide();
        //     $('#calendar_filter').hide();
        //     $("#calendarsearch_div").show();
        // });

        //Calendar search ends here
        /*$('.heading-title').click(function(){$(this).next('.content-area-cal').toggleClass('d-none')});
        $(document).on('click', function (e) {
            var container = $(".filter-border-cal");
            // If the target of the click isn't the container
            //console.log(e.target.className);
            if(!container.is(e.target) && container.has(e.target).length === 0  && (e.target.className == 'prev available' || e.target.className == 'next available' )){
              container.hide();
              $('.filter-area-cal').addClass('d-none');
            }
        });

      	$('.filter-list-cal input[type=checkbox]').change(function(){
        	countFilterDataCal();
      	});
    	function countFilterDataCal()
    	{

      		var courses = $.map($('input[name="courseClassCal[]"]:checked'), function(c){return c.value; });
      		var instructor = $.map($('input[name="courseInstrutorCal[]"]:checked'), function(c){return c.value; });
          	$.ajax({
          		type : "post",
          		url: engagifiiUrl_ajaxurl,
          		data:{
              		action:'classcountdata',
              		courses : courses,
              		instructors : instructor,  
          		},
          		success: function(response) {       
            		var element  = document.getElementById("countFilterResultCal");
            		if(element)
            		{
              			element.innerHTML = " ("+response.api_response +")";
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
 		});*/

    

   

    </script>

  