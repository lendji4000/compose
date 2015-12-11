<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php
if( $form->getObject() instanceof EiBlockForeach ){
    $type = EiVersionStructure::$TYPE_FOREACH;
}
else{
    $type = "EiBlock";
}
?>

<form class="form-horizontal" id="EiBlockForm" action="" method="post">
    <div class="row-fluid">
        <?php echo $form->renderHiddenFields(false) ?>
    </div>

    <div class="row-fluid">
        <?php echo $form['name']->renderError() ?>
        <?php echo $form->renderGlobalErrors() ?>
    </div>

    <div class="row-fluid table-responsive">
        <h6>Properties</h6>

        <table class="table table-bordered table-striped dataTable">
            <tr>
                <th>Block Name</th>
                <td colspan="2"><?php echo $form['name'] ?></td>
            </tr>
            <tr>
                <th>Block Description</th>
                <td colspan="2"><?php echo $form['description'] ?></td>
            </tr>
        </table>
    </div>

    <?php if (!$form->getObject()->isNew()): ?>
    <div class="table-responsive">
        <h6>Parameters</h6>

        <table class="table table-bordered table-striped blockParamsFormList dataTable">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <?php if (isset($form['EiBlockParams'])): ?>
                <?php foreach($form['EiBlockParams'] as $key => $fieldSchema): ?>
                    <?php include_partial('blockparam/newBlockParam',array('form' => $form, 'size' => $key)) ?>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="4">
                    <a href="<?php echo url_for2('addBlockParam') ?>" class="btn btn-mini btn-success addParamToBlockButton">
                         <?php echo ei_icon('ei_add','lg') ?>
                    </a>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>

    <?php if( $form->getObject() instanceof EiBlockForeach ){ ?>
    <div>
        <h6>Node to iterate</h6>

        <?php if (isset($form['Iterator'])): ?>
            <?php echo $form['Iterator']->renderHiddenFields(); ?>
        <?php endif; ?>

        <div>
            <?php include_component("block", "selectorNodeForm"); ?>
        </div>
    </div>
    <?php } ?>

    <?php $form->renderHiddenFields(); ?>

    <input type="hidden" name="typeBlock" value="<?php echo $type ?>" />
</form>