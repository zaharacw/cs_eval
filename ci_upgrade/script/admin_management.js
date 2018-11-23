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

    var oldName, oldUsername, oldEmail, nameField, usernameField, emailField;
    nameField = $("#userFullname");
    usernameField = $("#userId");
    emailField = $("#userEmail");

    function verifyUserInput() {
        // TODO: this is nasty; abstract it somehow
        var valid = checkRegexp(nameField, /^[a-z]*[" "][a-z]+$/i, 'Name must be alphabetic with first & last names separated by a space');
        valid = valid && checkRegexp(usernameField, /^[a-z]([0-9a-z_])+$/i, 'Enter your alphanumeric NET ID');
        valid = valid && checkRegexp(emailField, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com");
        return valid;
    }

    function toggleInputs(disabled) {
        nameField.prop('disabled', disabled);
        usernameField.prop('disabled', disabled);
        emailField.prop('disabled', disabled);
        $('#removeModalSubmit').prop('disabled', disabled);
        $('#addModalSubmit').prop('disabled', disabled);
    }

    function addUserSubmit(e) {
        e.preventDefault();
        clearErrors();

        var valid = checkLength(nameField, 0) && checkLength(usernameField, 0) && checkLength(emailField, 0) && verifyUserInput();

        if (!valid) {
            return;
        }

        $.ajax({
            url: 'admins/add',
            data: {
                name: nameField.val(),
                username: usernameField.val(),
                email: emailField.val()
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                if (data === 0) {
                    updateTips('Admins cannot have duplicated names or usernames', true);
                } else {
                    var tuple = {name: data.name, username: data.user, email: data.email, super: "F"};
                    $table.bootstrapTable('insertRow', {index: 0, row: tuple});

                    $('#addModal').modal('hide');
                    clearInputs();
                }
            },
            error: handleError
        });
    }

    function removeUserSubmit() {
        var obj = $table.bootstrapTable('getSelections')[0];
        $.ajax({
            url: 'admins/remove',
            data: 'username=' + obj.username,
            type: 'POST',
            success: function (data) {
                $table.bootstrapTable('remove', {field: 'username', values: [data]});
            }
        });

        $('#removeModal').modal('hide');
    }

    function modifyUserSubmit(e) {
        e.preventDefault();
        clearErrors();

        var valid = checkLength(nameField, 0) && checkLength(usernameField, 0) && checkLength(emailField, 0) && verifyUserInput();
        var obj = $table.bootstrapTable('getSelections')[0];

        if (!valid) {
            return;
        }

        $.ajax({
            url: 'admins/modify',
            data: {
                name: nameField.val(),
                username: usernameField.val(),
                email: emailField.val(),
                oldName: obj.name,
                oldUsername: obj.username,
                oldEmail: obj.email
            },
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                if (data === 0) {
                    updateTips('Admins cannot have duplicated names or usernames', true);
                } else if (data === 1) {
                    updateTips('You cannot modify yourself', true);
                } else {
                    var index = $('input[name="btSelectItem"]:checked').data('index');
                    var tuple = {
                        name: data.name,
                        username: data.user,
                        email: data.email
                    };
                    $table.bootstrapTable('updateRow', {index: index, row: tuple});

                    $('#addModal').modal('hide');
                    clearInputs();
                }
            },
            error: handleError
        });
    }

    function modifyUserOpen() {
        var obj = $table.bootstrapTable('getSelections')[0];

        nameField.val(obj.name);
        usernameField.val(obj.username);
        emailField.val(obj.email);

        if (obj.super == 'T' || obj.username === $("#user").val()) {
            toggleInputs(true);
        }
    }

    function clearErrors() {
        $('#userFullname').parent().removeClass('has-error');
        $('#userId').parent().removeClass('has-error');
        $('#userEmail').parent().removeClass('has-error');
        updateTips('All fields required', false);
    }

    function clearInputs() {
        toggleInputs(false);
        nameField.val('');
        usernameField.val('');
        emailField.val('');
    }

    modalsCommon('user', modifyUserOpen, addUserSubmit, modifyUserSubmit, removeUserSubmit, function () {
        clearErrors();
        clearInputs();
    });

    $('#remove').click(function () {
        var obj = $table.bootstrapTable('getSelections')[0];
        if (obj.super == 'T' || obj.username === $("#user").val()) {
            toggleInputs(true);
        }
    });
});