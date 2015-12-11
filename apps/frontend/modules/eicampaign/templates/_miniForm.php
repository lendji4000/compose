<?php if (isset($form)) : ?> 
    <div class="form-group"> 
        <label class="control-label col-md-3" for="text-input">
            <?php echo $form['name']->renderLabel() ?>
        </label>
        <div class="col-md-9">
            <?php echo $form['name']->renderError() ?>
            <?php echo $form['name']->render() ?> 
            <span class="help-block">Enter campaign name</span>
        </div>
    </div>   
    <div class="form-group"> 
        <label class="control-label col-md-3" for="text-input">
            <?php echo $form['on_error']->renderLabel() ?>
        </label>
        <div class="col-md-9">
            <?php echo $form['on_error']->renderError() ?>
            <?php echo $form['on_error']->render() ?> 
            <span class="help-block">What to do when error occur ...</span>
        </div>
    </div>   
    <div><?php echo $form['coverage']->renderError() ?></div>
    <div class="form-group"> 
        <label class=" col-md-3 control-label" for="textarea-input"> Coverage </label>
            <div class="col-md-9"> 
                <div class="row">
                   <div class="col-lg-3">
                    <?php echo $form['coverage']->render() ?>
                    </div> 
                    <div class="col-lg-7">
                    <?php $coverage=$form['coverage']->getValue();
                        if ($coverage <= 50)
                            $bgColor= "rgb(255,".ceil(($coverage * 2 * 255) / 100) . ",0)";

                        else
                            $bgColor = "rgb(" . ceil(((100 - $coverage) * 2 * 255) / 100) . ", 255,0)";

                        ?>
                        <div class="progress" id="campaignCoverageProgress">
                            <div id="ei_campaign_coverage_indicator" class="progress-bar indicator"
                                 aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                 style="<?php echo 'width : '.$coverage.'%; background-color: '.$bgColor ?>">
                              <span class="sr-only"><?php   echo $coverage.'%' ?></span>
                            </div>
                        </div>
                    </div> 
                </div>
                
            </div>
    </div>
    <div class="form-group"> 
        <label class=" col-md-3 control-label" for="textarea-input">
                <?php echo $form['description']->renderLabel() ?>
            </label>
            <div class="col-md-9">
                <?php echo $form['description']->renderError() ?>
                <?php echo $form['description']->render() ?>
            </div>
    </div>
<?php endif; ?> 
    
    
    
 
                 