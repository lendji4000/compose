$(document).ready(function() {
    bindBlockEvents();
    changeBlockScopeEvent();
    addBlockEvent($(".add_block:first"));
    bindTreeEvents(true);
});

function bindTreeEvents(hide_close_block) {
    $('.close_block').unbind('click');
    $('.open_block').unbind('click');

    if(hide_close_block === true){
        $('.close_block').hide();
        $("div.current_block > .open_block").hide();
        $("div.current_block > .close_block").show();
    }

    $('.close_block').bind('click', function(e) {
        closeBlock($(this));
    });

    $('.open_block').bind('click', function(e) {
        openBlock($(this));
    });

    $('.close_block_param').unbind('click');
    $('.open_block_param').unbind('click');

    $('.close_block_param').hide();

    $('.close_block_param').bind('click', function(e) {
        closeBlock($(this));
    })

    $('.open_block_param').bind('click', function(e) {
        openBlock($(this));
    });

}

function openBlock(link) {
    link.hide();
    link.next().show();
    link.parent().next().show();
    link.parent().next().toggleClass('hidden');
    link.parent().next().toggleClass('opened');
}

function closeBlock(link) {
    link.hide();
    link.prev().show();
    link.parent().next().hide();
    link.parent().next().toggleClass('hidden');
    link.parent().next().toggleClass('opened');
}

/**
 * Lie les event associé à la vue d'édition d'un block
 * @returns {undefined}
 */
function bindBlockEvents() {
    addBlockEvent($(".add_block:not(:first)"));
    deleteBlockEvent();
    bindBlockParamEvents();
    updateBlockEvent();
    bindSortEvent();
}

function bindSortEvent() { 
    $("#children_blocks").sortable({
        update: function(event, ui) {

            var insert_after = ui.item.prev('.sortable').attr('ei_block');

            if (insert_after === undefined)
                var url = ui.item.attr('data-href');
            else
                var url = ui.item.attr('data-href') + "?insert_after=" + insert_after;

            $.ajax({
                type: 'GET',
                url: url,
                dataType: 'json',
                async: false,
                success: function(res) {
                    if(res.status === "error"){
                        $( "#children_blocks" ).sortable( "cancel" );
                    }else{
                        //déplacement dans l'arbre de menu.
                        var ei_block_id = ui.item.attr('ei_block');

                        if (insert_after === undefined){
                            $("li[ei_block=" + ei_block_id + "]").parent().prepend( $("li[ei_block=" + ei_block_id + "]"));
                        }
                        else{
                            $("li[ei_block=" + insert_after + "]").after( $("li[ei_block=" + ei_block_id + "]"));
                        }
                    }

                    setToolTip(res.message, res.status === "error");
                },
                error: function(res) {
                    $( "#children_blocks" ).sortable( "cancel" );
                    setToolTip("An error occured. The block could not be retrieved.", true);
                }
            });
        }
    });
}

function toggleBlock(new_b){
    var icon = $('.current_block').find('i').last().first();

    new_b.parents("ul.hidden").removeClass("hidden").show();
    new_b.parent().parent().find("ul.hidden").first().removeClass("hidden").show();

    $('.current_block').toggleClass('current_block');
    icon.remove();
    new_b.parent().toggleClass('current_block');
    new_b.append(icon);

    console.log(new_b, new_b.first());
    new_b.parent().next("ul").first().addClass('opened');

    $(".current_block").parents("#scenario_structure ul").show();
}

/**
 * Au clique sur un block de l'arbre de structure,
 * renvoie le formulaire d'édition d'un block, l'inclu dans le tab
 * "structure" et l'affiche.
 *
 * @returns {undefined}
 */
function changeBlockScopeEvent() {
    $('.go_to_block_eiscenario').unbind('click');
    $('.go_to_block_eiversion').unbind('click');

    //changement de block au sein de l'édition d'un scenario
    $('.go_to_block_eiscenario').bind('click', function(e) {
        e.preventDefault();
        var link = $(this);

        if(link.attr('ei_block') != undefined)
            block_id = link.attr('ei_block');
        else
            block_id = link.parent().parent().attr("ei_block");

        $.ajax({
            type: 'GET',
            url: link.attr('href'),
            dataType: 'json',
            async: false,
            success: function(res) {
                $('#block').html(res.content);
                bindBlockEvents();
                $('a[href="#block"]').tab('show');
                $('#path_block').html(res.path);
                addBlockEvent($(".add_block:first"));
                changeBlockScopeEvent();
                toggleBlock($('li[ei_block='+block_id+']').find('.go_to_block_eiversion').first());
            },
            error: function(res) {
                setToolTip("An error occured. The block could not be retrieved.", true);
            }
        });
    });


    //changement de block au sein de l'édition d'une version.
    $('.go_to_block_eiversion').bind('click', function(e) {
        e.preventDefault();
        loadEiAjaxActions($(this),$('.eiLoading'),"json",true,{},go_to_block_eiversion); 
    });
}
function go_to_block_eiversion(response, elt) {
    if (elt.attr('ei_block') != undefined)
        block_id = elt.attr('ei_block');
    else
        block_id = elt.parent().parent().attr("ei_block");
    $("#scrolled_box_version").html(response.content);
    $('#path_block').html(response.path);

    if (typeof response.propertiesAndParams != "undefined") {  
        $("#blockPropertiesParameters").html(response.propertiesAndParams);
    } 
    bindFunctionEvents();
    changeBlockScopeEvent();
    //réinit des event de suppression.
    deleteBlockEvent();
    toggleBlock($('li[ei_block=' + block_id + ']').find('.go_to_block_eiversion').first());
}

