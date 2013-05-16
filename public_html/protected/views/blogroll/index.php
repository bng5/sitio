<?php

setlocale(LC_ALL, 'es_UY.UTF-8');

//Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/jquery-1.9.1.js');
//Yii::app()->clientScript->registerScript('rssAjax', "
//
//function actualizarRSS() {
//    $.ajax({
//        type: 'GET',
//        url: '/blogroll.rss',
//        dataType: 'xml'
//    }).done(function(data, textStatus, jqXHR) {
//        console.log(data, textStatus, jqXHR);
//        console.log(data.getElementsByTagName('item'));
//    }).fail(function(jqXHR, textStatus, errorThrown) {
//    
//    });
//}
//
//", CClientScript::POS_HEAD);


?>

<h1>Blogroll</h1>

<div id="blogroll">
    
<?php

if($mensaje) {
    echo '
        <div>
            <p>'.$mensaje.'</p>
        </div>';
}

?>

    <div class="fuentes">
        <h3>Fuentes</h3>
        <ul>
    <?php
    
    $fuentes = array();
    foreach($feeds AS $f) {
        if(!array_key_exists($f->id, $fuentes)) {
            $fuentes[$f->id] = urlencode(parse_url($f->link, PHP_URL_HOST));
        } 
        // href="/blogroll/fuentes/'.$f->id.'"
        $selected = ($feed && $feed->id == $f->id) ? 
            ' class="activo"' :
            '';
        printf('
            <li style="list-style-image: url(\'https://plus.google.com/_/favicon?domain=%2$s\');"%3$s><a href="/blogroll/%2$s">%1$s</a></li>', $f->title, $fuentes[$f->id], $selected);
//        echo '
//            <li style="list-style-image: url(\'https://plus.google.com/_/favicon?domain='.$fuentes[$f->id].'\');"><a href="/blogroll/'.$fuentes[$f->id].'">'.$f->title.'</a></li>';
    }
    
    ?>
        </ul>
    </div>

    
<?php

$this->renderPartial('_items', array(
    'fuentes' => $fuentes,
    'posts' => $posts,
    'id' => ($feed ? $feed->id : 'all'),
));

?>

</div>

<?php 
/*
<!--<div>
    <button onclick="actualizarRSS()">Actualizar</button>
</div>-->
*/
?>

<div style="clear: both;">
    <h3>Otros formatos</h3>
    <ul>
        <li><a href="/blogroll.opml"><img src="/img/icons/opml" alt="" /> opml</a></li>
        <li><a href="/blogroll.rss"><img src="/img/icons/feed" alt="" /> rss</a></li>
    </ul>
</div>
