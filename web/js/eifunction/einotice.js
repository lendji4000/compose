$(document).ready(function() { 
    $(this).delegate(".showFunctionNotice", "click", showFunctionNotice); //Visualisation de la notice 
    $(this).delegate("#addFunctionNotice", "click", addFunctionNotice); // Surcharge de la notice
    $(this).delegate("#editFunctionNotice,#editDefaultNotice", "click", editFunctionNotice); // Edition de la notice de fonction coté client
    $(this).delegate("#saveFunctionNotice,#saveDefaultNotice", "click", saveFunctionNotice); // Surcharge de la notice 
    $(this).delegate("#saveVersionNotice", "click", saveVersionNotice); // Sauvegarde d'une version de notice 
    $(this).delegate("#ei_version_notice_form ", "submit", saveVersionNotice); // Sauvegarde d'une version de notice 
    $(this).delegate("#restoreDefaultFunctionNotice", "click", restoreDefaultFunctionNotice); //Restauration dela notice par défaut 
    $(this).delegate(".editVersionNoticeLang,.editVersionNotice", "click", editVersionNotice);  //Chargement d'une langue de notice
    $("#ei_version_notice_form").ready(function(){
        if( typeof VnInParamaters != "undefined" && typeof VnOutParameters != "undefined" ){
            initTinyMceFunctionNotice(VnInParamaters,VnOutParameters);
        }
    });

    $('#functionNoticeModal').on('hidden.bs.modal', function() {
        $('.modal-backdrop').first().remove();
    });
 
//
function editVersionNotice(e){
    e.preventDefault();   
        $.ajax({
            type: 'POST',
            url: $(this).attr('itemref'), 
            dataType: 'json',
            async: true,
            beforeSend: function() { // traitements JS à faire AVANT l'envoi  
                $("#noticeLoaderNotice").addClass("fa fa-spinner fa-spin fa-3x").css("display", "inline-block");
            },
            success: function(response) {
                if (response.success) {//Si erreur au niveau du formulaire
                    //On recharge l'en tête uniquement si c'est la version entière.Dans le cas ou c'est la langue de la notice, on ne recharge pas l'en-tête mais uniquement le formulaire
                    if(!$(this).hasClass("editVersionNoticeLang")){
                        $('#versionNoticeHeader').replaceWith(response.header);//On recharge l'en tête
                    } 
                    $("#versionNoticeForm").replaceWith(response.formContent); 
                } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() { 
            $("#noticeLoaderNotice").removeClass("fa fa-spinner fa-spin fa-3x"); 
            initTinyMceFunctionNotice(VnInParamaters,VnOutParameters);
        });
}
/* Sauvegarde de la version de notice d'une fonction */ 

    function saveVersionNotice(e) {
        e.preventDefault();  
        $('.tinyMceContent').tinymce().save(); //Récupération du contenu tinyMce pour remplir le textarea
        $('.tinyMceNoticeExpect').tinymce().save(); //Récupération du contenu tinyMce pour remplir le champ expect
        $('.tinyMceNoticeResult').tinymce().save(); //Récupération du contenu tinyMce pour remplir le champ result
        $.ajax({
            type: 'POST',
            url: $('#ei_version_notice_form').attr('action'),
            data: $('#ei_version_notice_form').serialize(),
            dataType: 'json',
            async: true,
            beforeSend: function() { // traitements JS à faire AVANT l'envoi 
                $("#saveVersionNotice").hide();
                $("#noticeLoader").addClass("fa fa-spinner fa-spin fa-3x").css("display", "inline-block");
            },
            success: function(response) {
                if (!response.success) {//Si erreur au niveau du formulaire
                    $('#ei_version_notice_form').replaceWith(response.html);//On recharge le formulaire entier
                    $("#alertNoticeMsg").find(".alert-danger").fadeIn(2000).fadeOut(2000);
                }
                else { //La sauvegarde du formulaire a bien été éffectuée   
                    $("#alertNoticeMsg").find(".alert-success").fadeIn(2000).fadeOut(2000);
                } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {
            $("#saveVersionNotice").show();
            $("#noticeLoader").removeClass("fa fa-spinner fa-spin fa-3x"); 
        });
    }
    
/* Ajout d'une notice de fonction  (surcharge de la notice provenant de la plate forme centrale ) */ 
    
    /* Visualisation de la notice d'une fonction */
    function showFunctionNotice(e) { 
        e.preventDefault();
        var elt = $(this);
        $.ajax({
            type: "POST",
            url: elt.attr('href'),
            dataType: 'json', 
            success: function(response) {
                //Si une fenetre modal est déjà définie , on remplace son contenu
                if ($('#functionNoticeModal').length > 0)
                    $('#functionNoticeModal').replaceWith(response.html);

                else
                    $('#block').append(response.html);

                $('#functionNoticeModal').modal('show');
                //Make modal draggable
                $("#functionNoticeModal").draggable({
                    handle: ".modal-content"
                });
            },
            error: function(response) {
                if (response.status == '401') //Si utilisateur déconnecté
                    window.location.href = window.location.pathname;

            }
        }).done(function() {   });
    } 

    /* Ajout d'une notice de fonction  (surcharge de la notice provenant de la plate forme centrale ) */

    function addFunctionNotice(e) {
        e.preventDefault();
        var result, elt = $(this);
        $.ajax({
            type: "POST",
            url: elt.attr('href'),
            dataType: 'json', 
            success: function(response) {
                //Si une fenetre modal est déjà définie , on remplace son contenu
                if ($('#functionNoticeModal').length > 0)
                    $('#functionNoticeModal').replaceWith(response.html);

                else
                    $('#block').append(response.html);
                 
                //On met à jour les paramètres d'entrée du tinyMCE
                var InFunctionParameters=response.inParameters; 
                var OutFunctionParameters=response.outParameters; 
                //Ajout des paramètres dans la fenêtre tinymce
                
                initTinyMceFunctionNotice(InFunctionParameters,OutFunctionParameters); 
                
                //On montre la fenêtre modale
                $('#functionNoticeModal').modal('show');
                //Make modal draggable
                $("#functionNoticeModal").draggable({
                    handle: ".modal-content"
                });
                result = response;
                
            },
            error: function(response) {
                if (response.status == '401') //Si utilisateur déconnecté
                    window.location.href = window.location.pathname;

            }
        }).done(function() { //Traitement de fin d'execution 
            if ($('.modal-backdrop').length > 1)
                $('.modal-backdrop').first().remove(); 
            //Message d'alert
            $('#functionNoticeModalAlert').addClass(result.alert_class);
            $('#functionNoticeModalAlert').text(result.alert_message);
        });
    }
    /* Edition de la notice coté client ( compose.kalifast ) */
    function editFunctionNotice(e) {
        e.preventDefault();
        var elt = $(this);
        
        $.ajax({
            type: "POST",
            url: elt.attr('href'),
            dataType: 'json', 
            success: function(response) {
                //Si une fenetre modal est déjà définie , on remplace son contenu
                if ($('#functionNoticeModal').length > 0)
                    $('#functionNoticeModal').replaceWith(response.html);
                else
                    $('#block').append(response.html);

                //On met à jour les paramètres d'entrée du tinyMCE
                var InFunctionParameters=response.inParameters; 
                var OutFunctionParameters=response.outParameters; 
                //Ajout des paramètres dans la fenêtre tinymce
                initTinyMceFunctionNotice(InFunctionParameters,OutFunctionParameters);
                //On montre la fenêtre modale
                $('#functionNoticeModal').modal('show');
                //Make modal draggable
                $("#functionNoticeModal").draggable({
                    handle: ".modal-content"
                });
            },
            error: function(response) {
                if (response.status == '401') //Si utilisateur déconnecté
                    window.location.href = window.location.pathname;

            }
        }).done(function() { //Traitement de fin d'execution  
            if ($('.modal-backdrop').length > 1)
                $('.modal-backdrop').first().remove();
        });
    }
    /* Sauvegarde d'un notice (oracle)  d'une fonction */

    function saveFunctionNotice(e) {
        e.preventDefault();
        var elt = $(this);
        var result;
        $('.tinyMceContent').tinymce().save(); //Récupération du contenu tinyMce pour remplir le textarea
        $('.tinyMceNoticeExpect').tinymce().save(); //Récupération du contenu tinyMce pour remplir le champ expect
        $('.tinyMceNoticeResult').tinymce().save(); //Récupération du contenu tinyMce pour remplir le champ result
        $.ajax({
            type: 'POST',
            url: $('#functionNoticeForm').attr('action'),
            data: $('#functionNoticeForm').serialize(),
            dataType: 'json',
            async: true,
            beforeSend: function() { // traitements JS à faire AVANT l'envoi 
                elt.hide();
                $("#noticeLoader").addClass("fa fa-spinner fa-spin fa-2x").show();
            },
            success: function(response) {
                if (!response.success) {//Si erreur au niveau du formulaire
                    $('#functionNoticeForm').replaceWith(response.html);//On recharge le formulaire entier
                }
                else { //La sauvegarde du formulaire a bien été éffectuée 
                    if (!response.updateMode) {//On est en mode création 
                        //On change l'url du formulaire pour la passer en mode édition
                        $('#functionNoticeForm').attr('action', response.url_form);
                        //On recharge le menu de la fenetre modale 
                        $('#functionNoticeModal').find('.modal-footer').replaceWith(response.modal_footer);
                    }
                    result = response;
                } 
            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {
            elt.show();
            $("#noticeLoader").hide();
            //Message d'alert
            $('#functionNoticeModalAlert').addClass(result.alert_class);
            $('#functionNoticeModalAlert').text(result.alert_message);
        });
    }

    /* Restauration de la notice par défaut d'une fonction.
     * Lors de la restauration d'une notice, on supprime la surcharge définit par le client (sur compose) ,
     *  et on récupère la notice par défaut correspondante sur la plate-forme centrale
     */

    function restoreDefaultFunctionNotice(e) {
        e.preventDefault();
        var elt = $(this);
        var result;
        $.ajax({
            type: 'POST',
            url: elt.attr('href'),
            dataType: 'json',
            async: false,
            beforeSend: function() {   elt.hide();  },
            success: function(response) {
                if (response.success) {
                    $('#functionNoticeModal').replaceWith(response.html);//On recharge la notice par défaut 
                    $('#functionNoticeModal').modal('show');
                    //Make modal draggable
                    $("#functionNoticeModal").draggable({
                        handle: ".modal-content"
                    });
                }
                result = response;

            },
            error: function(response) { 
                if (response.status == '401')
                    window.location.href = window.location.pathname;
                alert('Error ! Problem when processing');
            }
        }).done(function() {
            //Message d'alert
            $('#functionNoticeModalAlert').addClass(result.alert_class);
            $('#functionNoticeModalAlert').text(result.alert_message);
        });
    }


});

function loadTinyMceEvent() {
//Récupération du contenu tinyMce pour remplir le textarea
    if ($('.tinyMceContent').length > 0)
        $('.tinyMceContent').tinymce().save();
}


/*  Fonction d'initialisation du tinyMCE pour la prise en compte
 *  des paramètres d'entrées et sorties de la fonction  
 */
function initTinyMceFunctionNotice(InFunctionParameters,OutFunctionParameters){
    var params='';
    //Construction des item du menu des paramètres à associer au plugin
    if(InFunctionParameters.length > 0){
        $.each(InFunctionParameters, function(key, val) { 
                params=params + val+ ',' ; 
            });
    }
    if(OutFunctionParameters.length > 0){
        $.each(OutFunctionParameters, function(key, val) { 
                params=params + val+ ',' ; 
            });
    }
   //         alert(params);
   //Construction du plugin de gestion des paramètres de fonction
    tinymce.PluginManager.add('example', function(editor, url) { 
        //alert('good');
        //Paramètres d'entrée de la fonction
         if(InFunctionParameters.length > 0){
             $.each(InFunctionParameters, function(key, val) {
               //alert( key + '/' + val);
               editor.addMenuItem(val, {
                title: val,
                text: 'In : ' +val,
                context: 'parameters',
                onclick: function(e) { 
                   editor.insertContent('#{'+val+'}');
                }
               });
            });
         } 
         //Paramètres de sortie  de la fonction
         if(OutFunctionParameters.length > 0){
             $.each(OutFunctionParameters, function(key, val) { 
               editor.addMenuItem(val, {
                title: val,
                text: 'Out : ' + val,
                context: 'parameters',
                onclick: function(e) { 
                   editor.insertContent('${'+val+'}');
                }
               });
            });
         }       
}); 

    tinymce.init({
                selector: "textarea.tinyMceContent , textarea.tinyMceNoticeExpect ,textarea.tinyMceNoticeResult",
                paste_data_images: true ,
                plugins: [
                    " example advlist autolink lists link image charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table  paste "
                ],   
                menu : {
                    parameters: {
                         title: 'Parameters',
                         items:  params
                     },
                    edit: { title: 'Edit', items: 'undo redo  | cut copy paste selectall | searchreplace' },
                    insert: { title: 'Insert', items: 'link charmap' },
                    format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript | removeformat' },
                    table: { title: 'Table', items: 'inserttable tableprops deletetable | cell row column' }
                  },
                //menubar: "file edit format table view insert tools parameters",  
                theme_advanced_buttons1 : "mapalette",
                entity_encoding: "raw",
                toolbar: " undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                
            });
     
}
  