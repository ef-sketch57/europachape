Cufon.replace("h1, h2, h4",  { fontFamily: 'Anivers' });

$(window).load(function() {
	jQuery("div#slider1").codaSlider();
	
    $('#billboard-image-core').cycle({ 
	    fx:      'custom', 
	    sync: 0, 
	    cssBefore: {  
	        top:  0, 
	        left: 460, 
	        display: 'block' 
	    }, 
	    animIn:  { 
	        left: 0 
	    }, 
	    animOut: {  
	        top: 290 
	    }, 
	    delay: -500 
	});
});

$(document).ready(function() {
    $('#text-slides').cycle({ 
    	fx:'fade',
    	cleartype:  true,
    	cleartypeNoBg:  true
    });
    
    $('.thickbox').lightBox({fixedNavigation:true});
}); 