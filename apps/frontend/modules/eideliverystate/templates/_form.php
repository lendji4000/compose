<?php if (isset($formParam)): ?>
    <?php $formParam = $formParam->getRawValue() ?>
    <?php $form = $formParam['form']; ?>
    <?php $url_tab = $formParam;
    unset($url_tab['form']); ?>  

    <?php use_stylesheets_for_form($form) ?>
    <?php use_javascripts_for_form($form) ?>
    <?php $uriForm = $url_tab;
    $uriForm['state_id'] = $form->getObject()->getId();
    $uriForm['action'] = 'update'; ?> 

    <form class="deliveryStateForm" action="<?php echo url_for2('delivery_state_edit', $uriForm) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <?php if (!$form->getObject()->isNew()): ?>
            <input type="hidden" name="sf_method" value="put" />
                    <?php endif; ?> 
        <table class="table small-font table-condensed table-striped dataTable">
            <tr  >
                <th> Name </th>
                <td class="ei_deliveries_state">
                <?php echo $form->renderHiddenFields() ?>
                <?php echo $form['name']->renderError() ?>
                <?php echo $form['name'] ?>
                </td>
            </tr>
            <tr  >
                <th> Color code</th>
                <td class="ei_deliveries_state_color">
                    <?php echo $form['color_code']->renderError() ?>
                    <?php echo $form['color_code'] ?>
                </td>
            </tr>
            <tr  >
                <th>Display in home page ?</th>
                <td class="ei_deliveries_state_display_homepage"> 
                    <?php echo $form['display_in_home_page']->renderError() ?>
                    <?php echo $form['display_in_home_page'] ?>
                </td>
            </tr>
            <tr  >
                <th>Display in search ?</th>
                <td class="ei_deliveries_state_display_search"> 
                    <?php echo $form['display_in_search']->renderError() ?>
                    <?php echo $form['display_in_search'] ?>
                </td>
            </tr> 
            <tr >
                <th>Close delivery state ? </th>
                <td class="ei_deliveries_state_close_state"> 
                    <?php echo $form['close_state']->renderError() ?>
                    <?php echo $form['close_state'] ?>
                </td>   
            </tr>
            <tr  >
                <th> Updated at</th>
                <td class="ei_deliveries_state_updated">   <?php echo $form->renderGlobalErrors() ?> </td>  
            </tr>
            <tr  >
                <th>Actions</th>
                <td>  
                    <input type="submit" value="Save" class="saveEiDeliveriesState btn btn-sm btn-success" />  
                </td> 
            </tr>
            </tr>
        </table> 
<?php endif; ?>
</form> 