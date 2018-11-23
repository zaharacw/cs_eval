$(document).ready(function () {
    var $table = $('#table'),
        $updateCourse = $('#updateCourse');

    $table.bootstrapTable({
        height: 500
    });
    $table.on('check.bs.table uncheck.bs.table ' +
    'check-all.bs.table uncheck-all.bs.table', function () {
        $updateCourse.prop('disabled', $table.bootstrapTable('getSelections').length < 1);
    });
    $(window).resize(function () {
        $table.bootstrapTable('resetView', {
            height: 500
        });
    });

    $updateCourse.click(function () {
        return $table.bootstrapTable('getSelections').length > 0;
    });

    $('#updateModal').on('shown.bs.modal', function () {
        if ($table.bootstrapTable('getSelections').length > 0) {
            var startDate = $table.bootstrapTable('getSelections')[0].eval_start;
            var endDate = $table.bootstrapTable('getSelections')[0].eval_end;

            var $datepicker = $('.input-daterange');
            $datepicker.find('#start').datepicker('update', startDate);
            $datepicker.find('#end').datepicker('update', endDate);
            $datepicker.data('datepicker').updateDates();
        }
    });

    $('.input-daterange').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true,
        todayBtn: "linked"
    });

    function getSelectedIds() {
        return $table.bootstrapTable('getSelections').map(function (x) {
            return x.id;
        });
    }

    $('#updateModalSubmit').click(function (e) {
        var start = $("#start").val();
        var end = $("#end").val();

        getSelectedIds().forEach(function (id) {
            $.ajax({
                url: 'evaluation_period/set_eval_period',
                data: {
                    startEval: start,
                    endEval: end,
                    cid: id
                },
                type: 'POST',
                success: function (result) {
                    if (result != 1) {
                        return;
                    }

                    $('input[name="btSelectItem"]:checked').each(function () {
                        var tuple = {eval_start: start, eval_end: end};
                        $table.bootstrapTable('updateRow', {index: $(this).data('index'), row: tuple});
                        $(".selected").addClass( "info" );
                    });
                },
                error: function () {
                    alert('Error 509 (connection with server). Please check your connection.');
                }
            })
        });
        $('#updateModal').modal('hide');

    });
});
