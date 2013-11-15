<?php

Yii::app()->clientScript->registerScriptFile('https://login.persona.org/include.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('persona', "

var signinLink = document.getElementById('signin');
if(signinLink) {
    signinLink.onclick = function() {
        navigator.id.request();
    };
}
 
var signoutLink = document.getElementById('signout');
if(signoutLink) {
    signoutLink.onclick = function() {
        navigator.id.logout();
    };
}



 
navigator.id.watch({
//    loggedInUser: currentUser,
    onlogin: function(assertion) {

$(signinLink).text('https://verifier.login.persona.org/verify?assertion='+assertion+'&audience=localhost.bng5.net:80');

    


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


", CClientScript::POS_READY);

?>


<div id="signin">signin</div>
<div id="signout">signout</div>


<address>https://developer.mozilla.org/en-US/docs/Persona/Quick_Setup</address>
