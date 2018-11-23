<?php
include 'header.php';
?>

<div id="admin-help" class="row">
    <div class="panel panel-info">
        <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
            <h2><i class="fa fa-question-circle help accent"></i>Frequently Asked
                Questions</h2>
        </div>

        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">

                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#getting_started">
                        <h4 class="panel-title">What are the first steps of setting up Course Evaluator?</h4>
                    </div>

                    <div id="getting_started" class="panel-collapse collapse">
                        <div class="panel-body">
                            <ol>
                                <li>
                                    Click on "Courses" in the navigation menu, select "Upload Courses". You will see the
                                    <span class="btn btn-primary btn-sm disabled">Prepare the Database</span> button. Clicking
                                    this button will show the selection of courses that you may want
                                    to evaluate during current quarter/period. You will see a table listing of courses
                                    that are available.
                                    Select courses individually or select all by clicking top left checkbox and showing
                                    all records at once.
                                    Now this application will know which courses can be evaluated
                                </li>
                                <li>
                                    Go to "Questions/add required questions" on the navigation menu.
                                    Using this page you can set default questions that will appear on every course
                                    evaluation.
                                    You can then add specific questions to the courses of your choosing using
                                    "Questions/course questions" page
                                </li>
                                <li>Provide the link for this website to students so that they can evaluate courses they
                                    are currently enrolled in.
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion"
                         href="#connection_issues">
                        <h4 class="panel-title">When do I need a secure network access?</h4>
                    </div>

                    <div id="connection_issues" class="panel-collapse collapse">
                        <div class="panel-body">
                            The "upload courses" page requires VPN to get the course data.<br>
                            If you are not on EWU network, set up VPN by going to this website: <a
                                href="http://access.ewu.edu/it/services/it-training/documentation-and-resources/virtual-private-network">faculty vpn</a> or <a
                                href="http://itech.ewu.edu/vpn.php">student vpn</a>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#add_questions">
                        <h4 class="panel-title">How do I create, modify, and remove questions for students to
                            answer?</h4>
                    </div>

                    <div id="add_questions" class="panel-collapse collapse">
                        <div class="panel-body">
                            The "required questions" and "course questions" pages have options to add or modify the
                            existing questions for the evaluation.
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#generate_reports">
                        <h4 class="panel-title">What are the evaluation reports?</h4>
                    </div>

                    <div id="generate_reports" class="panel-collapse collapse">
                        <div class="panel-body">
                            This functionality generates summary of the course using studen't ratings of the courses as
                            well as showing how many students submitted these evaluations.
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#know_admin">
                        <h4 class="panel-title">How does this app know if I'm an admin?</h4>
                    </div>

                    <div id="know_admin" class="panel-collapse collapse">
                        <div class="panel-body">
                            When you login with Eastern's Single Sign-On (SSO), we can tell if you are an admin.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <h1>Page Guide</h1>

    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse2">

                <h4 class="panel-title"><span class="glyphicon glyphicon-user help accent" aria-hidden="true"></span>Managing Admins</h4>
            </div>
            <div id="collapse2" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>Administrators may add, modify or remove other administrators. You cannot modify your admin info, however. Administrators will be able to add courses/questions to evaluate and view reports.</li>
                        <li>To add an admin: click <button class="btn btn-primary btn-sm disabled" title="Add">
                        <i class="fa fa-plus"></i> Add</button> selection to access the pop up input menu. The Net ID has to match their Eastern SSO Login ID.</li>
                        <li>To modify an existing online eval admin: double click the name to access the pop-up menu or select the name and
                            click "Modify".
                        </li>
                        <li>To remove an admin: select the name and click <button id="remove" class="btn btn-danger btn-sm disabled" ><i class="fa fa-times"></i> Remove</button> and click "remove user" in the warning prompt.</li>
                        <li>The "super" column refers to to the admin that can't be removed or modified.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse8">

                <h4 class="panel-title"><i class="fa fa-question-circle help accent"></i>Required Questions</h4>
            </div>
            <div id="collapse8" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>This page allows the administrator to add, modify, or remove questions which will be asked
                            on all evaluations.
                        </li>
                        <li>To add a question: click
                            <button class="btn btn-primary btn-sm disabled" title="Add">
                                <i class="glyphicon glyphicon-plus"></i></button>
                            "Add", fill in the question to be asked and click "Add Question".
                        </li>
                        <li>To add a question: click <button class="btn btn-primary btn-sm disabled" title="Add">
                        <i class="fa fa-plus"></i> Add</button>, fill in the question to be asked and click "Add Question".</li>
                        <li>To modify a question: click on the question you want to modify and click "Modify", or you
                            can double click the question in the list box.
                        </li>
                        <li>To remove a question: click on the question you want to remove and click  <button id="remove" class="btn btn-danger btn-sm disabled" ><i class="fa fa-times"></i> Remove</button> and confirm "remove question".</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse9">
                <h4 class="panel-title"><i class="fa fa-question-circle help accent"></i>Course Questions</h4>
            </div>
            <div id="collapse9" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>This page allows the administrator to add, modify, or remove course-specific questions
                            (instructor, departmental, or other).
                        </li>
                        <li>To add a question: click <button class="btn btn-primary btn-sm disabled" title="Add" >
                        <i class="fa fa-plus"></i> Add</button>, fill in the required fields, make sure to select the course this question applies to, and click "Add Question".</li>
                        <li>To modify a question: click on the question you want to modify and click "Modify", or you
                            can double click the question in the list box.
                        </li>
                        <li>To remove a question: click on the question you want to remove and click  <button id="remove" class="btn btn-danger btn-sm disabled" ><i class="fa fa-times"></i> Remove</button> and confirm "remove question".</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse3">

                <h4 class="panel-title"><i class="fa fa-cloud-upload help accent"></i>Course Upload</h4>
            </div>
            <div id="collapse3" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>This page allows the administrator to upload information to the evaluation database for
                            current quarter.
                        </li>
                        <li>Click <span class="btn btn-primary btn-sm disabled">Prepare the Database</span> button to establish
                            connection to the live listing of courses available. It will take some time to bring up the
                            course information table.
                        </li>
                        <li>If you want to view all courses at once, click the
                            <button class="btn btn-default btn-sm disabled" type="button" title="Hide/Show pagination"><i
                                    class="glyphicon glyphicon-collapse-down icon-chevron-down"></i></button>
                            button at the top right of the table.
                        </li>
                        <li>
                            <button type="button" class="btn btn-default dropdown-toggle btn-sm disabled" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-th icon-th"></i> <span class="caret"></span></button>
                            button will let you view additional fields/info about the courses in the table.
                        </li>
                        <li>Click on the courses you want evaluated and click "Submit". This may take some time.</li>
                    </ul>
                    <h4>Notes</h4>
                    <ul>
                        <li>Rows highlighted in red color indicate courses already uploaded to this application.</li>
                        <li>If selected, courses highlighted <span style="color: red">RED</span> will overwrite previous
                            changes
                            made through the <strong>Manage Courses</strong> page.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                <h4 class="panel-title"><i class="fa fa-tachometer accent help"></i>Manage Courses</h4>
            </div>
            <div id="collapse4" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>This page allows the administrator to manage the courses for the current quarter.</li>
                        <li>An admin may update the instructor for a course by selecting a course, clicking <span class="btn btn-primary btn-sm disabled"><i class="fa fa-files-o"></i> Modify instructor</span>  and choosing a new instructor from the list.</li>
                        <li><span class="btn btn-primary btn-sm disabled"><i class="fa fa-files-o"></i> Duplicate</span> a course in order to add another instructor.</li>

                        <li><span class="btn btn-danger btn-sm disabled"><i class="fa fa-times"></i> Remove</span> a course that should not be evaluated.</li>
                                <li>When the start or end date is modified, course rows will be <span class="label label-info">highlighted</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse10">
                <h4 class="panel-title"><i class="fa fa-calendar help accent"></i>Set Evaluation Period</h4>
            </div>
            <div id="collapse10" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>This page allows the administrator to set the time-frame during which a student may evaluate a course. </li>
                        <li>Select a course, and click <span class="btn btn-primary btn-sm disabled"><i class="fa fa-pencil-square-o"></i> Modify</span> to set eval start and stop times for the course by clicking start and stop fields and selecting the date.</li>
                         <li>If you want to view all courses at once, click the 
                            <button class="btn btn-default btn-sm disabled" type="button" title="Hide/Show pagination"><i class="glyphicon glyphicon-collapse-down icon-chevron-down">
                            </i></button> button at the top right of the table.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse5">

                <h4 class="panel-title"><i class="fa fa-eye help accent"></i>Evaluations</h4>
            </div>
            <div id="collapse5" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>This lets admins see what the questions will look like to students taking evals.</li>
                        <li>To view the evaluation questions for a particular course, click on one of the links.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse6">

                <h4 class="panel-title"><i class="fa fa-bar-chart help accent"></i>Reports</h4>
            </div>
            <div id="collapse6" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>This page allows the administrator to generate reports for a given class or multiple
                            classes.
                        </li>
                        <li>First, select one or more courses.</li>
                        <li>Raw scores can be obtained by clicking
                            <button class="btn btn-default btn-sm disabled">Scores</button>
                            . This will download a csv (excel) file.
                        </li>
                        <li>For raw (csv) comments, click
                            <button id="rawCommentGen" class="btn btn-default btn-sm disabled">
                                Comments
                            </button>
                            .
                        </li>
                        <li>To generate PDF reports&mdash;the most comprehensive&mdash;click
                            <button class="btn btn-primary btn-sm disabled">
                                PDF report
                            </button>
                            . This will a pdf browser page where you can save or print the report.
                        </li>
                        <li>Select
                            <button class="btn btn-primary btn-sm disabled">
                                Count report
                            </button>
                            to determine how many students have completed
                            evaluations for a given course. This will be in csv (excel) form.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#collapse7">
                <h4 class="panel-title"><i class="fa fa-gears help accent"></i>Settings</h4>
            </div>
            <div id="collapse7" class="panel-collapse collapse">
                <div class="panel-body">
                    <ul>
                        <li>The <strong>welcome message</strong> will be displayed at the top of the student homepage,
                            where students may select from a list of their current courses.
                        </li>
                        <li>The <strong>evaluation message</strong> is shown at the top of each evaluation.</li>
                        <li>Enabling <strong>developer mode</strong> will trigger developer-specific functionality.
                            Nothing will <em>break</em>, per se, but this will allow for some potentially unwanted
                            behavior.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

</div>