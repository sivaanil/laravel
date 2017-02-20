@if (isset($navBarSettings))
    <div style="clear:both;"></div>
    <div id="main-header">
        <span id="main-header-text">
            @if (getenv('C2_SERVER_TYPE') == 'siteportal')
                SitePortal &reg; &nbsp;Unified Development Preview
            @endif
            @if (getenv('C2_SERVER_TYPE') == 'sitegate')
                <img src="img/img/header_logo_sitegate.png" class="header-logo-sitegate">
            @endif
        </span>
    </div>
    @include("menuBreadcrumbs")
    <div id="theMenuDiv">
        <div id="jqxSearchMenu" style='float:right; border:none; '>
            <ul id="theSearchMenu">
                <li id="theSearchMenuli" style='border:none; padding:0px'>
                    <input type="text" style="width: 156px" id="theSearchMenuInput" onclick="openSearch()">
                    <img src="{!!asset('img/icons/zoomedIn.png')!!}" onclick="openSearch()"/>
                    <input type="hidden" id="e3" style="visibility: hidden; width: 200px; top:-17px; left: -156px"/>
                </li>
            </ul>
        </div>
        <div ng-controller="menuCtrl" style="height:100%" id="jqxMainMenu">
            <menu-component
                    id="jqxMainMenu"
                    divid="jqxMainMenu"
                    source="menu"
                    theme="custom"
                    nodeid="{!!$nodeId!!}"
                    activepage="{!!$activePage!!}"
                    dataurl="/refreshMenu"
                    style="display: table-cell; float:right; border:none; vertical-align: middle;"
                    >
            </menu-component>
        </div>
    </div>

    <div style="clear:both;"></div>

    <div style="clear:both;"></div>
    <script id="script_e3">
        var menuWidth = 0;
        var largestMenuWidth = 0;
        var nodeChanged = false;
        var searchToggle = 0;
        var searchNode;
        var minimumSearchLength = 3;
        var viewModel = new Array();
        var currentData;


        $(document).ready(function () {
            
            searchNode = {{$nodeId}};
            {{--Init searching node for pages other than node selection--}}
            initSearch();
            $("#jqxSearchMenu").jqxMenu({autoSizeMainItems: true});

            initScrollingBreadcrumb();

            //make the selected menu option this class jqx-fill-state-pressed-custom
            widthCheckForBreadcrumbButtons();
            //initKnockoutMenu();
            /*var jsonMenuItems = '
            {{'navBarSettings2'}}';
             populateMenu(jsonMenuItems);*/
            //updateMenu(searchNode);
        });


        function initSearch() 
       
            $("#e3").select2({
                name: $('.e3').val(),
                placeholder: "Search",
                minimumInputLength: minimumSearchLength,
                ajax: {

                    url: baseUrl + "/autocomplete",
                    dataType: 'json',
                    quietMillis: 500,
                    data: function (term, page) {
                        if (term.toString().length > 3) {
                            term = $.trim(term);
                            s2id_autogen1_search.value = term;
                        }
                        return {
                            name: term,
                            subDev: subDev.checked,
                            fullTreeSearch: fullTreeSearch.checked,
                            selectedNode: searchNode,
                            selectedNodeSearch: selectedNodeSearch.checked,
                            page_limit: 10, // page size
                            page: page // page number
                        };

                    },
                    results: function (data, page) {
                        {{-- parse the results into the format expected by Select2.
                        since we are using custom formatting functions we do not need to alter remote JSON data--}}
                        var more = page * 10 < data.count;
                        return {results: data.data, more: more};
                    }
                },
                formatResult: nodeFormatResult,
                formatSelection: nodeFormatSelection,
                escapeMarkup: function (m) {
                    return m;
                } {{-- we do not want to escape markup since we are displaying html in results--}}
            });
        }
        function callLink(url, target) {
            if (target != undefined) { {{--Launch web will create a new tab disable scanning will navigate to the disable scanning form scan now and enable scanning are just ajax calls--}}
                window.open(url, target);
            } else {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (result) {
                        updateMenu(searchNode);
                        {{--Update the menu for when enable scaning is pressed  --}}
                    }
                });
            }
        }
        function nodeFormatResult(list) {

            var markup = "<table><tr>";

            markup += "<td><div>" + list.name + "</div>";

            markup += "</td></tr></table>";
            return markup;
        }

        function nodeFormatSelection(list) {

            if (list.id !== -1) {
                var str = baseUrl + '/home#/nodeChange/'+list.id;
                window.location.href = str;
                return list.name;
            }
            return;
        }

        function openSearch() {
            {{--There should be better way to do this.
            if the menu is in minimzed mode, openedn then the search is clicked the menu does close
            This closes the menu by restoring the then minimizing it if the width is low enough--}}
            $('#jqxMainMenu').jqxMenu('restore');
            if (($(window).width() - $(jqxSearchMenu).outerWidth()) < menuWidth + 5) {
                $('#jqxMainMenu').jqxMenu('minimize');
            } else {
                $('#jqxMainMenu').jqxMenu('restore');
            }
            $("#e3").select2("open");

            if (document.getElementById("advancedSearchOptions") == null) {
                $("#select2-drop").append('<ul id="advancedSearchOptions"><li style="border-bottom:1px solid #000;">' +
                        '<label for="subDev"><input type="checkbox" name="subDev" value="subDev" id="subDev" onclick="triggerSearchOnChange()">' +
                        '{{trans("menuArea.includesubdevices")}}</label></input></li><li><label for="fullTreeSearch">' +
                        '<input type="radio" name="depth" value="full" id="fullTreeSearch" onclick="triggerSearchOnChange()" checked>{{trans("menuArea.wholetree")}}' +
                        '</label><br><label for="selectedNodeSearch"><input type="radio" name="depth" value="node" id="selectedNodeSearch" onclick="triggerSearchOnChange()">' +
                        '{{trans("menuArea.selectednode")}}</label></input></li><ul>');
            }
        }

        function triggerSearchOnChange() {
            {{--if this select 2 request is accomplished than it should be used https://github.com/ivaynberg/select2/issues/1701--}}
            if (s2id_autogen1_search.value.length >= minimumSearchLength){{{--only manipulate the string if it is going to search--}}
				s2id_autogen1_search.value += searchToggle%2?" ":"\t"; {{--select2 saves the string from the last search so the string needs to be changed. It is trimmed out before sending to php--}}
			}
			searchToggle++;	
			updateSearch();
			return;
		}
		function updateSearch(){
			{{--call the event to trigger search again--}}
			var keyboardEvent = document.createEvent("KeyboardEvent");
			var initMethod = typeof keyboardEvent.initKeyboardEvent !== 'undefined' ? "initKeyboardEvent" : "initKeyEvent";
			
			keyboardEvent[initMethod]("keyup-change", true, true, window,false, false, false, false, 65, 65 );
			s2id_autogen1_search.dispatchEvent(keyboardEvent);

			return;
		}

		$(window).resize(function() {
			waitForFinalEvent(function(){
				widthCheckForBreadcrumbButtons();
			}, 100, "menu");

		});
		var waitForFinalEvent = (function () { //This is also in alarm.js move it to a common js lib
			var timers = {};
			return function (callback, ms, uniqueId) {
			  if (!uniqueId) {
				uniqueId = "Don't call this twice without a uniqueId";
			  }
			  if (timers[uniqueId]) {
				clearTimeout (timers[uniqueId]);
			  }
			  timers[uniqueId] = setTimeout(callback, ms);
			};
		  })();
		  
		$('#jqxMainMenu').on('itemclick', function (event) {
			{{--moves menu off the right side of screen--}}
			if(document.getElementById("menuWrapperjqxMainMenu") != null){
				document.getElementById("menuWrapperjqxMainMenu").style["left"] = $(window).width()-($('#jqxSearchMenu').outerWidth(true) + largestMenuWidth)+"px";
			}
	   });
	   function updateMenu(nodeId){
				$('#jqxMainMenu').jqxMenu('restore'); //makes the menu be able to be minimized after being refreshed

				nodeChanged = true;
	   }
	   
	   function initScrollingBreadcrumb(){
			$("#makeMeScrollable").smoothTouchScroll({ 
				startAtElementId: "lastCrumb", 
			});
	   }
	   function widthCheckForBreadcrumbButtons(){
		   if(document.getElementById('crumb0')==null ){ {{--There is only 1 crumb so no buttons--}}
			   document.getElementById("makeMeScrollable").style.marginLeft="0px";
				document.getElementById("makeMeScrollable").style.marginRight="0px";
				document.getElementById("breadcrumbButtonLeftDiv").style.display="none";
				document.getElementById("breadcrumbButtonRightDiv").style.display="none";
			   return;
		   }else if(isElementInViewport(crumb0) && isElementInViewport(lastCrumb)){
				document.getElementById("makeMeScrollable").style.marginLeft="0px";
				document.getElementById("makeMeScrollable").style.marginRight="0px";
				document.getElementById("breadcrumbButtonLeftDiv").style.display="none";
				document.getElementById("breadcrumbButtonRightDiv").style.display="none";
				return;
		   }else{ {{-- both crumbs aren't visiable put the buttons in--}}
				document.getElementById("makeMeScrollable").style.marginLeft="37px";
				document.getElementById("makeMeScrollable").style.marginRight="37px";
				document.getElementById("breadcrumbButtonLeftDiv").style.display="inline-block";
				document.getElementById("breadcrumbButtonRightDiv").style.display="inline-block";
			}
	   }
	   
	   function shiftRight(){
		   var i;
		   {{--make sure it is pushed all the way to the right.
		   If we are at the end--}}
		   if(isElementInViewport(lastCrumb)){
				$("#makeMeScrollable").smoothTouchScroll({ 
					startAtElementId: "lastCrumb", 
				});
			   return;
		   }
		   var current = crumb0; {{--Start at the end
				   loop the length of the crumb--}}
			for(i=0; i<crumb0.parentElement.children.length; i++){
				if(isElementInViewport(current)){
					$("#makeMeScrollable").smoothTouchScroll({ 
						startAtElementId: current.nextElementSibling.id.toString(), 
					});
					return;
				}
				current = current.nextElementSibling;
			}
		}
	   
	   function shiftLeft(){
			var i;
			{{--make sure it is pushed all the way to the left.
			If we are at the start--}}
			
			if(document.getElementById('crumb0')==null ){
				{{--if crumb0 doesnt exist then lastCrumb is the only item--}}
				 $("#makeMeScrollable").smoothTouchScroll({ 
					 startAtElementId: "lastCrumb", 
				 });
				return;
			}else if(isElementInViewport(crumb0)){
				 $("#makeMeScrollable").smoothTouchScroll({
					 startAtElementId: "crumb0", 
				 });
				return;
			}
			var current = crumb0; {{--Start at the start
			loop the length of the crumb--}}
	
			for(i=0; i<crumb0.parentElement.children.length; i++){
				if(isElementInViewport(current.nextElementSibling)){
					$("#makeMeScrollable").smoothTouchScroll({ 
						startAtElementId: current.id.toString(),
					});
					return;
				}
				current = current.nextElementSibling;
			}
		}
	   
	   function isElementInViewport (el) {

		{{--special bonus for those using jQuery--}}
			if (el instanceof jQuery) {
				el = el[0];
			}

			var rect = el.getBoundingClientRect();

			return (
				rect.top >= 0 &&
				rect.left >= 0 &&
				rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
				rect.right <= (window.innerWidth || document.documentElement.clientWidth) 
			);
		}
	</script>
@endif