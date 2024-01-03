var pluginFilterData = (function () {

    // privates
    var settings = { searchelement: null, itemselectedclass: "yourselectedclass", companyName: "Engagifii Legislation", AuthorName: "Engagifii Legislation",countView: null, countSelected:0 ,clickCallback: null, isBlockChecked:0};

    function applySearchfeature() {
        var element = document.getElementById(settings.searchelement);
        if (element) {
            element.setAttribute('data-selectedblock', settings.isBlockChecked);
            element.className = "form-control form-control-sm input-xs small-css tzsearchinput mb-3";
            element.addEventListener("keyup", function () {
                var searchtitle = this.value.toLowerCase();
                var searchContainer = this.dataset.searchContainer;
                applyfilter(searchContainer, searchtitle);
            });
            initilizeClickEvent(element);
        }

    }

    function initilizeClickEvent(tag_) {
        var searchContainer = tag_.dataset.searchContainer;
        var companyItems = document.getElementsByClassName(searchContainer);
        var items = companyItems[0].getElementsByTagName("li");
        for (var n = 0; n < items.length; ++n) {
            var li_ = items[n];
            li_.className = " tzsearchitem mb-1 " + settings.searchelement; 
            li_.addEventListener("click", selectedelements);
        }

    }
    function updateBlockCount()
    {
        
            
        if(settings.countSelected>0)
        {
            settings.isBlockChecked=1;
        }
        else
        {
            settings.isBlockChecked=0;
        }  
        var element = document.getElementById(settings.searchelement);
        if (element) {
            element.setAttribute('data-selectedblock', settings.isBlockChecked);
        }
    }
    function updateCountView()
    {
        var element = document.getElementById(settings.countView);
        if (element) {
            element.innerHTML  = " (" +settings.countSelected+") ";
        }
              
        var notifier = settings.clickCallback;        
        if(notifier && (typeof notifier === "function")){
            notifier();
        }
        updateBlockCount();
    }

    function selectedelements() {                
        var res = this.classList.toggle(settings.itemselectedclass);
        if (res == true) {
            this.classList.add("deftzselected");
            //settings.countSelected++;
			 settings.countSelected=$(this).siblings('.deftzselected').length+1;
			//console.log($(this).siblings('.deftzselected').length+1);
            this.querySelector("input[type='checkbox']").checked = true;
        }
        else {
            this.classList.remove("deftzselected");
            //settings.countSelected--;
			settings.countSelected=$(this).siblings('.deftzselected').length;
			//console.log($(this).siblings('.deftzselected').length);
            this.querySelector("input[type='checkbox']").checked = false;
        }
       // console.log(settings.countSelected);
        updateCountView();
    }


    function applyfilter(lookupareabyclass, findStr) {
        var companyItems = document.getElementsByClassName(lookupareabyclass);
        var items = companyItems[0].getElementsByTagName("li");

        for (var n = 0; n < items.length; ++n) {
            var li_ = items[n];
            if (typeof li_.dataset !== 'undefined') {
                var searchstr = li_.dataset.title.toLowerCase();
                var res = findAndMatchValue(searchstr, findStr);
                if (res === false) {
                    li_.style.display = "none";
                }
                else {
                    li_.style.display = "block";
                }
            }

        }

    }


    function findAndMatchValue(searchstr, findStr) {
        if (searchstr.toLowerCase().indexOf(findStr) > -1) {
            return true;
        } else {
            return false;
        }
    }



    // Return an object exposed to the public
    return {

        // override the current configuration
        applySearch: function (newConfig) {

            if (typeof newConfig === "object") {
                settings = Object.assign({}, settings, newConfig); // values in config override values in defaults
                //console.log(settings);
                applySearchfeature();
            }
        },
        showCount:function(){

        }
        ,


    };

    


});


