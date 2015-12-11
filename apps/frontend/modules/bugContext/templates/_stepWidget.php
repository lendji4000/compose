<?php if (isset($form)): ?>
    <div class="controls contextCampaignStepFormPart"> 
        <?php echo $form['campaign_graph_id']->renderError() ?>
        <?php echo $form['campaign_graph_id'] ?>
    </div>
<?php endif; ?>