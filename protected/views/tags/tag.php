<?php

Yii::app()->clientScript->registerScript('posts', "
    
$.ajax({
    url: '/feed/rss',
    dataType: 'xml'
}).done(function(data, textStatus, jqXHR) {
    var items = data.getElementsByTagName('item');
    var title;
    var cont = $('#posts').append('<ul></ul>');
    for(var i = 0; i < items.length; i++) {
        title = items.item(i).getElementsByTagName('title')[0].firstChild.textContent;
        cont.append('<li><a href=\"#\">'+title+'</a></li>');
    }
});

", CClientScript::POS_READY);

?>



<h1><?php echo $tag->titulo; ?></h1>
<p><?php echo $tag->descripcion; ?></p>

<div id="posts">
    <h2>Posts</h2>
    Cargando...
</div>
