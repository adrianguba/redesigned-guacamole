(function ($) {
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(jQuery);

jQuery(document).ready(function($) {

    $('input[type=email]').on('keyup', function() {
        var re = /([A-Z0-9a-z_-][^@])+?@[^$#<>?]+?\.[\w]{2,4}/.test(this.value);
        if(!re) {
            $(this).addClass("invalid");
        } else {
            $(this).removeClass("invalid");
        }
    })

    $("#submission-form").submit(function(e) {
        e.preventDefault();

        let formdata = $(this).serializeFormJSON();

        $.post( "wp-json/osom-recrutation/v1/submissions", formdata)
            .done(function( data ) {
               if(data.success) {
                   alert('Formularz zapisany.');
               }
            });
    });
});

