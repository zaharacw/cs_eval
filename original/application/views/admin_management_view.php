<?php
$pagetitle = 'Manage Administrators';
$scripts = array('core.js', 'admin_management.js');
include 'header.php';
?>

<div class="row">
    <div class="col-md-8">
        <div class="alert alert-danger alert-dismissible" role="alert" id="selectionAlert" hidden>
            <button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>Wait!</strong> You must select a user.
        </div>

        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="addModalLabel">Add user</h4>
                    </div>
                    <div class="modal-body">
                        <div id="addModalAlert" class="alert alert-info" role="alert">All fields required.</div>
                        <form>
                            <div class="form-group">
                                <label for="userFullname" class="control-label">Name:</label>
                                <input type="text" class="form-control" name="userFullname" id="userFullname">
                            </div>
                            <div class="form-group">
                                <label for="userId" class="control-label">NET ID:</label>
                                <input type="text" class="form-control" name="userId" id="userId"
                                       placeholder="ex: bob51">
                            </div>
                            <div class="form-group">
                                <label for="userEmail" class="control-label">Email:</label>
                                <input type="text" class="form-control" name="userEmail" id="userEmail">
                            </div>
                            <input type="hidden" id="modalOperation" value="add">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="addModalSubmit" type="button" class="btn btn-primary">Add user</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="removeModal" tabindex="-1" role="dialog" aria-labelledby="removeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="removeModalLabel">Remove user</h4>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove this user?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="removeModalSubmit" type="button" class="btn btn-danger">Remove user</button>
                    </div>
                </div>
            </div>
        </div>

        <h1><span class="glyphicon glyphicon-user accent" aria-hidden="true"></span>Manage Administrators</h1>
        <input type="hidden" id="name" value="<?php echo $currentAdmin['name']; ?>"/>
        <input type="hidden" id="user" value="<?php echo $currentAdmin['username']; ?>"/>
        <input type="hidden" id='email' value="<?php echo $currentAdmin['email']; ?>"/>

        <div id="toolbar">
            <button id="add" class="btn btn-primary" title="Add" data-toggle="modal" data-target="#addModal" data-operation="add">
                <i class="fa fa-plus"></i> Add
            </button>
            <button id="modify" class="btn btn-default" disabled data-toggle="modal" data-target="#addModal" data-operation="modify">
                <i class="fa fa-pencil-square-o"></i> Modify
            </button>
            <button id="remove" class="btn btn-danger" disabled data-toggle="modal" data-target="#removeModal">
                <i class="fa fa-times"></i> Remove
            </button>
        </div>
        <table id="table"
               data-toolbar="#toolbar"
               data-search="true"
               data-maintain-selected="true"
               data-click-to-select="true"
               class="table table-hover table-condensed"
            >
            <thead>
            <tr>
                <th data-field="state" data-radio="true"></th>
                <th data-field="name" data-sortable="true">name</th>
                <th data-field="username" data-sortable="true">username</th>
                <th data-field="email" data-sortable="true">email</th>
                <th data-field="super">super</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($admins as $index => $admin) : ?>
                <tr>
                    <td data-name="state"></td>
                    <td data-name="name"><?php echo $admin['name']; ?></td>
                    <td data-name="username"><?php echo $admin['username']; ?></td>
                    <td data-name="email"><?php echo $admin['email']; ?></td>
                    <td data-name="super"><?php echo $admin['super'] ? 'T' : 'F'; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="col-md-4">
        <div id="sidebar">
            <h2>Directions</h2>
            <ul>
                <li>Administrators may add, modify or remove other administrators.</li>
                <li>To add an admin: click <span class="button-help"><i class="fa fa-plus"></i> Add</span>.</li>
                <li>To modify an admin: double click the name or select the name and click <span class="button-help"><i class="fa fa-pencil-square-o"></i> Modify</span>.</li>
                <li>To remove an admin: select the name and click <span class="button-help"><i class="fa fa-times"></i> Remove</span>.</li>
                <li>You cannot modify or remove a super-admin or yourself.</li>
            </ul>
        </div>
    </div>
</div>
</div>
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.7.0/bootstrap-table.min.js"></script>
</body>
</html>