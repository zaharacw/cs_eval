$(document).ready(function () {

    var type = $("#type");

    var description = $("#description");
    descriptionMod = $("#descriptionMod");
    allFields = $([]).add(description);
    allFieldsMod = $([]).add(descriptionMod);
    instructor = $("#instructor");

    tips = $(".validateTips");

    //updating the <p> field on top of the dialog box
    function updateTips(t) {
        tips
            .text(t)
            .addClass("ui-state-highlight");
        setTimeout(function () {
            tips.removeClass("ui-state-highlight", 1500);
        }, 500);
    }

    //check status of checkAll box and check or uncheck all boxes
    $(function () {
        $('#checkAll').click(function (event) {

            var selected = this.checked;
            // Iterate each checkbox
            $(':checkbox').each(function () {
                this.checked = selected;
            });
        });
    });

    //check to see if a box is unselected and unselect the checkAll box
    $(":checkbox").change(function () {
        if (!this.checked) {
            $('#checkAll').prop("checked", false);
        }
    });

    //check to see if a box is unselected and unselect the checkAll box
    $(":checkbox").change(function () {
        if (!this.checked) {
            $('#checkAllMod').prop("checked", false);
        }
    });

    //check to see if there is at least one box checked.
    function checkBoxes() {
        var checkboxes = document.getElementsByName("course");
        var checkboxesChecked = [];

        $("input:checkbox[name=course]:checked").each(function () {
            // add $(this).val() to your array
            checkboxesChecked.push($(this).val());
        });

        if (checkboxesChecked.length == 0) {
            updateTips("You must select at least one course to apply this question to.");
            return false;
        }

        return true;
    }

    function checkUnderscore(d) {

        var s = d.val();
        if (s.indexOf("_") !== -1) {
            updateTips("You cannot have an underscore");
            return false;
        }
        else {
            return true;
        }

    }

    //checking length of input from user
    function checkLength(o, min, n) {
        if (o.val().length < min + 1) {
            o.addClass("ui-state-error");

            if (o.val().length == 0) {
                updateTips("You must enter a question");
            }

            else {
                updateTips("You must enter a question.");
            }
            return false;
        }

        else {
            return true;
        }
    }


    //prevent default 'enter'
    $("#descriptionMod").keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

    // TODO: use Bootstrap modals when this is revisited

    //this is the add question dialog
    $("#Add-Question").dialog({
        autoOpen: false,
        height: 400,
        width: 500,
        modal: true,
        resizable: false,

        open: function () {
            updateTips("All fields required.");

        },

        //add question button on form
        buttons: {
            "Add Question": function (e) {
                e.preventDefault();
                var bValid = true;

                //removing the state error class from all inputs
                allFields.removeClass("ui-state-error");

                //checking to make sure the inputs are the right length
                bValid = bValid && checkLength(description, 0, "Description");
                bValid = bValid && checkUnderscore(description);
                bValid = bValid && checkBoxes();


                if (bValid) {

                    var checkboxes = document.getElementsByName("course");
                    var checkboxesChecked = [];

                    // loop over them all
                    $("input:checkbox[name=course]:checked").each(function () {
                        // add $(this).val() to your array
                        checkboxesChecked.push($(this).val());
                    });

                    //setting up the variables to be pasted into the AddQuestion function in controller
                    var ara = {
                        description: description.val(),
                        instructor: instructor.val(),
                        courses: checkboxesChecked
                    };

                    var d = description.val();
                    var url = $('#url').val() + 'admin_instructor_questions/addQuestion';

                    //ajax post back with url of controller and method
                    $.ajax({
                        url: url,
                        data: ara,
                        type: 'POST',
                        //on success the listbox is updated with the data from the method inside the controller
                        success: function (data) {

                            var myData = data;

                            if (myData == 0) {
                                alert("That question is already a required question or an instructor question.\n\n" +
                                "Required questions will appear on all course evaluations.\n\n" +
                                "You can delete an instructor question and add it as a department question.\n\t" +
                                "* Be sure to check the instructor's course checkbox so that it is available to them.");

                                description.val('');
                                $("input:checkbox[name=course]:checked").each(function () {
                                    //uncheck boxes
                                    $(this).prop("checked", false);
                                });
                            }

                            else {
                                var courseString = "";


                                for (var i = 0; i < checkboxesChecked.length; i++) {
                                    courseString = courseString + "_" + checkboxesChecked[i].toString();
                                }


                                //this selects the listbox element
                                var selField = document.getElementById("mainSelect");
                                //this selects the options inside the listbox element
                                var oOption = document.createElement("OPTION");

                                //adding the option to the listbox
                                selField.options.add(oOption);
                                oOption.text = type.val() + "_" + d;
                                oOption.value = myData + courseString;

                                type.val('departmental');
                                //description.val('');

                                $("input:checkbox[name=course]:checked").each(function () {
                                    //uncheck boxes
                                    $(this).prop("checked", false);
                                });
                            }


                        },
                        //error checker
                        error: function (data, textStatus, errorThrown) {
                            alert("An error has occurred: " + textStatus + " " + errorThrown);
                        }
                    });
                    //closing out the dialog form
                    $(this).dialog("close");
                }
            },

            //cancel button
            Cancel: function () {

                allFields.removeClass("ui-state-error"); //remove error states
                description.val('');

                $('#checkAll').prop("checked", false);
                $("input:checkbox[name=course]:checked").each(function () {
                    //uncheck boxes
                    $(this).prop("checked", false);
                });

                $(this).dialog("close");
            }

        },

        close: function () {
            allFields.removeClass("ui-state-error"); //remove error states
            description.val('');
            $("input:checkbox[name=course]:checked").each(function () {
                //uncheck boxes
                $(this).prop("checked", false);
            });

            $('#checkAll').prop("checked", false);
        }
    });//end addQuestion Dialog

    $("#Remove-Question").dialog({
        autoOpen: false,
        height: 170,
        width: 400,
        modal: true,
        resizable: false,

        //yes that removes removes all user selected courses to remove
        buttons: {
            "Yes": function () {

                //selecting the listbox element
                var list = document.getElementById("mainSelect");
                //for loop running through all of the options in the listbox
                for (var count = list.options.length - 1; count >= 0; count--) {
                    //if the options is selected remove from the listbox
                    if (list.options[count].selected == true) {

                        var del = "q_id=" + list.options[count].value
                        var url = 'admin_instructor_questions/RemoveQuestion';

                        $.ajax({
                            url: url,
                            data: del,
                            type: 'POST',
                            //on success
                            success: function (data) {

                                //sends alert of the data(name) from inside the controller
                                var list = document.getElementById("mainSelect");

                                //looping through the list box
                                for (var cnt = list.options.length - 1; cnt >= 0; cnt--) {
                                    //checking to see if the value inside the list box matches the data sent back
                                    if (list.options[cnt].value == data) {
                                        list.remove(cnt, null);
                                    }
                                }

                            },
                            error: function () {
                                alert(data + "Error 509(connection with server). Please check your connection.");
                            }
                        });
                    }
                }

                $(this).dialog("close");
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        }
    });//end remove-Question dialog

    //this is the modify question dialog with functionality
    $("#Modify-Question").dialog({
        autoOpen: false,
        height: 250,
        width: 700,
        modal: true,
        resizable: false,

        open: function () {

            updateTips("");
            var list = document.getElementById("mainSelect");
            var str, array;
            var user;
            for (var count = list.options.length - 1; count >= 0; count--) {
                if (list.options[count].selected == true) {
                    //get the different parts of the question
                    str = list.options[count].text;
                    array = str.split("_");

                    user = array[0];
                    $('#descriptionMod').val(array[2]);
                    break;
                }//end if
            }
        },

        //modify question button on form
        buttons: {
            "Modify Question": function (e) {
                e.preventDefault();
                var str, array, user;
                var bValid = true;
                allFieldsMod.removeClass("ui-state-error");

                //removing the state error class from all inputs
                //allFields.removeClass( "ui-state-error" );

                bValid = bValid && checkLength(descriptionMod, 0, "Description");
                bValid = bValid && checkUnderscore(descriptionMod);


                if (bValid) {
                    //selecting the listbox element
                    var list = document.getElementById("mainSelect");

                    var qidMod;
                    //for loop running through all of the options in the listbox
                    for (var count = list.options.length - 1; count >= 0; count--) {
                        //setting up the variables to be posted into the ModifyQuestion function in controller
                        if (list.options[count].selected == true) {
                            qidMod = list.options[count].value;
                            break;
                        }//end if

                    }

                    var ara = {
                        descriptionMod: $("#descriptionMod").val(),
                        q_id: qidMod
                    };

                    var d = $("#descriptionMod").val();

                    str = list.options[count].text;
                    array = str.split("_");

                    user = array[0];
                    var cNum = array[1];
                    var q = qidMod;
                    //ajax postback with url of controller and method
                    $.ajax({
                        url: 'admin_instructor_questions/ModifyQuestion',
                        data: ara,
                        type: 'POST',
                        //on success the listbox is updated with the data from the method inside the controller
                        success: function (data) {

                            var calcString, show, calc;

                            if (data == 0) {
                                alert("That question is already available for this course.");
                                descriptionMod.val('');
                            }

                            else if (data == -1) {
                                alert("An admin has already created that question.");
                                descriptionMod.val('');
                            }

                            else {
                                //this selects the listbox element
                                var list = document.getElementById("mainSelect");
                                var oOption = document.createElement("OPTION");

                                for (var cnt = list.options.length - 1; cnt >= 0; cnt--) {
                                    //checking to see if the value inside the list box matches the data sent back
                                    if (list.options[cnt].value == q) {
                                        //list.options[cnt].text = user + "_" + descriptionMod.val();
                                        list.remove(cnt, null);
                                        break;
                                    }
                                }

                                list.options.add(oOption);
                                oOption.text = user + "_" + cNum + "_" + d;
                                oOption.value = data;

                                //numberMod.val('');
                                descriptionMod.val('');
                            }

                        },
                        //error checker
                        error: function (data, textStatus, errorThrown) {
                            alert("Something really bad happened " + textStatus + " " + errorThrown);
                        }
                    });
                    //closing out the dialog form
                    $(this).dialog("close");
                }
            },
            //cancel button
            Cancel: function () {

                //remove error states
                allFieldsMod.removeClass("ui-state-error");
                $(this).dialog("close");
            }
        },
        close: function () {
            allFieldsMod.removeClass("ui-state-error");
            descriptionMod.val('');
        }
    });//end ModifyQuestion Dialog

    //checking to see if an option inside the listbox is double clicked then calling the remove form
    $("#mainSelect").dblclick(function () {
        $("#mainSelect option:selected").each(function () {
            if ($('#ModifyButton').is(':disabled') == false) {
                $("#Modify-Question").dialog("open");
            }
        });
    });

    //remove question dialog pops up when you click the remove button
    $("#RemoveButton").click(function () {

        var valid = false;

        //selecting the listbox element
        var list = document.getElementById("mainSelect");

        //for loop running through all of the options in the listbox
        for (var count = list.options.length - 1; count >= 0; count--) {
            //if the options is selected
            if (list.options[count].selected == true) {
                valid = true;
                break;
            }
        }

        if (valid) {
            $("#Remove-Question").dialog("open");
        } else {
            alert("You must select an item to remove.")
        }

    });

    //add question form pops up when you click the add button
    $("#AddButton").click(function () {
        $("#Add-Question").dialog("open");
    });

    //modify question form pops up when you click the add button
    $("#ModifyButton").click(function () {

        var valid = false;

        //selecting the listbox element
        var list = document.getElementById("mainSelect");

        //for loop running through all of the options in the listbox
        for (var count = list.options.length - 1; count >= 0; count--) {
            //if the options is selected
            if (list.options[count].selected == true) {
                valid = true;
                break;
            }
        }

        if (valid) {
            $("#Modify-Question").dialog("open");
        } else {
            alert("You must select an item to modify.")
        }
    });
});