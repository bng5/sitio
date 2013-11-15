<?php

$this->breadcrumbs = array(
    'Blogroll' => array('/blogroll'),
    'Fuentes' => array('/blogroll/fuentes'),
    $feed->title,
);

?>

<h1><?php echo $feed->title; ?></h1>

<p><?php echo $feed->description; ?></p>

<dl>
    <dt>URL:</dt>
    <dd><?php echo CHtml::link($feed->link, $feed->link, array('rel' => 'external')); ?></dd>
    <dt><?php echo ($feed->type == Feed::TYPE_RSS) ? 'RSS' : 'Atom'; ?>:</dt>
    <dd><?php echo CHtml::link($feed->url, $feed->url, array('rel' => 'external')); ?></dd>
</dl>

<div id="blogroll">
    
<?php

//      'estado' => string '1' (length=1)
//      'lastBuildDate' => string '1367249297' (length=10)
//      'lastRequest' => string '1367616789' (length=10)
//      'charset' => null
//      'HttpLastModified' => string '1367615342' (length=10)
//      'HttpETag' => string '7E7uYJ3amtXSvu/t6OtJYC+t7z0' (length=27)

$this->renderPartial('_items', array(
    'fuentes' => array($feed->id => urlencode(parse_url($feed->link, PHP_URL_HOST))),
    'posts' => $feed->items,
    'id' => $feed->id,
));

?>
</div>
