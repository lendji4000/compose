$(document).ready(function($){
	
	paceOptions = {
		elements: true
	};
	
	$(".sidebar").mmenu();
		
	/* ---------- Disable moving to top ---------- */
	$('a[href="#"][data-top!=true]').click(function(e){
		e.preventDefault();
	});
	
	/* ---------- Tabs ---------- */
	$('#myTab a:first').tab('show');
	$('#myTab a').click(function (e) {
	  e.preventDefault();
	  $(this).tab('show');
	});

	/* ---------- Tooltip ---------- */
	$('[rel="tooltip"],[data-rel="tooltip"]').tooltip({"placement":"bottom",delay: { show: 400, hide: 200 }});

	/* ---------- Popover ---------- */
	$('[rel="popover"],[data-rel="popover"],[data-toggle="popover"]').popover();

	
	$('.btn-close').click(function(e){
		e.preventDefault();
		$(this).parent().parent().parent().fadeOut();
	});
	
	$('.btn-minimize').click(function(e){
		e.preventDefault();
		var $target = $(this).parent().parent().next('.panel-body');
		if($target.is(':visible')) $('i',$(this)).removeClass('fa-chevron-up').addClass('fa-chevron-down');
		else 					   $('i',$(this)).removeClass('fa-chevron-down').addClass('fa-chevron-up');
		$target.slideToggle('slow', function() {
		    widthFunctions();
		});
		
	});
	
	$('.btn-setting').click(function(e){
		e.preventDefault();
		$('#myModal').modal('show');
	});

});

/* ---------- Check Retina ---------- */
function retina(){
	
	retinaMode = (window.devicePixelRatio > 1);
	
	return retinaMode;
	
}

/* ---------- Main Menu Open/Close, Min/Full ---------- */
$(document).ready(function($) {
			
	$('#main-menu-toggle').click(function(){
		
		if($('body').hasClass('sidebar-hide')){
									
			$('body').removeClass('sidebar-hide');
			
		} else {
						
			$('body').addClass('sidebar-hide');
			
		}				
		
	});
	
	$('#sidebar-menu').click(function(){
		
		$(".sidebar").trigger("open");				
		
	});
		
	$('#sidebar-minify').click(function(){
		
		if($('body').hasClass('sidebar-minified')){
						
			$('body').removeClass('sidebar-minified');
			$('#sidebar-minify i').removeClass('fa-caret-square-o-right').addClass('fa-caret-square-o-left');
						
		} else {
						
			$('body').addClass('sidebar-minified');
			$('#sidebar-minify i').removeClass('fa-caret-square-o-left').addClass('fa-caret-square-o-right');
		}
		
	});
	
	$('.dropmenu').click(function(e){
		
		e.preventDefault();
	
		if ($(this).parent().hasClass('opened')) {
			$(this).parent().removeClass('opened');
		} else {
			$(this).parent().addClass('opened');
		}
				
		if ($('.sidebar').hasClass('minified')) {
			
			if ($(this).hasClass('open')) {
				
				//do nothing or add here any function
				
			} else {
				$(this).parent().find('ul').first().slideToggle('slow',function(){
					dropSidebarShadow();
				});
											
			}
			
		} else {
			
			$(this).parent().find('ul').first().slideToggle('slow',function(){
				dropSidebarShadow();
			});
			
		}
	
	});
		
});	

$(document).ready(function($){
	
	/* ---------- Add class .active to current link  ---------- */
	$('ul.nav-sidebar').find('a').each(function(){
		
		if($($(this))[0].href==String(window.location)) {
			
			$(this).parent().addClass('active');
			
			$(this).parents('ul').add(this).each(function(){
			    $(this).show().parent().addClass('opened');
				//$(this).prev('a').find('.chevron').removeClass('closed').addClass('opened');
			});
			
		}	
	
	});

});

$('.sidebar-menu').scroll(function() {
   dropSidebarShadow();
});

$(document).ready(function(){			
	widthFunctions();
	dropSidebarShadow();		
});

function dropSidebarShadow() {
	
	if ($('.nav-sidebar').length) {
		var topPosition = $('.nav-sidebar').offset().top - $('.sidebar').offset().top;	
	}
		
	if (topPosition < 60) {
		$('.sidebar-header').addClass('drop-shadow');
	} else {
		$('.sidebar-header').removeClass('drop-shadow');
	}
	
	var bottomPosition = $(window).height() - $('.nav-sidebar').outerHeight() - topPosition;
	
	if (bottomPosition < 130) {
		$('.sidebar-footer').addClass('drop-shadow');
	} else {
		$('.sidebar-footer').removeClass('drop-shadow');
	}
}
           
/* ---------- Page width functions ---------- */

$(window).bind("resize", widthFunctions);

function widthFunctions(e) {
		
	if($('.timeline')) {
		
		$('.timeslot').each(function(){
			
			var timeslotHeight = $(this).find('.task').outerHeight();
			
			$(this).css('height',timeslotHeight);
			
		});
		
	}
	
	var sidebarHeight = $('.sidebar').outerHeight();
	var mainHeight = $('.main').outerHeight();
		
	var sidebarLeftHeight = $('.sidebar').outerHeight();
	var contentHeight = $('.main').height();
	var contentHeightOuter = $('.main').outerHeight();
	
	var headerHeight = $('.navbar').outerHeight();
	var footerHeight = $('footer').outerHeight();
	
    var winHeight = $(window).height();
    var winWidth = $(window).width();
	
	$('.sidebar-menu').css('height', winHeight - 200);
	
	if (winWidth < 992) {	
		$('body').removeClass('sidebar-minified');
	}
	
	if (winWidth > 768) {
		$('.main').css('min-height',winHeight-footerHeight);
	}
}