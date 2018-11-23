$(document).ready(function () {
    var $table = $('#table'),
        $modify = $('#modify'),
        $remove = $('#remove');

    $table.bootstrapTable({
        height: 500
    });
    $table.on('check.bs.table uncheck.bs.table ' +
    'check-all.bs.table uncheck-all.bs.table', function () {
        $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
        $modify.prop('disabled', !$table.bootstrapTable('getSelections').length);
    });
    $(window).resize(function () {
        $table.bootstrapTable('resetView', {
            height: 500
        });
    });

    function checkValid() {
        return $table.bootstrapTable('getSelections').length > 0;
    }

    $remove.click(checkValid);
    $modify.click(checkValid);

    setupDefaultHandlers();

    var description = $('#questionDescription');

    function clearErrors() {
        description.parent().removeClass('has-error');
        updateTips('All fields required', false);
    }

    function modifyQuestionOpen() {
        var obj = $table.bootstrapTable('getSelections')[0];
        $('#questionDescription').val(obj.description);
    }

    function addQuestionSubmit(e) {
        e.preventDefault();
        clearErrors();

        var valid = checkLength(description, 0);

        if (!valid) {
            return;
        }

        $.ajax({
            url: '../required_questions/add',
            data: {
                description: description.val()
            },
            type: 'POST',
            success: function (data) {
                if (data == 0) {
                    alert('That question is already a department question or instructor question.\nPlease remove that question and then try to add it again.');
                } else if (data == -1) {
                    alert('That question is already a required question.');
                } else {
                    var tuple = {id: data, description: description.val()};
                    $table.bootstrapTable('insertRow', {index: 0, row: tuple});
                }
                description.val('');
            },
            error: handleError
        });

        $('#addModal').modal('hide');
    }

    function removeQuestionSubmit() {
        var obj = $table.bootstrapTable('getSelections')[0];

        $.ajax({
            url: '../required_questions/remove',
            data: 'qid=' + obj.id,
            type: 'POST',
            success: function (data) {
                $table.bootstrapTable('remove', {field: 'id', values: [data]});
            }
        });

        $('#removeModal').modal('hide');
    }

    function modifyQuestionSubmit(e) {
        e.preventDefault();
        clearErrors();

        var valid, obj;
        valid = checkLength(description, 0) && checkUnderscore(description);
        obj = $table.bootstrapTable('getSelections')[0];

        if (!valid) {
            return;
        }

        $.ajax({
            url: '../required_questions/modify',
            data: {
                description: description.val(),
                qid: obj.id
            },
            type: 'POST',
            success: function (data) {
                if (data == 0) {
                    alert('That question is already a department question or instructor question.\nPlease remove that question and then try to add it again.');
                } else if (data == -1) {
                    alert('That question is already a required question.');
                } else {
                    var index = $('input[name="btSelectItem"]:checked').data('index');
                    var tuple = {id: data, description: description.val()};
                    $table.bootstrapTable('updateRow', {index: index, row: tuple});
                }
                description.val('');
            },
            error: handleError
        });

        $('#addModal').modal('hide');
    }

    modalsCommon('question', modifyQuestionOpen, addQuestionSubmit, modifyQuestionSubmit, removeQuestionSubmit, function () {
        clearErrors();
        description.val('');
    });
});