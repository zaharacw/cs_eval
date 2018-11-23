$(document).ready(function () {
    var quarter = $('#quarter');
    var year = $('#year');
    var subjects = $('#subjects');
    var overwriteMessage = 'Evaluation metadata (instructor, evaluation start/end dates, etc.) will be overwritten for one or more selected courses. Do you wish to continue?';

    if (document.getElementById('course-selection')) {
        $('#course-selection').submit(function (e) {
            var val = $('input[type=submit][clicked=true]').val();
            if (val == 'Submit') {
                var selections = $('#table').bootstrapTable('getSelections');
                var willOverwrite = $.inArray('T', selections.map(function (x) { return x.duplicate; })) != -1;

                if (willOverwrite && !confirm(overwriteMessage)) {
                    e.preventDefault();
                    return;    
                }

                var selectedIds = selections.map(function (x) {
                    return x.id;
                });

                $(this).append('<input type="hidden" name="course_list" value="' + selectedIds.join() + '" />');
            }
        });

        $('#course-selection input[type=submit]').click(function () {
            $('input[type=submit]', $(this).parents('form')).removeAttr('clicked');
            $(this).attr('clicked', 'true');
        });
    }

    $(document).on("keypress", 'form', function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
});