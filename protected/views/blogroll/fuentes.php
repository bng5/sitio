<?php

$this->breadcrumbs = array(
    'Blogroll' => array('/blogroll'),
    'Fuentes',
);

?>

<h1>Blogroll</h1>

<dl class="fuentes">
    <?php
    
    foreach($feeds AS $f) {
        echo '
    <dt><a href="'.$f->link.'" rel="external">'.$f->title.'</a></dt>
    <dd>'.$f->description.'</dd>';
        
    }
    
    ?>
</dl>

<div>
    <h3>Otros formatos</h3>
    <ul>
        <li><a href="/blogroll.opml"><img src="/img/icons/opml" alt="" /> opml</a></li>
        <li><a href="/blogroll.rss"><img src="/img/icons/feed" alt="" /> rss</a></li>
    </ul>
</div>
