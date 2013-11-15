
    <div class="items">
        <h3>Posts</h3>
        <ul>
<?php

if($this->beginCache("blogroll:{$id}", array('duration' => 3600))) {
    $hoy = localtime(time(), true);
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
        $pubDate = localtime($item->pubDate, true);
        $fecha = ($pubDate['tm_yday'] == $hoy['tm_yday'] && $pubDate['tm_year'] == $hoy['tm_year']) ?
            "{$pubDate['tm_hour']}:{$pubDate['tm_min']} hs." : 
            "{$pubDate['tm_mday']}-{$meses[$pubDate['tm_mon']]}-".($pubDate['tm_year'] + 1900);
        echo "
            <li><span title=\"{$item->feed->title}\" class=\"autor\"><img src=\"https://plus.google.com/_/favicon?domain={$fuentes[$item->feed_id]}\" alt=\"[Favicon]\" /> {$item->feed->title}</span> <a href=\"{$item->link}\" rel=\"external\">{$item->title}</a> <span class=\"fecha\" >{$fecha}</span></li>";
//            "<!-- ".strip_tags($item->description)." -->".
    }
    $this->endCache();
    echo "
<!-- caché generado -->
";
}
else {
    echo "
<!-- leído del caché -->
";
}

?>
        </ul>
    </div>