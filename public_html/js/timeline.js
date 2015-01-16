
var config = {};
config.windowWidth = 0;
config.totalLifeDays = 0; 
config.pixelsPerDay = 10;
config.zoom = 1; 
config.strLifeStart = '';
config.offset = 0;

var earliestdate = "1355961600"; // Todays timestamp by default


// Takes 2 timestamps and returns time period between them in secs, days or weeks
function duration(start,end,unit){

    if( typeof unit === 'undefined' ){
        unit = 'days';
    }

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


// Takes a string in the format YYYY-MM-DD and returns a Date object
function parseDate(str) {
    var ymd = str.split('-');
    return new Date( ymd[0], ymd[1]-1, ymd[2] );
}


// Takes 2 date objects and returns the number of days between them
function dayDiffObj( objDate1, objDate2 ){
    return ( objDate2 - objDate1 ) / ( 1000 * 60 * 60 * 24 );
}


// Takes 2 date strings in the format YYYY-MM-DD and returns the number of days between them
function dayDiffStr( strDate1, strDate2 ){
    return dayDiffObj( parseDate(strDate1), parseDate(strDate2) );
}


$('.eventInput i').click( function(){ 
    $('.eventInputwrap').addClass('EIWcollapsed');
    $('.lifeBoard').removeClass('blurred');	 
});

$('.addwrap i').click( function(){ 
    $('.eventInputwrap').removeClass('EIWcollapsed');
    $('.lifeBoard').addClass('blurred');     
});


$(".element").each( function(){ 
    if($(this).data('start') < earliestdate){
        earliestdate = $(this).data('start');
    }
});

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

$('#zoom, #offset').change( rescale );


// Performs AJAX call to server
function fetchData(){

    $.ajax({
        type: "GET",
        url: "/GET/",
        dataType: "json"
    }).done(function(data) {

        config.totalLifeDays = data.lifeDays;
        config.strLifeStart = data.lifeStart;
        config.strLifeEnd = data.lifeEnd;
        rescale();
        
    });

}


// Calculates size and position of elements based on user data
function rescale(){

    // Get the total available pixels across the width of the viewport
    config.windowWidth = $(window).width();

    var zoom = ( $('#zoom').val() / 10 ) + 1;

    // Set how many pixels a day should take up
    config.pixelsPerDay = ( config.windowWidth / config.totalLifeDays ) * zoom;


	var offsetDays = $('#offset').val() * 7;
    var offsetPixels = offsetDays * config.pixelsPerDay;

    $(".lifeBoard").css( { 'margin-left': (0 - offsetPixels) + 'px' } ); 

    $(".resizable h2").css( { 'margin-left': offsetPixels + 'px' } );

	$(".element").each( function() {
        var durationDays = Math.round( dayDiffStr( $(this).data('start'), $(this).data('end') ) );
        var widthPixels = Math.round( durationDays * config.pixelsPerDay );
		$(this).css('width', widthPixels + 'px' );
        var daysSinceLifeStart = dayDiffStr( config.strLifeStart, $(this).data('start') );
        var leftPixels = daysSinceLifeStart * config.pixelsPerDay;
		$(this).css({left: leftPixels + 'px'});

        //console.log( $(this).find('h3').html() + ' lasts ' + durationDays + ' days. Width: ' + widthPixels + ' pixels' );
	});

    // Iterate through all the newYear markers positioning them appropriately
    $(".newYear").each( function(){
        var daysSinceLifeStart = dayDiffStr(config.strLifeStart, $(this).data('start') ) ;
        var leftPixels = daysSinceLifeStart * config.pixelsPerDay;
        $(this).css({ 'margin-left': leftPixels +'px'});
    });

}

fetchData();
