
var config = {};
config.windowWidth = 0;
config.totalLifeDays = 0; 
config.pixelsPerDay = 10;
config.zoom = 1; 
config.strLifeStart = '';
config.offset = 0;



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


$('.eventInput i, #btnCancelAddEvent').click( hideAddEvent );

function hideAddEvent(){ 
    $('.eventInputwrap').addClass('EIWcollapsed');
    $('.lifeBoard').removeClass('blurred');	 
}

$('.btnAddEvent').click( function(){ 
    $('.eventInputwrap').removeClass('EIWcollapsed');
    $('.lifeBoard').addClass('blurred');    
    $('.eventInput input#name').focus(); 
});


$('select#category_id').change( function(){
    if( $(this).val() == '' ){
        $('#newCatRow').removeClass('hidden');
    } else {
        $('#newCatRow').addClass('hidden');
    }
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
$('#btnSubmitAddEvent').click( function(){

    var category_id = $('select#category_id').val();
    var newCategory = $('input#newCategory').val();
    var name = $('input#name').val();
    var startdate = $('input#startdate').val();
    var enddate = $('input#enddate').val();
    var noEnd = 0;
    if( $('input#noEnd').attr('checked') ){
        noEnd = 1;
    }

    $.ajax({
        type: "POST",
        url: "/POST/",
        data: { 'category_id': category_id,
                'newCategory': newCategory,
                'name': name,
                'startdate': startdate,
                'enddate': enddate,
                'noEnd': noEnd
        },
        dataType: "json"
    }).done(function(data) {

        processCategory( data.skvCategory );
        rescale();
        hideAddEvent();
        
    });

});


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
        populateNewYearMarkers();
        populateCategorySelect( data.arrCategories );
        processCategories( data.arrCategories );
        rescale();
        
    });

}


// Iterates through the years of the lifespan ensuring the newYear marker elements exist and creating them if not
function populateNewYearMarkers(){
    var start = parseInt( config.strLifeStart.match(/^[0-9]{4}/) );
    var end = parseInt( config.strLifeEnd.match(/^[0-9]{4}/) );
    var elem;
    for( var i = end; i >= start; i--){
        elem = $('.newYear#n' + i);
        if( !elem.length ){
            $('.lifeBoard').prepend( '<div class="newYear" id="n' + i + '">' + i + '</div>' )
        }
    }
}


// Takes array of categories and populates the select box used for adding/editing events
function populateCategorySelect( arrCategories ){
    var elem = $('select#category_id');
    // Empty the select box
    elem.find('option').remove();
    elem.append( '<option value="">- Let me enter a new one -</option>' );
    for( var i = 0, iLimit = arrCategories.length; i < iLimit; i++ ){
        elem.append( '<option value="' + arrCategories[i]['id'] + '">' + arrCategories[i].name + '...</option>' );
    }
}


// Takes array of categories calling processCategory() function for each
function processCategories( arrCategories ){
    for( var i = 0, iLimit = arrCategories.length; i < iLimit; i++ ){
        processCategory( arrCategories[i] );
    }
}


// Takes a structure representing a category and populates the DOM with the data
function processCategory( skvCategory ){

    var strHTML;

    // Check for the existence of the category
    var elemCategory = $('#c' + skvCategory['id'] );
    if( elemCategory.length ){
        // Great! The category exists
    } else {
        // We need to create the category
        strHTML =  '<div class="categoryRow" id="c' + skvCategory['id'] + '">';
        strHTML += '<h2>' + skvCategory['name'] + '&#133;</h2>';
        strHTML += '</div>';
        $('.lifeBoard').append( strHTML );
        elemCategory = $('#c' + skvCategory['id'] );
    }

    var elemEvent;
    for( var i = 0, iLimit = skvCategory.arrEvents.length; i < iLimit; i++ ){
        elemEvent = $('#e' + skvCategory.arrEvents[i]['id']);
        if( elemEvent.length ){
            // Cool! The event element exists
            
        } else {
            // We need to create the event element
            strHTML =  '<div class="element" id="e' + skvCategory.arrEvents[i]['id'] + '" ';
            strHTML += ' data-start="' + skvCategory.arrEvents[i]['startDate'] + '" ';
            strHTML += ' data-end="' + skvCategory.arrEvents[i]['endDate'] + '">';
            strHTML += '<h3>';
            if( skvCategory.arrEvents[i]['name'] ){
                strHTML += skvCategory.arrEvents[i]['name'];
            }
            strHTML += '</h3>';
            strHTML += '<span class="start date">' + skvCategory.arrEvents[i]['startDate'] + '</span>';
            strHTML += '<span class="end date">' + skvCategory.arrEvents[i]['endDate'] + '</span>';
            strHTML += '</div>';
            elemCategory.append( strHTML );

            elemEvent = $('#e' + skvCategory.arrEvents[i]['id']);
        
            // Colour the element
            elemEvent.css( { 'background-color': getRandomShadeOfHue(skvCategory.hue) } );
        }
    }
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
        var daysSinceLifeStart = dayDiffStr(config.strLifeStart, $(this).html() + '-01-01' ) ;
        var leftPixels = daysSinceLifeStart * config.pixelsPerDay;
        $(this).css({ 'margin-left': leftPixels +'px'});
    });

}

fetchData();


// Returns a random integer in the range provided
function rand(min, max){
    if( typeof min === 'undefined' ){
        min = 0;
    }
    if( typeof max === 'undefined' ){
        max = 100;
    }
    return parseInt(Math.random() * (max-min+1), 10) + min;
}


// Takes a hue and returns a random shade of it 
function getRandomShadeOfHue( hue ){
    var s = 40; // saturation fixed at 40 for this application
    var l = rand(40, 60); // lightness 30-70%
    return 'hsl(' + hue + ',' + s + '%,' + l + '%)';
}
