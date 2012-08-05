jQuery(document).ready(function($){
    var loader;
    var base_of_user_identifier;
    var range_of_user_spec;

    var loading = function(){
        if(loader){
            loader.dialog('open');
        }else{
            loader = $('#openid_loader').dialog({
                closeOnEscape: false,
                draggable: false,
                modal: true,
                resizable: false
            });
        }
        $('.ui-dialog-titlebar').hide();
    };

    var openid_submit = function(){
        if(!$('#openid_dialog_input').val()){return;}
        var id = base_of_user_identifier.substr(0,range_of_user_spec[0]).concat(
                $('#openid_dialog_input').val(),
                base_of_user_identifier.substr((range_of_user_spec[0]-0)+(range_of_user_spec[1]-0)));
        $('#openid_dialog_identifier').val(id);
        $('#openid_dialog_form').submit();
        $('#openid_dialog').dialog('close');
        loading();
    };

    var show_dialog = function(element){
        var clicked_image = $(element);
        base_of_user_identifier = clicked_image.attr('base_of_user_identifier');
        range_of_user_spec = clicked_image.attr('range').split(',');
        $('#openid_dialog_input').val('');
        $('#openid_dialog_label').text($('#openid_input_id_message').text().replace('%s',clicked_image.attr('title')));

        dialog = $('#openid_dialog').dialog({
            buttons: {'Login': openid_submit}
        });
        $('#openid_dialog_input').keypress(function(e){
            if(e.keyCode == $.ui.keyCode.ENTER || e.keyCode == $.ui.keyCode.NUMPAD_ENTER){
                openid_submit();
            }
        });
    };

    $('form.openid_login_form').submit(function(){
        loading();
    });

    $('img.openid_show_dialog').show().click(function(e){
        show_dialog(e.target);
        e.preventDefault();
    });
});