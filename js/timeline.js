
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

    $(function() {

        $(".element").mouseover( function(){$(this).children('.date').show() }).mouseout(function(){$(this).children('.date').hide() });
        
    	rescale(10);
		$(".resizable").each( function(){
			$(this).css('height',$(this).data('height')).children('.element').css('height',$(this).data('height')-35);
		} );
        $( ".resizable" ).resizable({
		    start:function () {
		      
		    },
		    stop:function () {
		     
		    },
		    resize:function () {
			  $(this).children('.element').css('height',parseInt($(this).css('height').substring(0,$(this).css('height').length-2))-35);
			  
		    }
		  });

    	$('#scale').change( function(){ rescale($(this).attr('value')) } );
    });

    function rescale(scale){
    	scale=scale/10;
    	console.log(scale);

    	$(".element").each( function() {
    		$(this).css('width',$(this).data('duration')*scale);
    		$(this).css({left:$(this).data('start')*scale+'px'});
    	});
    }