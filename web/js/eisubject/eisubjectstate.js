
$(document).ready(function () {
    var currentEditSubjectState;
    //Edition d'un statut de livraison
    $(this).delegate(".editBugState", "click", openEditBugStateBox);
    //Sauvegarde d'un statut de livraison
    $(this).delegate(".saveEiBugsState", "click", saveEiBugsState); 
});
//ouverture de la box de gestion d'un statut de bug  pour edition
function openEditBugStateBox(e){
    e.preventDefault();
    var elt = $(this);
    currentEditSubjectState=elt;
    $("#bugsStateBox").modal('show'); 
    editBugState(elt);
}
function editBugState(elt){  
    $.ajax({
        type: 'POST',
        url: elt.attr('href'), 
        dataType: 'json',
        async: false,
        beforeSend: function () {
        },
        success: function (response) { 
            if (response.success) { 
                $("#bugsStateBoxContent").empty().append(response.html);
                //elt.parents('.bugsStateLine').replaceWith(response.html);   
            } 
            else{
                return false;
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
 
//Sauvegarde d'un statut de livraison
function  saveEiBugsState(e) {
    e.preventDefault();
    var elt=$(this) ;
    var stateLine=currentEditSubjectState.parents('.bugsStateLine');
    var form=elt.parents('.bugsStateForm');
    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'json',
        async: false,
        beforeSend: function () {
        },
        success: function (response) {
            
            if (response.success) {
               stateLine.replaceWith(response.html);
               $("#bugsStateBox").modal('hide'); 
            } 
            else{
                form.replaceWith(response.html);
            }
        },
        error: function (response) {
            if (response.status == '401')
                window.location.href = window.location.pathname;
            alert('Error ! Problem when processing');
        }
    });
}
  
