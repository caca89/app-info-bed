$(document).ready(function() {
    $('body').on('click', '.loadmenu', function(event) {
        event.preventDefault();

        $('#loader').css({display: "block"});
        var url = $(this).attr('href');
        // var title = $(this).attr('title');
        // document.title = title + ' | Brother CMS';
        var title = "";
        window.history.pushState("", title, url);
        $(this).parent().siblings().find('a').removeClass('active');
        $(this).addClass('active');
        $('#rightsidebar').load(url, function(response, status, xhr) {
        	$('#loader').css({display: "none"});
        });
    });

})