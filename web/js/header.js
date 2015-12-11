$(document).ready(function(){
    $("#button_token_api_show").click(function(event){
        event.preventDefault();

        $.ajax({
            type: "GET",
            context: this,
            dataType: 'json',
            url: $("input[name=url_to_get_api_key]").val(),
            error:function(data)
            {
                console.log( data );

                enableAllApiTokenInput();
            },
            success:function(data)
            {
                console.log(data);

                enableAllApiTokenInput();

                $("#input_token_api").val(data.token).select();
            },
            beforeSend: function(){
                disableAllApiTokenInput();
            }
        });


        return false;
    });

    $("#button_token_api_generate").click(function(event){
        event.preventDefault();

        $.ajax({
            type: "GET",
            context: this,
            dataType: 'json',
            url: $("input[name=url_to_generate_api_key]").val(),
            error:function(data)
            {
                console.log( data );

                enableAllApiTokenInput();
            },
            success:function(data)
            {
                console.log(data);

                enableAllApiTokenInput();

                $("#input_token_api").val(data.token).select();
            },
            beforeSend: function(){
                disableAllApiTokenInput();
            }
        });


        return false;
    });
});

function disableAllApiTokenInput(){
    $("#input_token_api").attr("disabled", "disabled");
    $("#button_token_api_generate").attr("disabled", "disabled");
    $("#button_token_api_show").attr("disabled", "disabled");
}

function enableAllApiTokenInput(){
    $("#input_token_api").attr("disabled", false);
    $("#button_token_api_generate").attr("disabled", false);
    $("#button_token_api_show").attr("disabled", false);
}