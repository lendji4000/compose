<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="row">
    <?php include_partial("configureToken"); ?>
</div>

<div class="row">
    <div class="col-lg-12">
        <form action="<?php echo url_for('eiuser/' . ($form->getObject()->isNew() ? 'create' : 'update')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

            <div class="panel panel-default eiPanel">
                <div class="panel-heading">
                    <h2>
                         <i class="fa fa-gear"></i> Define firefox settings
                    </h2>
                    <div class="panel-actions">
                    </div>
                </div>
                    <?php if (!$form->getObject()->isNew()): ?>
                        <input type="hidden" name="sf_method" value="put" />
                    <?php endif; ?>
                    <div class="panel-body">
                        <div class="row">
                            <?php echo $form->renderHiddenFields(); ?>
                            <?php echo $form->renderGlobalErrors(); ?>
                            <?php echo $form['firefox_path']->renderError() ?>
                        </div>
                        <div class="form-group">
                            <label class=" col-md-3 control-label" for="textarea-input">
                                Path to .exe
                            </label>
                            <div class="col-md-9">
                                <?php echo $form['firefox_path']->render() ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input type="submit" value="Save" class="btn btn-success " />
                    </div>
            </div>
        </form>
    </div>
</div>

<!--    <div class="panel panel-default eiPanel">-->
<!--        <div class="panel-heading">-->
<!--            <h2>-->
<!--                <i class="fa fa-gear"></i> Define excel settings-->
<!--            </h2>-->
<!--            <div class="panel-actions">-->
<!--            </div>-->
<!--        </div>-->
<!--        --><?php //if (!$form->getObject()->isNew()): ?>
<!--            <input type="hidden" name="sf_method" value="put" />-->
<!--        --><?php //endif; ?>
<!--        <div class="panel-body">-->
<!--            <div class="row">-->
<!--                --><?php //echo $form->renderHiddenFields(); ?>
<!--                --><?php //echo $form->renderGlobalErrors(); ?>
<!--                --><?php //echo $form['excel_mode']->renderError() ?>
<!--            </div>-->
<!--            <div class="form-group">-->
<!--                <label class=" col-md-3 control-label" for="textarea-input">-->
<!--                    Mode-->
<!--                </label>-->
<!--                <div class="col-md-9">-->
<!--                    --><?php //echo $form['excel_mode']->render() ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="panel-footer">-->
<!--            <input type="submit" value="Save" class="btn btn-success " />-->
<!--        </div>-->
<!--    </div>-->