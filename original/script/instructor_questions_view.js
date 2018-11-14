$(document).ready(function () {
    clearBox();

    var description = $("#description");
    descriptionMod = $("#descriptionMod");
    allFields = $([]).add(description);
    allFieldsMod = $([]).add(descriptionMod);

    tips = $(".validateTips");


    $("#description").keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

    $("#descriptionMod").keydown(function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
        }
    });

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

    function clearBox() {
        var optList = document.getElementById("optionalQuestions");
        var courList = document.getElementById("courseQuestions");
        var evalList = document.getElementById("evaluationQuestions");

        for (var count = evalList.options.length - 1; count >= 0; count--) {
            var desc = evalList.options[count].text;
            for (var courCount = courList.options.length - 1; courCount >= 0; courCount--) {
                if (desc == courList.options[courCount].text) {
                    //courList.remove(courCount, null);
                }
            }

            for (var optCount = optList.options.length - 1; optCount >= 0; optCount--) {
                if (desc == optList.options[optCount].text) {
                    //optList.remove(optCount, null);
                }
            }
        }
    }

    //updating the <p> field on top of the dialog boxx
    function updateTips(t) {
        tips
            .text(t)
            .addClass("ui-state-highlight");
        setTimeout(function () {
            tips.removeClass("ui-state-highlight", 1500);
        }, 500);
    }

    //checking length of input from user
    function checkLength(o, min, n) {
        if (o.val().length < min + 1) {
            o.addClass("ui-state-error");

            if (o.val().length == 0) {
                updateTips("Please enter a question.");
            }

            else {
                updateTips("Please enter a question.");
            }
            return false;
        }

        else {
            return true;
        }
    }

    $("#rightArrowButton").click(function () {
        //selecting the listbox element
        var optList = document.getElementById("optionalQuestions");
        var courList = document.getElementById("courseQuestions");
        var evalList = document.getElementById("evaluationQuestions");
        //for loop running through all of the options in the listbox
        for (var count = optList.options.length - 1; count >= 0; count--) {
            //if the options is selected remove from the listbox
            if (optList.options[count].selected == true) {
                var url = $('#url').val() + 'instructor_questions/addToEvaluation';
                var text = optList.options[count].text;
                var value = optList.options[count].value;
                var str = value.split("_");
                var ara = {
                    q_id: str[0],
                    c_id: $('#c_id').val()
                };
                $.ajax({
                    url: url,
                    data: ara,
                    type: 'POST',
                    success: function () {
                    },
                    error: function () {
                        alert("Error");
                    }
                });
                var option = document.createElement("OPTION");
                //adding the option to the listbox
                evalList.options.add(option);
                option.text = text;
                option.value = value;

                //optList.remove(count, null);
            }
        }

        for (var count = courList.options.length - 1; count >= 0; count--) {
            //if the options is selected remove from the listbox
            if (courList.options[count].selected == true) {
                var url = $('#url').val() + 'instructor_questions/addToEvaluation';
                var text = courList.options[count].text;
                var value = courList.options[count].value;
                var str = value.split("_");
                var ara = {
                    q_id: str[0],
                    c_id: $('#c_id').val()
                };
                $.ajax({
                    url: url,
                    data: ara,
                    type: 'POST',
                    success: function () {
                    },
                    error: function () {
                        alert("Error");
                    }
                });
                var option = document.createElement("OPTION");
                //adding the option to the listbox
                evalList.options.add(option);
                option.text = courList.options[count].text;
                option.value = courList.options[count].value;

                //courList.remove(count, null);
            }
        }
    });

    $("#leftArrowButton").click(function () {
        //selecting the listbox element
        var optList = document.getElementById("optionalQuestions");
        var courList = document.getElementById("courseQuestions");
        var evalList = document.getElementById("evaluationQuestions");
        var desc, ara;
        //for loop running through all of the options in the listbox
        for (var count = evalList.options.length - 1; count >= 0; count--) {
            //if the options is selected remove from the listbox
            if (evalList.options[count].selected == true) {
                var url = $('#url').val() + 'instructor_questions/removeFromEvaluation';
                var text = evalList.options[count].text;
                var value = evalList.options[count].value;
                var str = value.split("_");
                var ara = {
                    q_id: str[0],
                    c_id: $('#c_id').val()
                };

                $.ajax({
                    url: url,
                    data: ara,
                    type: 'POST',
                    success: function () {
                    },
                    error: function () {
                        alert("Error");
                    }
                });

                var option = document.createElement("OPTION");
                desc = evalList.options[count].value;
                ara = desc.split("_");
                if (ara[1] == $('#user').val() || ara[1] != "admin") {
                    courList.options.add(option);
                    option.text = text;
                    option.value = value;
                }
                else {
                    //adding the option to the listbox
                    optList.options.add(option);
                    option.text = evalList.options[count].text;
                    option.value = evalList.options[count].value;
                }
                //evalList.remove(count, null);
            }
        }
    });

    // TODO: use Bootstrap modals when this is revisited

    //this is the add question dialog with functionality
    $("#Add-Question").dialog({
        autoOpen: false,
        height: 250,
        width: 600,
        modal: true,
        resizable: false,


        //add question button on form
        buttons: {
            "Add Question": function (e) {
                e.preventDefault();
                var bValid = true;
                var show;
                //removing the state error class from all inputs
                allFields.removeClass("ui-state-error");

                bValid = bValid && checkLength(description, 0, "Description");
                bValid = bValid && checkUnderscore(description);


                if (bValid) {
                    var creator, desc, name;
                    desc = description.val();
                    creator = $('#user').val();
                    name = $('#userName').val();
                    //setting up the variables to be pasted into the addQuestion function in controller
                    var ara = {
                        i_id: $('#user').val(),
                        description: description.val(),
                        subject: $('#subject').val(),
                        number: $('#courNum').val()
                    };

                    var url = $('#url').val() + 'instructor_questions/addQuestion';

                    //ajax post back with url of controller and method
                    $.ajax({
                        url: url,
                        data: ara,
                        type: 'POST',
                        //dataType: 'JSON',
                        //on success the listbox is updated with the data from the method inside the controller
                        success: function (data) {

                            var myData = data;

                            if (myData == 0) {
                                alert("This question either already exists for this course or has already been created by an Administrator. \nSelect it from one of the list boxes or contact an Administrator.");
                                description.val('');
                            }

                            else {

                                //this selects the listbox element
                                var courList = document.getElementById("courseQuestions");
                                //this selects the options inside the listbox element
                                var oOption = document.createElement("OPTION");

                                //adding the option to the listbox
                                courList.options.add(oOption);
                                oOption.text = name + "_" + $('#subject').val() + $('#courNum').val() + "_" + desc;
                                oOption.value = myData + "_" + creator;


                                description.val('');

                            }


                        },
                        //error checker
                        error: function (data, textStatus, errorThrown) {
                            alert(textStatus + ": " + errorThrown);
                        }
                    });
                    //closing out the dialog form*/
                    $(this).dialog("close");
                }
            },
            //cancel button
            Cancel: function () {
                //remove error states
                allFields.removeClass("ui-state-error");
                $(this).dialog("close");
            }
        },
        close: function () {
            allFields.removeClass("ui-state-error");
            description.val('');
        }
    });//end addQuestion Dialog

    $("#Remove-Question").dialog({
        autoOpen: false,
        height: 200,
        width: 400,
        modal: true,
        resizable: false,

        //the yes button that does the function
        buttons: {
            "Yes": function () {

                //selecting the listbox element
                var list = document.getElementById("courseQuestions");
                //for loop running through all of the options in the listbox
                for (var count = list.options.length - 1; count >= 0; count--) {
                    //if the options is selected remove from the listbox
                    if (list.options[count].selected == true) {
                        var str = list.options[count].value
                        var arr = str.split("_");
                        var ara = {
                            i_id: $('#user').val(),
                            c_id: $('#c_id').val(),
                            q_id: arr[0]
                        };
                        var url = $('#url').val() + 'instructor_questions/removeQuestion';

                        $.ajax({
                            url: url,
                            data: ara,
                            type: 'POST',
                            //on success
                            success: function (data) {

                                if (data == 0) {
                                    alert("Invalid. Can't remove someone elses quesiton.");
                                }
                                else {
                                    //sends alert of the data(name) from inside the controller
                                    var list = document.getElementById("courseQuestions");

                                    //looping through the list box
                                    for (var cnt = list.options.length - 1; cnt >= 0; cnt--) {
                                        var str = list.options[cnt].value;
                                        var ara = str.split("_");
                                        //checking to see if the value inside the list box matches the data sent back
                                        if (ara[0] == data) {
                                            //list.remove(cnt, null);
                                        }
                                    }
                                }
                            },
                            error: function (data, textStatus, errorThrown) {
                                alert(textStatus + ": " + errorThrown);
                            }
                        });
                    }
                }

                $(this).dialog("close");
            },
            Cancel: function () {
                $(this).dialog("close");
            }
        },
        close: function () {

        }
    });//end remove-Question dialog

    //this is the modify question dialog with functionality
    $("#Modify-Question").dialog({
        autoOpen: false,
        height: 250,
        width: 600,
        modal: true,
        resizable: false,

        open: function () {

            var list = document.getElementById("courseQuestions");
            var str, array, calcString;
            for (var count = list.options.length - 1; count >= 0; count--) {
                if (list.options[count].selected == true) {
                    //get the different parts of the question
                    str = list.options[count].text;
                    array = str.split("_");

                    $('#descriptionMod').val(array[2]);
                    break;

                }//end if

            }
        },


        //modify question button on form
        buttons: {
            "Modify Question": function (e) {
                e.preventDefault();

                var calc, calcString;
                var bValid = true;
                var show;
                allFieldsMod.removeClass("ui-state-error");

                //removing the state error class from all inputs
                //allFields.removeClass( "ui-state-error" );

                bValid = bValid && checkLength(descriptionMod, 0, "Description");
                bValid = bValid && checkUnderscore(descriptionMod);


                if (bValid) {
                    //selecting the listbox element
                    var list = document.getElementById("courseQuestions");

                    var qidMod, modDesc;
                    //for loop running through all of the options in the listbox
                    for (var count = list.options.length - 1; count >= 0; count--) {
                        //setting up the variables to be posted into the ModifyQuestion function in controller
                        if (list.options[count].selected == true) {
                            var str = list.options[count].value;
                            var array = str.split("_");
                            qidMod = array[0];
                            break;
                        }//end if

                    }

                    var ara = {
                        descriptionMod: descriptionMod.val(),
                        q_id: qidMod,
                        i_id: $('#user').val(),
                        subject: $('#subject').val(),
                        number: $('#courNum').val()
                    };

                    modDesc = descriptionMod.val();
                    var url = $('#url').val() + 'instructor_questions/modifyQuestion';

                    //ajax postback with url of controller and method
                    $.ajax({
                        url: url,
                        data: ara,
                        type: 'POST',
                        //on success the listbox is updated with the data from the method inside the controller
                        success: function (data) {

                            var calcString, show, calc;

                            if (data == 0) {
                                alert("This question either already exists for this course or has already been created by an Administrator. \nSelect it from one of the list boxes or contact an Administrator.");
                                descriptionMod.val('');
                            }
                            else if (data == -1) {
                                alert("Invalid. That question doesn't belong to you.");
                                descriptionMod.val('');
                            }
                            else {
                                //this selects the listbox element
                                var list = document.getElementById("courseQuestions");

                                for (var cnt = list.options.length - 1; cnt >= 0; cnt--) {
                                    var str = list.options[cnt].value;
                                    var ara = str.split("_")
                                    //checking to see if the value inside the list box matches the data sent back
                                    if (ara[0] == qidMod) {
                                        list.options[cnt].text = $('#userName').val() + "_" + $('#subject').val() + $('#courNum').val() + "_" + modDesc;
                                        list.options[cnt].value = data + "_" + $('#user').val();
                                        break;
                                    }
                                }

                                //numberMod.val('');
                                descriptionMod.val('');
                            }

                        },
                        //error checker
                        error: function (data, textStatus, errorThrown) {
                            alert(textStatus + ": " + errorThrown);
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

    //remove question dialog pops up when you click the remove button
    $("#RemoveButton").click(function () {

        var valid = false;

        //selecting the listbox element
        var list = document.getElementById("courseQuestions");


        if (list.options.length == 0) {
            alert("You must move questions over from the right box to remove");
        }
        else {
            //for loop running through all of the options in the listbox
            for (var count = list.options.length - 1; count >= 0; count--) {
                if (list.options[count].selected) {
                    var str = list.options[count].value;
                    var ara = str.split("_");
                    if (ara[1] == $('#user').val().trim()) {
                        valid = true;
                    }
                    else {
                        valid = false;
                    }
                    break;
                }
            }

            if (valid) {
                $("#Remove-Question").dialog("open");
            }
            else {
                alert("You must select an item that belongs to you to remove.");
            }
        }
    });

    //add question form pops up when you click the add button
    $("#AddQuestionButton").click(function () {
        $("#Add-Question").dialog("open");
    });

    //checking to see if an option inside the listbox is double clicked then calling the remove form
    $("#courseQuestions").dblclick(function () {
        var valid = false;

        //selecting the listbox element
        var list = document.getElementById("courseQuestions");

        //for loop running through all of the options in the listbox
        for (var count = list.options.length - 1; count >= 0; count--) {
            //if the options is selected
            if (list.options[count].selected == true) {
                var str = list.options[count].value;
                var ara = str.split("_");
                if (ara[1] == $('#user').val().trim()) {
                    valid = true;
                }
                else {
                    valid = false;
                }
                break;
            }
        }

        if (valid) {
            $("#Modify-Question").dialog("open");
        } else {
            alert("You must select an item that belongs to you to modify.");
        }
    });

    //modify question form pops up when you click the add button
    $("#ModifyButton").click(function () {

        var valid = false;
        var user = $('#user').val();

        //selecting the listbox element
        var list = document.getElementById("courseQuestions");
        var check = 0;
        for (var cnt = list.options.length - 1; cnt >= 0; cnt--) {
            if (list.options[cnt].selected == true) {
                check++;
            }
        }

        if (check > 1) {
            alert("You can only modify one question at a time");
        }
        else if (list.options.length == 0) {
            alert("You must move questions over from the right box to modify");
        }
        else {
            //for loop running through all of the options in the listbox
            for (var count = list.options.length - 1; count >= 0; count--) {
                //if the options is selected
                if (list.options[count].selected == true) {
                    var str = list.options[count].value;
                    var ara = str.split("_");
                    if (ara[1] == user.trim()) {
                        valid = true;
                    }
                    else {
                        valid = false;
                    }
                    break;
                }
            }

            if (valid) {
                $("#Modify-Question").dialog("open");
            } else {
                alert("You must select an item that belongs to you to modify.");
            }
        }
    });

    $("#description").focus(function () {
        updateTips("Please enter a question for your course");
    });

    $("#descriptionMod").focus(function () {
        updateTips("Please enter a question for your course");
    });
});