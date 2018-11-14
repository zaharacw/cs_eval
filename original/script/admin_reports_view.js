$(document).ready(function () {
    var $table = $('#table'),
        $scoreGen = $('#rawScoreGen'),
        $commentGen = $('#rawCommentGen'),
        $pdfGen = $('#pdfGen'),
        $countGen = $('#countGen');

    $table.bootstrapTable({
        height: 500
    });
    $table.on('check.bs.table uncheck.bs.table ' +
    'check-all.bs.table uncheck-all.bs.table', function () {
        var isEnabled = !$table.bootstrapTable('getSelections').length;
        $scoreGen.prop('disabled', isEnabled);
        $commentGen.prop('disabled', isEnabled);
        $pdfGen.prop('disabled', isEnabled);
        $countGen.prop('disabled', isEnabled);
    });
    $(window).resize(function () {
        $table.bootstrapTable('resetView', {
            height: 500
        });
    });

    function download(fileType) {
        var selectedIds = $table.bootstrapTable('getSelections').map(function (x) {
            return x.id;
        });

        if (selectedIds.length > 0) {
            $.download('reports/download', 'c_ids=' + selectedIds.join() + ':file_type=' + fileType);
        }
    }

    $scoreGen.click(function () {
        download('scores');
    });

    $commentGen.click(function () {
        download('comments');
    });

    $pdfGen.click(function () {
        download('pdf');
    });

    $countGen.click(function () {
        download('count');
    });
});