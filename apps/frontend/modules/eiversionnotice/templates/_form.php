<?php use_stylesheets_for_form($form) ?>

<?php if(isset($url_form) && isset($form)): ?>
<?php $url_form=$url_form->getRawValue() ?>

<form action="<?php echo url_for2("detailsNoticeActions",$url_form) ?>" id="ei_version_notice_form" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?> > 
    <?php echo $form->renderGlobalErrors(); ?>
    <?php echo $form->renderHiddenFields(false) ?>   
    <?php echo $form['description'] ?>
    <?php echo $form['expected'] ?>
    <?php echo $form['result'] ?> 
    <script>
        VnInParamaters=<?php print_r(json_encode($inParameters->getRawValue())) ?>;
        VnOutParameters=<?php print_r(json_encode($outParameters->getRawValue())) ?> ;
        </script>
</form>
<?php endif; ?>
<?php use_javascripts_for_form($form) ?>