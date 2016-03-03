$(document).ready(function () {

    $("tr").hide();
    $("tr").slice(0,12).show();

    (function ($) {

        $('#filter').keyup(function () {
            var rex = new RegExp($(this).val(), 'i');
            $('.searchable tr').hide();
            $('.searchable tr').filter(function () {
                return rex.test($(this).text());
            }).show();

            if ($(this).val() === "") {
                $("tr").hide();
                $("tr").slice(0,12).show();
            }

        })

    }(jQuery));

});
