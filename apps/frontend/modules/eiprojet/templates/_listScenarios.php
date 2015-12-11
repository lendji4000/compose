<!--comments-->
<?php
    $action = url_for2('projet_eiscenario', array('id_projet' => $id_projet,
                                                    'profile_name'  => $profile_name,
                                                    'id_profil' => $id_profil,
                                                    'action' => "forwardToEdit"));
?>

<form action="<?php echo $action ?>" id="liste_scenario_form">

<?php
    echo $form['liste_scenario_choice'];
?>

</form>
