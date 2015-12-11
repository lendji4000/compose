 
<form id="functionNoticeForm" action="<?php echo $url_form ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <table class="table table-striped dataTable bootstrap-datatable">
        <?php if (!$form->getObject()->isNew()): ?>
            <input type="hidden" name="sf_method" value="put" />
        <?php endif; ?>
        <tfoot>
            <tr>
                <td colspan="2">
                    <?php echo $form->renderHiddenFields(false) ?>   
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php echo $form->renderGlobalErrors() ?>
            <tr>
                <th><?php echo $form['description']->renderLabel() ?></th>
                <td>
                    <?php echo $form['description']->renderError() ?>
                    <?php echo $form['description'] ?>
                </td>
            </tr>
            <tr>
                <th><?php echo $form['expected']->renderLabel() ?></th>
                <td>
                    <?php echo $form['expected']->renderError() ?>
                    <?php echo $form['expected'] ?>
                </td>
            </tr>
            <tr>
                <th><?php echo $form['result']->renderLabel() ?></th>
                <td>
                    <?php echo $form['result']->renderError() ?>
                    <?php echo $form['result'] ?>
                </td>
            </tr> 
        </tbody>
    </table>
</form>  

