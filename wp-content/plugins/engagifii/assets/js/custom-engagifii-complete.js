jQuery(document).ready(function(){
    jQuery( "#ebtmaintable" ).wrap( "<div class='engaifii-scroller'></div>" );
    // Add class name & Name Attribute in top search section
        jQuery(".summaryPanel").show();
        jQuery(".versionPanel").hide();
        jQuery(".votesPanel").hide();
        jQuery(".historyPanel").hide();
        jQuery(".quickPanel").hide();
        jQuery(".staffPanel").hide();

    // Show Hide Functionality
    jQuery("#summary").click(function(){
        jQuery(".versionPanel").hide();
        jQuery(".votesPanel").hide();
        jQuery(".historyPanel").hide();
        jQuery(".quickPanel").hide();
        jQuery(".staffPanel").hide();
        jQuery(".summaryPanel").show();
        
    });
    jQuery("#staff").click(function(){
        jQuery(".summaryPanel").hide();
        jQuery(".versionPanel").hide();
        jQuery(".votesPanel").hide();
        jQuery(".historyPanel").hide();
        jQuery(".quickPanel").hide();
        jQuery(".staffPanel").show();
    });

    jQuery("#version").click(function(){
        jQuery(".summaryPanel").hide();
        jQuery(".votesPanel").hide();
        jQuery(".historyPanel").hide();
        jQuery(".quickPanel").hide();
        jQuery(".staffPanel").hide();
        jQuery(".versionPanel").show();
        
    });

    jQuery("#votes").click(function(){
        jQuery(".summaryPanel").hide();
        jQuery(".versionPanel").hide();
        jQuery(".historyPanel").hide();
        jQuery(".quickPanel").hide();
        jQuery(".staffPanel").hide();
        jQuery(".votesPanel").show();
        
    });


    jQuery("#history").click(function(){
        jQuery(".summaryPanel").hide();
        jQuery(".versionPanel").hide();
        jQuery(".votesPanel").hide();
        jQuery(".quickPanel").hide();
        jQuery(".staffPanel").hide();
        jQuery(".historyPanel").show();
        
    });

    jQuery("#quick").click(function(){
        jQuery(".summaryPanel").hide();
        jQuery(".versionPanel").hide();
        jQuery(".votesPanel").hide();
        jQuery(".historyPanel").hide();
        jQuery(".staffPanel").hide();
        jQuery(".quickPanel").show();
        
    });
});