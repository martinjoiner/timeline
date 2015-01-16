
var earliestdate = "1355961600"; // Todays timestamp by default

function duration(start,end,unit){
	var difSecs = end - start;
	var difHours = difSecs / 3600;
	var difDays = difHours / 24;
	var difWeeks = difDays / 7;
	switch(unit){
        case 'secs': return difSecs; break;
        case 'days': return difDays; break;
			case 'weeks': return difWeeks; break;
	}
}


$('.eventinput i').click( 	function(){ $('.eventinputwrap').addClass('EIWcollapsed') 	 } );
$('.addwrap i').click( 		function(){ $('.eventinputwrap').removeClass('EIWcollapsed') } );

$(".element").mouseover( function(){$(this).children('.date').show() }).mouseout(function(){$(this).children('.date').hide() });
$(".element").each( function(){ 
    if($(this).data('start') < earliestdate){
        earliestdate = $(this).data('start');
    }
});
rescale();
$(".resizable").each( function(){
	$(this).css('height',$(this).data('height')).children('.element').css('height',$(this).data('height')-24);
});
$( ".resizable" ).resizable({
    start:function () {
      
    },
    stop:function () {
     
    },
    resize:function () {
	  $(this).children('.element').css('height',parseInt($(this).css('height').substring(0,$(this).css('height').length-2))-24);
	  
    }
  });

$('#scale').change( function(){ rescale() } );
$('#offset').change( function(){ rescale() } );


function rescale(){
    var scale = $('#scale').attr('value')/10;
	var offset = $('#offset').attr('value')*scale*10;
	$(".element").each( function() {
        $(this).data('duration',duration($(this).data('start'),$(this).data('end'),'weeks'));
		$(this).css('width',$(this).data('duration')*scale);
        $(this).data('left',duration(earliestdate,$(this).data('start'),'weeks'));
		$(this).css({left:($(this).data('left')*scale)-offset+'px'});
	});
    $(".newyear").each( function(){
        $(this).data('left',duration(earliestdate,$(this).data('start'),'weeks'));
        $(this).css({left:($(this).data('left')*scale)-offset+'px'});
    })
}
