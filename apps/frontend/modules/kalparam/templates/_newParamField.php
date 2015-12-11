<tr>
    <td> 
        <?php echo $form['kalParams'][$number]['param_type'] ?>
        <?php echo $form['kalParams'][$number]['name'] ?>
        <?php echo $form['kalParams'][$number]['name']->renderError() ?> 
    </td>
    <td> 
        <?php echo $form['kalParams'][$number]['description'] ?> 
        <?php echo $form['kalParams'][$number]['description']->renderError() ?> 
    </td>
    
    <td> 
        <?php if($form['kalParams'][$number]['param_type']->getValue()=='IN'): ?>
        <?php echo $form['kalParams'][$number]['default_value'] ?>
        <?php echo $form['kalParams'][$number]['default_value']->renderError() ?> 
        <?php endif; ?>
    </td>
    <td>
        <a href="#" class="btn btn-xs btn-danger removeKalParamField">
            Remove
        </a>
    </td>
</tr> 