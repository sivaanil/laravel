function initNodeActions(firstLoad) {
	var selectedNodeId = $('input[name=group1]:checked').val();
	
	$('#nodeSelectionContainer input:radio').hide().each(function() {
		$(this).attr('data-radio-fx', this.name);
		var label = $("label[for=" + '"' + this.id + '"' + "]").text();
        if (firstLoad) {
            var radioClass = 'sev_loading';
        } else {
            var radioClass = $(this).attr('class');
        }
		$('<a ' + (label != '' ? 'title=" ' + label + ' "' : '' ) + ' data-radio-fx="'+this.name+'" class="radio-fx" style=" display:block;'+ ($(this).attr('checked')=="checked" ? ' background-color:#C1DCFC;  "':' background-color:#FFFFFF; "')+ '>'+
			'<span class="radio ' + radioClass +'"class="radio-fx" style=" display:block;'+ ($(this).attr('checked')=="checked" ? ' background-color:#C1DCFC;  "':' background-color:#FFFFFF; "')+'></span></a>').insertAfter(this);

	});
	$('#nodeSelectionContainer a.radio-fx').on('click', function(e) {
		//e.preventDefault();
		var unique = $(this).attr('data-radio-fx');
		//Remove blue highlight after a node has been selected 
		$("#nodeSelectionContainer a[data-radio-fx='"+unique+"'] span").parent().parent().next().children(true).each(function(){
                    $(this).css('background-color', "#FFFFFF");
		});
		$("#nodeSelectionContainer a[data-radio-fx='"+unique+"'] span").parent().parent().next().css('background-color', "#FFFFFF");
		$("#nodeSelectionContainer a[data-radio-fx='"+unique+"'] span").parent().css('background-color', "#FFFFFF");
		$("#nodeSelectionContainer a[data-radio-fx='"+unique+"'] span").css('background-color', "#FFFFFF");
		// Put the icon back in after clicking a button 
		$($("#nodeSelectionContainer a[data-radio-fx='"+unique+"'] span")).each(function() {
                    $(this).attr('class',$(this).attr('class').toString().replace("-checked", ""))
		});
		
		$("#nodeSelectionContainer :radio[data-radio-fx='"+unique+"']").attr('checked',false);
		
		
		//Color selected button
		$(this).find('span').parent().css('background-color', "#C1DCFC");
                //console.log($(this).find('span').parent().attr('style').toString().replace("#FFFFFF", "#C1DCFC"));
                $(this).find('span').parent().parent().next().css('background-color', "#C1DCFC");
		$(this).find('span').parent().parent().next().children(true).each(function(){
                    $(this).css('background-color', "#C1DCFC");
		});
		//$(this).find('span').parent().parent().parent().attr('style', $(this).find('span').parent().parent().next().attr('style').toString().replace("#FFFFFF", "#C1DCFC"));
		$(this).find('span').css('background-color', "#C1DCFC");
		$(this).prev('input:radio').attr('checked',true);
		var selectedNodeId = $(this).prev().attr('value');
		updateMenu(selectedNodeId);
	}).on('keydown', function(e) {
		if ((e.keyCode ? e.keyCode : e.which) == 32) {
                    $(this).trigger('click');
		}
	});    
}

function fetchSeverities(nodeId) {
   $.ajax({
        type: 'GET',
        url:  baseUrl + "/nodesWithSeverities/" + nodeId,
        success: function(result) {
            var selectedNodeId = $('input[name=group1]:checked').val();
            $('#nodeSelectionContainer').replaceWith(result);
            initNodeActions(false);
        } 
    });
}