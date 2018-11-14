function updateTips(message, danger) {
    var tipElements = $('#addModalAlert');
    tipElements.text(message).attr('class', danger ? 'alert alert-danger' : 'alert alert-info');
}

function checkUnderscore(txt) {
    var s = txt.val();
    if (s.indexOf('_') !== -1) {
        updateTips('You cannot have an underscore', true);
        return false;
    }
    return true;
}

// source: http://stackoverflow.com/questions/10073699/pad-a-number-with-leading-zeros-in-javascript
function pad(n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
}

function checkRegexp(element, regexp, errorMsg) {
    if (!(regexp.test(element.val()))) {
        element.parent().addClass('has-error');
        updateTips(errorMsg);
        return false;
    }
    return true;
}

function checkLength(element, min) {
    if (element.val().length < min + 1) {
        element.parent().addClass('has-error');
        updateTips('We need some more information', true);
        return false;
    }
    return true;
}

function getCheckedBoxes() {
    var output = [];
    $('input:checkbox[name=course]:checked').each(function (ind, el) {
        output.push($(el).val());
    });
    return output;
}

function uncheckBoxes() {
    $('input:checkbox[name=course]:checked').each(function () {
        $(this).prop('checked', false);
    });
}

function checkedBoxesExist() {
    var vals = getCheckedBoxes();

    if (vals.length === 0) {
        updateTips('You must select at least one course to apply this question to', true);
        return false;
    }
    return true;
}

/* HANDLERS */

function handleError(data, textStatus, errorThrown) {
    alert('Unexpected error: ' + textStatus + ' ' + errorThrown);
}

function handleCheckAll() {
    var selected = this.checked;
    $(':checkbox').each(function () {
        this.checked = selected;
    });
}

function handleUncheck(checkAllElement) {
    if (!this.checked) {
        checkAllElement.prop('checked', false);
    }
}

function setupDefaultHandlers() {
    function preventEnterDefault(event) {
        if (event.keyCode === 13) {
            event.preventDefault();
        }
    }

    $('#questionDescription').keydown(preventEnterDefault);

    $('#removeModal').on('show.bs.modal', function (event) {
        $('#selectionAlert').hide();
    });

    $('#removeModal').on('shown.bs.modal', function (event) {
        $('#removeModalSubmit').focus();
    });

    $('#addModal').on('shown.bs.modal', function () {
        $('#questionDescription').focus();
    });

    $('#selectionAlert button').click(function () {
        $('#selectionAlert').hide();
    });
}

var globalUtil = {
    termNumToName: function (term) {
        term = parseInt(term, 10);
        switch (term) {
            case 10:
                return 'Winter';
            case 15:
                return 'Fall Semester';
            case 20:
                return 'Spring';
            case 25:
                return 'Spring Semester';
            case 30:
                return 'Summer';
            case 35:
                return 'Summer Semester';
            case 40:
                return 'Fall';
            default:
                return 'BAD_TERM';
        }
    },
    termNameToNum: function (term) {
        switch (term) {
            case 'Winter':
                return 10;
            case 'Fall Semester':
                return 15;
            case 'Spring':
                return 20;
            case 'Spring Semester':
                return 25;
            case 'Summer':
                return 30;
            case 'Summer Semester':
                return 35;
            case 'Fall':
                return 40;
            default:
                return -1;
        }
    },
    typeNumToName: function (type) {
        type = parseInt(type, 10);
        switch (type) {
            case 0:
                return 'required';
            case 1:
                return 'other';
            case 2:
                return 'instructor';
            case 3:
                return 'departmental';
            default:
                return -1;
        }
    },
    typeNameToNum: function (type) {
        switch (type.toLowerCase()) {
            case 'required':
                return 0;
            case 'other':
                return 1;
            case 'instructor':
                return 2;
            case 'departmental':
                return 3;
            default:
                return -1;
        }
    }
};

function modalsCommon(typeName, modifyOpen, addSubmit, modifySubmit, removeSubmit, cleanupCallback) {
    function triggerAddModal(isAdd, event) {
        var alertBox, modal, words;
        alertBox = $('#selectionAlert');
        cleanupCallback();

        modal = $('#addModal');
        words = (isAdd ? 'Add ' : 'Modify ') + typeName; // e.g. "Add user"
        alertBox.hide();

        modal.find('.modal-title').text(words);
        modal.find('.modal-footer .btn-primary').text(words);
        modal.find('#modalOperation').val(isAdd ? 'add' : 'modify');

        // modification
        if (!isAdd) {
            modifyOpen();
        }
    }

    // new modal
    $('#addModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        triggerAddModal(button.data('operation') === 'add', event);
    });

    $('#addModalSubmit').click(function (e) {
        if ($('#modalOperation').val() === 'add') {
            addSubmit(e);
        } else {
            modifySubmit(e);
        }
    });

    $('#removeModalSubmit').click(removeSubmit);

    $('#table').on('dbl-click-row.bs.table', function () {
        $('#addModal').modal('show');
        triggerAddModal(false);
    });
}