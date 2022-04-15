
$(document).ready(function(){
    //$( "#ebtmaintable" ).wrap( "<div class='engaifii-scroller'></div>" );
      // Add class name & Name Attribute in top search section
    
    $(".versionPanel").hide();
    $(".documentPanel").hide();
    
    // Show Hide Functionality
    $("#summary").click(function(){
        $(".versionPanel").hide();
        $(".documentPanel").hide();
        $(".summaryPanel").show();
        $('#summary').addClass('active');
        $('#document').removeClass('active');
        $('#version').removeClass('active');
        
        
    });
    $("#version").click(function(){
        $(".summaryPanel").hide();
        $(".documentPanel").hide();
        $(".versionPanel").show();
        $('#version').addClass('active');
        $('#summary').removeClass('active');
        $('#document').removeClass('active');
    });

    $("#document").click(function(){
        $(".summaryPanel").hide();
        $(".versionPanel").hide();
        $(".documentPanel").show();
        $('#document').addClass('active');
        $('#summary').removeClass('active');
        
        $('#version').removeClass('active');
        
        
    });



        var p = document.location.pathname;
        if(p == '/accg/engagifii-detail/')
        {
            $(".staffanalysisPanel").show();
            $(".summaryPanel").hide();
        }
        else{ 
            $(".staffanalysisPanel").hide();
            $(".summaryPanel").show();
        }

        
        $(".versionsPanel").hide();
        $(".votesPanel").hide();
        $(".historyPanel").hide();
        $(".quickPanel").hide();
        

    // Show Hide Functionality
    $("#summary").click(function(){
        $(".versionsPanel").hide();
        $(".votesPanel").hide();
        $(".historyPanel").hide();
        $(".quickPanel").hide();
        $(".staffanalysisPanel").hide();
        $(".summaryPanel").show();
        $('.lbt-link').removeClass('active');
        $('#summary').addClass("active");
        
    });


    $("#staffanalysis").click(function(){
        $(".summaryPanel").hide();
        $(".versionsPanel").hide();
        $(".votesPanel").hide();
        $(".historyPanel").hide();
        $(".quickPanel").hide();
        $(".staffanalysisPanel").show();
        $('.lbt-link').removeClass('active');
        $('#staff').addClass("active");
    });

    $("#versions").click(function(){
        $(".summaryPanel").hide();
        $(".votesPanel").hide();
        $(".historyPanel").hide();
        $(".quickPanel").hide();
        $(".staffanalysisPanel").hide();
        $(".versionsPanel").show();
        $('.lbt-link').removeClass('active');
        $('#versions').addClass("active");
        
    });

    $("#votes").click(function(){
        $(".summaryPanel").hide();
        $(".versionsPanel").hide();
        $(".historyPanel").hide();
        $(".quickPanel").hide();
        $(".staffanalysisPanel").hide();
        $(".votesPanel").show();
        $('.lbt-link').removeClass('active');
        $('#votes').addClass("active");
        
    });


    $("#history").click(function(){
        $(".summaryPanel").hide();
        $(".versionsPanel").hide();
        $(".votesPanel").hide();
        $(".quickPanel").hide();
        $(".staffanalysisPanel").hide();
        $(".historyPanel").show();
        $('.lbt-link').removeClass('active');
        $('#history').addClass("active");
        
    });

    $("#quick").click(function(){
        $(".summaryPanel").hide();
        $(".versionsPanel").hide();
        $(".votesPanel").hide();
        $(".historyPanel").hide();
        $(".staffanalysisPanel").hide();
        $(".quickPanel").show();
        $('.lbt-link').removeClass('active');
        $('#quick').addClass("active");
        
    });
    

    $('.panel-title').click(function(){
            $(this).next('.panel-details').slideToggle('slow');
            let icon = $(this).find("svg");
            console.log(icon);
            icon.toggleClass("fa-angle-up fa-angle-down");
    })

    

});

    function __addExtraDiv(title)
    {
      var span_Ext = $(document).find(".select2-search").find("p.engwarpper").length;
      if(span_Ext<1)
      {  
          $(document).find(".select2-search").prepend("<p class=\"engwarpper\"> "+title+" </p>");
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

   $(function () { $("[data-toggle = 'tooltip']").tooltip(); });
