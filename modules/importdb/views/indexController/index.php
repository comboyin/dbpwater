<!-- Form Mixin-->
    <!-- Pen Title-->
    <div class="pen-title">
        <h3>IMPORT DB</h3>
    </div>

    <!-- BEGIN alert  -->
    <?php if(isset($error)):?>
    <div class="alert alert-error">
        <strong>Error! </strong>
		<?php echo $error;?>
    </div>
    <?php endif;?>
<?php

?>

    <div class="module">
        <div class="clearfix" id="result"></div>
        <div class="form row">
            <div class="col-md-6 col-md-offset-3">
                <form id="importDB" method="post" action="" >
                    <div class="form-group row">
                        <label for="ip" class="col-sm-2 col-form-label">IP</label>
                        <div class="col-sm-10">
                            <input type="text" required class="form-control" id="ip" name="ip" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="user" class="col-sm-2 col-form-label">User</label>
                        <div class="col-sm-10">
                            <input type="text" required class="form-control" id="user" name="user" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" required class="form-control" id="password" name="password" placeholder="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dbname" class="col-sm-2 col-form-label">DB name</label>
                        <div class="col-sm-10">
                            <input type="text" required class="form-control" id="dbname" name="dbname" placeholder="">
                        </div>
                    </div>
                    <span class="note">Note: DB will drop if exists, all data will be deleted</span>
                    <div class="form-group row">
                        <div class="col-sm-10 col-sm-offset-2">
                            <select class="form-control wide select-env" id="env" name="env">
                                <option disabled="disabled" selected value="">Select environment</option>
                                <option value="dev">Dev</option>
                                <option value="pre">Pre</option>
                                <option  value="debug1">Debug 1</option>
                                <option  value="debug2">Debug 2</option>
                                <option  value="debug3">Debug 3</option>
                                <option  value="test">Test</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-10 submit-wrapper">
                            <button type="button" class="btn btn-default btn-import" value="submit">Import</button>
<!--                            <button type="submit" class="btn btn-default btn-import" value="submit">Import</button>-->
                        </div>
                    </div>
                    <input type="hidden" name="check_database" id="checkDatabase" value="" >
                </form>
                <!-- Modal confirm -->
                <div class="modal fade" id="confirmImportModal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Confirm</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default modal-btn-yes">OK</button>
                                <button type="button" class="btn btn-default modal-btn-no" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal check if database exists -->
                <div class="modal fade" id="checkDatabaseModal" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Message</h4>
                            </div>
                            <div class="modal-body">
                                <p>A database with this name already exists, still process form ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default check-db-btn-yes">OK</button>
                                <button type="button" class="btn btn-default check-db-btn-no" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="overlay">
        <div class="loading-image">
            <img src="public/img/loading1.gif" >
            <p>Data processing, please wait...</p>
        </div>
    </div>