$('.autoellipsis').dotdotdot({
    watch: true
});

$('.navbar .main-navbar li').each(function(index, element) {
    if (window.location.href.indexOf($(element).children('a').attr('href').split('?')[0]) !== -1) {
        $(element).attr('class', 'active');
    }
});