/**
 * Soumet le formulaire d'édition d'un block, contenant le nom et sa description
 * @returns {undefined}
 */
function updateBlockEvent() {
    $('.update_block').delegate('.submit_block', 'click', function(e) {
        e.preventDefault();
        $.ajax({
            url: $('.update_block').attr('action'),
            type: "POST",
            async: true,
            data: $('.update_block').serialize(),
            dataType: 'json',
            success: function(data) {
                setToolTip(data.message, data.status === "error");
                if(data.status !== 'error')
                    $(".current_block").find("a").text($('.update_block').find("#ei_block_name").val());
            },
            error: function(e) {
                setToolTip("Unable to process the save: an internal error occured.", true);
            }
        });
    });
}

/**
 * Récupère le formulaire de création d'un nouveau block dans le block courant
 * et/ou après un block particulier.
 * @param {string} selector
 * @returns {undefined}
 */
function addBlockEvent(selector) {
    selector.bind('click', function(e) {
        var link = $(this);
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: link.attr('href'),
            dataType: 'json',
            async: false,
            success: function(res) {
                link.parent().parent().after(res);
                deleteNewBlockEvent();
                submitAddedBlockEvent(link.parent().parent().next());
            },
            error: function(res) {
                setToolTip("An error occured. Block creation form could not be retrieved.", true);
            }
        });
    });
}

/**
 * Envoie le formulaire d'ajout d'un block et affiche ce block dans le DOM
 * dans sa forme finale.
 * @param {string} selector
 * @returns {undefined}
 */
function submitAddedBlockEvent(selector) {
    selector.find(".submit_block").bind('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('submit_block');
        var form = $(this).parent().parent().parent();
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            async: false,
            data: form.serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.status === "error") {
                    setToolTip(data.message, true);
                    $(this).toggleClass('submit_block');
                }
                else {
                    setToolTip(data.message, false);
                    form.after(data.content); //ajout du contenu
                    //binding du formulaire
                    addBlockEvent(form.next().find(".add_block"));
                    //suppression du formulaire
                    form.remove();
                    //réinit des event de suppression.
                    deleteBlockEvent();

                    //MAJ de l'arbre
                    //insertion en milieu/fin
                    if (data.insert_after != 0) {
                        $("li[ei_block=" + data.insert_after + "]").after(data.item_tree);
                        $("li[ei_block=" + data.insert_after + "]").next().find('.close_block').first().hide();
                    }
                    //insertion au debut
                    else {
                        var block = $("li[ei_block=" + data.insert_first + "]");
                        if (block.children().last().attr('class') === "opened") {
                            block.children().last().prepend(data.item_tree);
                        }
                        else {
                            block.append('<ul class="opened" ></ul>');
                            block.children().last().prepend(data.item_tree);
                        }
                        bindTreeEvents(false);
                        block.children().last().find('.close_block').first().hide();

                        //block.children('.padding-left').first().removeClass('padding-left');
                        block.children().first().children('.open_block').first().removeClass('hidden');
                        block.children().first().children('.open_block').first().hide();
                        block.children().first().children('.close_block').first().show();
                    }

                    changeBlockScopeEvent();
                }
            },
            error: function(e) {
                $(this).toggleClass('submit_block');
                setToolTip("Unable to process the save: an internal error occured.", true);
            }
        });
    });
}

/**
 * Supprime un block du block courant en base de données et , sur succès,
 * le retire du DOM.
 *
 * @returns {undefined}
 */
function deleteBlockEvent() {
    $(".delete_block").unbind('click');
    $(".delete_block").bind('click', function(e) {
        e.preventDefault();
        $(this).toggleClass('delete_block');
        var link = $(this);
        var modale=link.parentsUntil('.modal').parent();
        var parent = modale.parent();
        $.ajax({
            type: 'GET',
            url: link.attr('data-href'),
            async: false,
            dataType: 'json',
            success: function(res) {
                if (res.status == "ok") {
                    setToolTip(res.message);
                    $("#scenario_structure").find('li[ei_block=' + link.attr('ei_block') + ']').remove();
                    parent.next().remove();
                    parent.remove();

                    if( getCurseur().size() == 0 ){
                        setCurseurAtFirst();
                    }

                    modale.modal('hide');
                    $('.modal-backdrop').attr('class','fade out');
                    modale.remove();

                    if( $("body").hasClass("modal-open") ){
                        $("body").removeClass("modal-open");
                    }
                } else {
                    $(this).toggleClass('delete_block');
                }
            },
            error: function(res) {
                modale.modal('hide');
                $(this).toggleClass('delete_block');
                setToolTip("An error occured. Block could not be deleted due to internal server error.", true);
            }
        });
    })
}


/**
 * 
 * @returns {undefined}
 */
function deleteNewBlockEvent() {
    $(".delete_block_new").bind('click', function(e) {
        e.preventDefault();
        $(this).parent().parent().parent().remove();
    });
}

function blocAjaxProcessErr(eltId,additionalDatas){
  setToolTip("An error occured. The block could not be retrieved.", true);
}