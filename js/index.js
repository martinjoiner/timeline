var docWidth = 1000;
    $(document).ready(function() {
    	$("#username").focus();
    	setStartPos();
		$('.vertline').each( function(){
			if($(this).hasClass('back')){
				$(this).data('inc',30);
			} 
			else if($(this).hasClass('mid')){
				$(this).data('inc',20);
			}
			else if($(this).hasClass('fore')){
				$(this).data('inc',10);
			}
		});
		ticker();
	});

	function setStartPos(){
		docWidth = $(document).width();
		$('.vertline').each( function(){
			var randLeft = Math.round(Math.random() * docWidth);
			$(this).data('left',randLeft);
		})
	}

    function magDif(a,b){
    	var diff = a - b;
    	if(diff < 0){
    		diff = diff - (diff*2);
    	}
    	return Math.round(diff);
    }

    var ticks = 0;
	function ticker(){
		if(ticks++ > 100){
			if(docWidth != $(document).width()){
				setStartPos();
			}
			ticks = 0;
		}
		$('.vertline').each( function(){
			var newLeft = Math.round( $(this).data('left') + (magDif(docWidth/2,$(this).data('left'))/$(this).data('inc')) + ((40 - $(this).data('inc'))/5 ) );
			if(newLeft > docWidth){
				newLeft = 0;
			}
			$(this).data('left',newLeft);
			$(this).css({left: newLeft + "px"});

		})

		t = setTimeout("ticker();",30); 
	}
