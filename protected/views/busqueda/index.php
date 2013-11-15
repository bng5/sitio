<?php


//Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/jquery-1.8.3.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('custom_search',"
    
(function() {
    var form = $('#busqueda').submit(function(event) {
        customSearchControl.execute(this.elements.namedItem('q').value);
        event.preventDefault();
        return false;
    });

    if(query.q) {
        form.get(0).elements.namedItem('q').value = query.q;
        setTitle(query.q);
    }
})();

$('#gcs').html('Cargando&hellip;');

//$(function() {
//
//    var gcs = document.getElementById('gcs');
//    //var searchresults = document.createElementNS('uri:google-did-not-provide-a-real-ns', 'gcse:searchresults-only');
//    var searchresults = document.createElement('gcse:searchresults-only');
//    gcs.appendChild(searchresults);
//    searchresults.setAttribute('linktarget', '_parent');
//
//    var cx = '000505282032523587963:plblebphvbg';
//    var gcse = document.createElement('script'); gcse.type = 'text/javascript'; gcse.async = true;
//    gcse.src = (document.location.protocol == 'https:' ? 'https:' : 'http:') +
//        '//www.google.com/cse/cse.js?cx=' + cx;
//    var s = document.getElementsByTagName('script')[0];
//    s.parentNode.insertBefore(gcse, s);
//  });


", CClientScript::POS_READY);

$google_url = 'http://www.google.com/cse?'.http_build_query($google_query, null, '&amp;');

?>

<div id="gcs" style="width:100%;">
    <p>Ha ocurrido un error o su navegador no cuenta con JavaScript habilitado.</p>
    <p>Puede intentar repetir la búsqueda en la página de Google: <a href="<?php echo $google_url; ?>"><?php echo $google_url; ?></a>.</p>
</div>
<!-- gcse:searchresults-only linktarget="_parent"></gcse:searchresults-only -->

<!-- gcse:searchresults></gcse:searchresults -->

<?php
//<gcse:searchbox-only></gcse:searchbox-only>
?>
