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

    // handle 'check all' buttons
    $('#checkAll').click(handleCheckAll);
    $('#checkAllMod').click(handleCheckAll);

    var type, description;
    type = $('#questionType');
    description = $('#questionDescription');

    // check to see if a box is unselected and unselect the checkAll box
    $(':checkbox').change(function () {
        if (!this.checked) {
            $('#checkAll').prop('checked', false);
        }
    });

    function clearErrors() {
        description.parent().removeClass('has-error');
        updateTips('All fields required', false);
    }

    function uncheckAll() {
        uncheckBoxes();
        $('#checkAll').prop('checked', false);
    }

    function modifyQuestionOpen() {
        var obj = $table.bootstrapTable('getSelections')[0];

        var selectedCourses, i, courseSelector;
        type.val(globalUtil.typeNameToNum(obj.type));
        description.val(obj.description);
        selectedCourses = obj.sections.split('_');

        for (i = 0; i < selectedCourses.length; i += 1) {
            courseSelector = '#section' + selectedCourses[i];
            $(courseSelector).prop('checked', true);
        }
    }

    function addQuestionSubmit(e) {
        e.preventDefault();
        clearErrors();

        var valid, checkedBoxes;
        valid = checkLength(description, 0) && checkedBoxesExist();
        checkedBoxes = getCheckedBoxes();

        if (!valid) {
            return;
        }

        $.ajax({
            url: '../course_questions/add',
            data: {
                type: type.val(),
                description: description.val(),
                courses: checkedBoxes
            },
            type: 'POST',
            success: function (data) {
                if (data == 0) {
                    alert('That question is already a required question or an instructor question.\n\n' +
                    'Required questions will appear on all course evaluations.\n\n' +
                    'You can delete an instructor question and add it as a department question.\n\t' +
                    '* Be sure to check the instructor\'s course checkbox so that it is available to them.');
                } else {
                    var tuple = {
                        id: data,
                        description: description.val(),
                        type: globalUtil.typeNumToName(type.val()),
                        sections: checkedBoxes.join('_')
                    };

                    $table.bootstrapTable('insertRow', {index: 0, row: tuple});
                }
                uncheckAll(false);
                description.val('');
                type.val(1);
            },
            error: handleError
        });

        $('#addModal').modal('hide');
    }

    function removeQuestionSubmit() {
        var obj = $table.bootstrapTable('getSelections')[0];

        $.ajax({
            url: '../course_questions/remove',
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

        var valid, checkedBoxes, obj;
        valid = checkLength(description, 0) && checkUnderscore(description) && checkedBoxesExist();
        checkedBoxes = getCheckedBoxes();
        obj = $table.bootstrapTable('getSelections')[0];

        if (!valid) {
            return;
        }

        $.ajax({
            url: '../course_questions/modify',
            data: {
                type: type.val(),
                description: description.val(),
                courses: checkedBoxes,
                qid: obj.id
            },
            type: 'POST',
            success: function (data) {
                if (data == 0) {
                    alert('That question is already a required question or an instructor question.\n\n' +
                    'Required questions will appear on all course evaluations.\n\n' +
                    'You can delete an instructor question and add it as a department question.\n\t' +
                    '* Be sure to check the instructor\'s course checkbox so that it is available to them.');
                } else {
                    var index = $('input[name="btSelectItem"]:checked').data('index');
                    var tuple = {
                        id: data,
                        description: description.val(),
                        type: globalUtil.typeNumToName(type.val()),
                        sections: checkedBoxes.join('_')
                    };
                    $table.bootstrapTable('updateRow', {index: index, row: tuple});
                }
                description.val('');
                uncheckAll(true);
            },
            error: handleError
        });

        $('#addModal').modal('hide');
    }

    modalsCommon('question', modifyQuestionOpen, addQuestionSubmit, modifyQuestionSubmit, removeQuestionSubmit, function () {
        clearErrors();
        description.val('');
        type.val(1);
        uncheckAll();
    });
});