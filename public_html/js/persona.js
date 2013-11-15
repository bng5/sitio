
//$(function() {
//
//
////    var signoutLink = document.getElementById('signout');
////    if(signoutLink) {
////        signoutLink.onclick = function() {
////            navigator.id.logout();
////        };
////    }
//});

navigator.id.watch({
    loggedInUser: '',
    onlogin: function(assertion) {
        var auth_persona_assertion = $('#auth_persona_assertion').val(assertion);
        if($('#auth_type').val() == 'persona') {
            var form = auth_persona_assertion[0].form;
            form.submit();
        }
        // A user has logged in! Here you need to:
        // 1. Send the assertion to your backend for verification and to create a session.
        // 2. Update your UI.
//        $.ajax({ /* <-- This example uses jQuery, but you can use whatever you'd like */
//            type: 'POST',
//            url: '/auth/login', // This is a URL on your website.
//            data: {assertion: assertion},
//            success: function(res, status, xhr) {
//                window.location.reload();
//            },
//            error: function(xhr, status, err) {
//                alert('Login failure: ' + err);
//            }
//        });
    },
    onlogout: function() {
        // A user has logged out! Here you need to:
        // Tear down the user's session by redirecting the user or making a call to your backend.
        // Also, make sure loggedInUser will get set to null on the next page load.
        // (That's a literal JavaScript null. Not false, 0, or undefined. null.)
//        $.ajax({
//            type: 'POST',
//            url: '/auth/logout', // This is a URL on your website.
//            success: function(res, status, xhr) { window.location.reload(); },
//            error: function(xhr, status, err) { alert('Logout failure: ' + err); }
//        });
    }
});

////////////////////////////////////////////////////////////////////////////////



//function enviarComentario() {
//
//var comentario = document.getElementById('comentario');
//var el;
//for(var k = 0; k < comentario.elements.length; k++) {
//    el = comentario.elements[k];
//    console.log(k, el, el.name, el.value);
//}
//    var formData = $('#comentario').serialize();
//console.log(formData);
//    $.ajax({
//        url: document.location.pathname.match(/^\/[a-z]+\/[^\/]+/)+'/comentarios',
//        dataType: 'json',
//        type: 'POST',
//        data: formData
//    }).done(function(a, b, c) {
//console.log(a, b, c);
//    });
//}

function aceptarDialogoOpenid(event) {
    event.preventDefault();
    var url = $.trim($('#dialog_openid_identifier').val());
    if(url != '') {
        $('#auth_type').val('openid');
        $('#auth_openid_identifier').val(url);
        document.getElementById('comentario').submit();
    }
    else {
        var dialog = $('#openId_dialog');
        if(dialog.find('div.error').length == 0) {
            $('#openId_dialog').prepend('<div class="error">Debe ingresar una URL de OpenID</div>');
        }
    } 
}

$(function() {
    
    
    
    var signinLink = document.getElementById('auth_type_persona');
    if(signinLink) {
        signinLink.childNodes[1].nodeValue = signinLink.childNodes[1].nodeValue.replace(/\s\([^\)]+\)/, '');
        signinLink.onclick = function(event) {
            $('#auth_type').val('persona');
            event.preventDefault();
            navigator.id.request();
            return false;
        };
    }
    
    
    var identifier = $('#auth_openid_identifier');
    identifier.parent().hide();
    
    var div = $('<div id="openId_dialog"></div>');
    var form = $('<form action="" method="get"></form>');
    form.on('submit', aceptarDialogoOpenid);
    div.append(form);
    form.append('<span><label for="dialog_openid_identifier">URL:</label> <input type="text" id="dialog_openid_identifier" name="openid_identifier" value="'+identifier.val()+'"></span>');

    div.dialog({
        title: 'OpenID',
        modal: true,
        autoOpen: false,
        width: 345,
        buttons: {
            Cancelar: function() {
                $(this).dialog('close');
            },
            Aceptar: aceptarDialogoOpenid
        },
        close: function() {
            //allFields.val( "" ).removeClass( "ui-state-error" );
        }
    });

    $('#auth_type_openid').click(function(event) {
        event.preventDefault();
        $('#openId_dialog').dialog('open');
        //var url = window.prompt('OpenID URL');
        //$('#auth_openid_identifier').val(url);
        //this.form.submit();
    });
});