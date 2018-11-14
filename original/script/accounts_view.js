$(document).ready(function () {
    $('#basic_selection').submit(function () {
        var val = $('input[type=submit][clicked=true]').val();
        $(this).append('<input type="hidden" name="group" value="' + val + '" />');
    });

    $('#basic_selection input[type=submit]').click(function () {
        $('input[type=submit]', $(this).parents('form')).removeAttr('clicked');
        $(this).attr('clicked', 'true');
    });
});