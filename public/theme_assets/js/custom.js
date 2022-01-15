

// Material Button
var element, circle, d, x, y;
$(".btn").click(function(e) {
	element = $(this);
	if(element.find(".circless").length == 0)
	element.prepend("<span class='circless'></span>");
	circle = element.find(".circless");
	circle.removeClass("animate");
	if(!circle.height() && !circle.width())
	{
		d = Math.max(element.outerWidth(), element.outerHeight());
		circle.css({height: d, width: d});
	}
	x = e.pageX - element.offset().left - circle.width()/2;
	y = e.pageY - element.offset().top - circle.height()/2;
	
	circle.css({top: y+'px', left: x+'px'}).addClass("animate");
});

// Loading
$(function() {
	$(".loading-wrapper").fadeOut(2000);
});

//Todo List
$(function() {
	$( '.task-list' ).on( 'click', 'li.task', function() {
		$(this).toggleClass('completed' );
	});
});

// $(function(){
// 	$(".navbar-nav .dropdown").hover(
// 		function() {
// 			$('.dropdown-menu', this).stop( true, true ).fadeIn("fast");
// 			$(this).toggleClass('open');
// 		},
// 		function() {
// 			$('.dropdown-menu', this).stop( true, true ).fadeOut("fast");
// 			$(this).toggleClass('open');
// 		}
// 	);
// });
