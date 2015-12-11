<tr>
    <td>
        <?php echo $form['EiBlockParams'][$size]['name'] ?>
        <?php echo $form['EiBlockParams'][$size]['name']->renderError() ?>
    </td>
    <td>
        <?php echo $form['EiBlockParams'][$size]['description'] ?>
        <?php echo $form['EiBlockParams'][$size]['description']->renderError() ?>
    </td>
    <td>
        <?php echo $form['EiBlockParams'][$size]->renderHiddenFields() ?>
        <a href="#" data-id="<?php echo $form['EiBlockParams'][$size]["id"]->getValue() ?>" class="btn btn-mini btn-danger removeParamToBlockButton">
            Remove
        </a>
    </td>
</tr>