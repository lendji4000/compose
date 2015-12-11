<div id="executionMenu">
    <div class="row" id="eisge-object">
        <h2 id="titleExecutionMenu">
            <?php echo ei_icon('ei_devices') ?>
            <!--Devices choice and execution's options-->
            Execution's options for devices
        </h2>
    <button id="closeExecutionMenu" class='btn btn-default'>X</button>
    </div>
    <div class="panel panel-default eiPanel" >
        <div class="panel-heading">
            <h2>
                <?php echo ei_icon('ei_devices') ?>
                <strong class="panelHeaderTitle">Devices options</strong>
            </h2>
        </div>
        <div class="panel-body dateTimePickerFix">
            <div class="col-lg-3 col-md-3 ">
                <div class="form-group">
                    <label class="control-label col-md-4" for="ei_execution_stack_expected_date">Expected date</label>
                    <div class='col-sm-8'>
                        <div id="datetimepickerExpectedDate" class="input-group input-append  date">
                            <input id="ei_execution_stack_expected_date" class="form-control col-lg-8 col-md-8 col-sm-8" type="text" data-format="yyyy/MM/dd hh:mm:ss">
                            <span class="input-group-addon add-on">
                                <i class="fa fa-calendar"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 ">
                <div class="form-group">
                    <label class="control-label col-md-4" for="ei_execution_stack_nb_execs">Number of executions</label>
                    <div class='col-sm-4'>
                        <input id="ei_execution_stack_nb_execs" class="form-control col-lg-8 col-md-8 col-sm-8" type="text" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>