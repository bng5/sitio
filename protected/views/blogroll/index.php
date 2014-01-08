<?php

setlocale(LC_ALL, 'es_UY.UTF-8');

?>

<div style="display: none;">
    <h3>Otros formatos</h3>
    <ul>
        <li><a href="/blogroll.opml"><img src="/img/icons/opml" alt="" /> opml</a></li>
        <li><a href="/blogroll.rss"><img src="/img/icons/feed" alt="" /> rss</a></li>
    </ul>
</div>

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
        if(!array_key_exists($f->_id, $fuentes)) {
            $fuentes[$f->_id] = array(
                'url' => urlencode(parse_url($f->link, PHP_URL_HOST)),
                'title' => $f->title,
            );
        } 
        $selected = ($feed && $feed->_id == $f->_id) ? 
            ' class="activo"' :
            '';
        printf('
            <li style="list-style-image: url(\'https://plus.google.com/_/favicon?domain=%2$s\');"%4$s><a href="/blogroll/%3$s">%1$s</a></li>', 
                $f->title,
                $fuentes[$f->_id]['url'],
                $f->_id,
                $selected
        );
    }

    ?>
        </ul>
    </div>

    <div class="items">
<?php

if($feed) {
    $feed_types = array(
        1 => 'RSS',
        2 => 'Atom',
    );
    echo "
        <h2>{$feed->title}</h2>
        <p>{$feed->description}</p>
        <dl>
            <dt>URL:</dt>
            <dd>".CHtml::link($feed->link, $feed->link, array('rel' => 'external'))."</dd>
            <dt>{$feed_types[$feed->feed_type]}:</dt>
            <dd>".CHtml::link($feed->url, $feed->url, array('rel' => 'external'))."</dd>
        </dl>";
}

?>

        <h3>Posts</h3>
        <ol>
<?php

$id = $feed ? 
    $feed->_id :
    'all';
//if($this->beginCache("blogroll1:{$id}", array('duration' => 600))) {
    $hoy = getdate();
    $meses = array(
        1 => 'ene',
        2 => 'feb',
        3 => 'mar',
        4 => 'abr',
        5 => 'may',
        6 => 'jun',
        7 => 'jul',
        8 => 'ago',
        9 => 'set',
        10 => 'oct',
        11 => 'nov',
        12 => 'dic',
    );

    foreach($posts AS $item) {
        if(!array_key_exists($item->feed_id, $fuentes)) {
            continue;
        }
        $pubDate = getdate($item->pubDate);
        $fecha = ($pubDate['yday'] == $hoy['yday'] && $pubDate['year'] == $hoy['year']) ?
            date('G:i', $item->pubDate).' hs.' : //"{$pubDate['hours']}:{$pubDate['minutes']} hs." : 
            "{$pubDate['mday']}-{$meses[$pubDate['mon']]}-{$pubDate['year']}";
        echo "
            <li><span title=\"{$fuentes[$item->feed_id]['url']}\" class=\"autor\"><img src=\"https://plus.google.com/_/favicon?domain={$fuentes[$item->feed_id]['url']}\" alt=\"[Favicon]\" /> {$fuentes[$item->feed_id]['title']}</span> <a href=\"{$item->link}\" rel=\"external\">{$item->title}</a> <span class=\"fecha\" >{$fecha}</span></li>";
    }
//    $this->endCache();
//}
//else {
//    echo "
//leído del caché
//";
//}

?>
        </ol>
    </div>
</div>
