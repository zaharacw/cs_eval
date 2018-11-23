$(document).ready(function () {
    var $table = $('#table'),
        $instructorTable = $('#instructorTable'),
        $duplicateCourse = $('#duplicateCourse'),
        $updateInstructor = $('#updateInstructor'),
        $deleteCourse = $('#deleteCourse');

    $table.bootstrapTable({
        height: 500
    });
    $instructorTable.bootstrapTable({
        height: 400
    });

    $table.on('check.bs.table uncheck.bs.table ' +
    'check-all.bs.table uncheck-all.bs.table', function () {
        $duplicateCourse.prop('disabled', $table.bootstrapTable('getSelections').length != 1);
        $updateInstructor.prop('disabled', $table.bootstrapTable('getSelections').length != 1);
        $deleteCourse.prop('disabled', !$table.bootstrapTable('getSelections').length);
    });
    $(window).resize(function () {
        $table.bootstrapTable('resetView', {
            height: 500
        });
        $instructorTable.bootstrapTable('resetView', {
            height: 400
        });
    });

    function checkValid() {
        return $table.bootstrapTable('getSelections').length > 0;
    }

    $duplicateCourse.click(checkValid);
    $updateInstructor.click(checkValid);
    $deleteCourse.click(checkValid);

    function getSelectedIds() {
        return $table.bootstrapTable('getSelections').map(function (x) {
            return x.id;
        });
    }

    function duplicateCourseSubmit() {
        var selectedIds = getSelectedIds();

        if (selectedIds.length > 1) {
            alert('You can only duplicate a single course at a time.');
            return;
        }

        var instData = $instructorTable.bootstrapTable('getSelections')[0];

        $.ajax({
            url: 'manage_course/duplicate_course',
            data: {
                newInst: instData.id,
                cid: selectedIds[0]
            },
            type: 'POST',
            success: function (worked) {
                if (worked == 1) {
                    var obj = jQuery.extend({}, $table.bootstrapTable('getSelections')[0]);
                    obj.instructor = instData.instructor;
                    obj.state = false;
                    $table.bootstrapTable('insertRow', {index: 0, row: obj});
                }
            },
            error: function () {
                alert('Error 509 (connection with server). Please check your connection.');
            }
        });

        $('#addModal').modal('hide');
    }

    function updateInstructorSubmit() {
        var selectedIds = getSelectedIds();

        if (selectedIds.length > 1) {
            alert('You can only update a single course at a time.');
            return;
        }

        var data = $instructorTable.bootstrapTable('getSelections');
        var ids = $.map(data, function (item) {
            return item.id;
        });

        var inst = ids[0];

        $.ajax({
            url: 'manage_course/update_instructor',
            data: {
                newInst: inst,
                cid: selectedIds[0]
            },
            type: 'POST',
            success: function (data) {
                var tuple = {instructor: data};

                var index = $('input[name="btSelectItem"]:checked').data('index');
                $table.bootstrapTable('updateRow', {index: index, row: tuple});
                $(".selected").addClass( "info" );
            },
            error: function () {
                alert('Error 509 (connection with server). Please check your connection.');
            }
        });

        $('#addModal').modal('hide');
    }

    function deleteCourseSubmit() {
        getSelectedIds().forEach(function (id) {
            $.ajax({
                url: 'manage_course/cleanup',
                data: 'cid=' + id,
                type: 'POST',
                success: function (data) {
                    $table.bootstrapTable('remove', {field: 'id', values: [data]});
                }
            });
        });

        $('#removeModal').modal('hide');
    }

    function triggerAddModal(isAdd, event) {
        var alertBox, modal, words;
        alertBox = $('#selectionAlert');

        modal = $('#addModal');
        words = (isAdd ? 'Duplicate section' : 'Update instructor');
        alertBox.hide();

        modal.find('.modal-title').text(words);
        modal.find('.modal-footer .btn-primary').text(words);
        modal.find('#modalOperation').val(isAdd ? 'add' : 'modify');

        $('input[name="instructor"]').each(function () {
            var isInstructor = $(this).val() == $table.bootstrapTable('getSelections')[0].instructor_id;
            $(this).prop('checked', isInstructor);
        })
    }

    $('#addModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        triggerAddModal(button.data('operation') === 'add', event);
    });

    $('#addModalSubmit').click(function (e) {
        if ($('#modalOperation').val() === 'add') {
            duplicateCourseSubmit(e);
        } else {
            updateInstructorSubmit(e);
        }
    });

    $('#removeModalSubmit').click(deleteCourseSubmit);

    $('#table').on('dbl-click-row.bs.table', function () {
        $('#addModal').modal('show');
        triggerAddModal(false);
    });
});