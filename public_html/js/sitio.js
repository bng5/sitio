$(function() {
    if(window.open) {
        $('a[rel=external]').addClass('external').click(function(event) {
            window.open(this.href, '_blank');
            event.preventDefault();
        });
    }
});
