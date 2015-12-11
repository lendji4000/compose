
$(document).ready(function () {
    var currentEditDeliveryState;
    //Edition d'un statut de livraison
    $(this).delegate(".editDeliveriesState", "click", openEditDeliveryStateBox);
    //Sauvegarde d'un statut de livraison
    $(this).delegate(".saveEiDeliveriesState", "click", saveEiDeliveriesState); 
});
//ouverture de la box de gestion d'un statut de livraison pour edition
function openEditDeliveryStateBox(e){
    e.preventDefault();
    var elt = $(this);
    currentEditDeliveryState=elt;
    $("#deliveryStateBox").modal('show'); 
    editDeliveriesState(elt);
}

function editDeliveriesState(elt){  
    $.ajax({
        type: 'POST',
        url: elt.attr('href'), 
        dataType: 'json',
        async: false,
        beforeSend: function () {
        },
        success: function (response) { 
            if (response.success) {
                $("#deliveryStateBoxContent").empty().append(response.html);   } 
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
function  saveEiDeliveriesState(e) {
    e.preventDefault();
    var elt=$(this) ; 
    var stateLine=currentEditDeliveryState.parents('.deliveriesStateLine');
    var form=elt.parents('.deliveryStateForm');  
    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        data: form.serialize(),
        dataType: 'json',
        async: false,
        beforeSend: function () {
        },
        success: function (response) {
            stateLine.replaceWith(response.html);
            if (response.success) {
               stateLine.replaceWith(response.html);
               $("#deliveryStateBox").modal('hide'); 
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
  
