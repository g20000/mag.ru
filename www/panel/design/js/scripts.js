$(document).ready(function () {

	// смотрим есть ли на странице тултипы и выставляем
	$("[data-toggle='tooltip']").tooltip();

	// success  info  warning  error
	toastr.options = {
		fadeIn: 300,
		fadeOut: 1000,
		timeOut: 5000,
		extendedTimeOut: 1000,
		debug: false,
		positionClass: 'toast-top-right',
		onclick: function(){ void(0); }
	};	
	
	
});


$.fn.elSidebar({
	sidebar		: '#sidebar',				// real sidebar
	sidebarBtn	: '.el-sidebar-btn',		// show/hide sidebar button
	elSidebar	: '#el-sidebar-nav',		// el sidebar
	elWrapper	: '#el-sidebar-wrapper'		// el wrapper
});


function notify(shortCutFunction,title,msg) {
	var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
}