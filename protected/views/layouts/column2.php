<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div id="main" class="column2">
    <div id="sidebar-right">
        <ul>
            <li><a href="/multilevelpiechart">Multi-level pie chart</a></li>
        </ul>
        
        
        
        <h3>Últimas entradas del <a href="/blogroll">Blogroll</a></h3>
        <?php

        $criteria = array(
//            'condition' => implode(' AND ', $condition),
//            'params' => $params,
            'order' => 'pubDate DESC',
            'limit' => 10,
        );
        $blogroll_items = FeedItem::model()->with('feed')->findAll($criteria);
        echo "
            <ul>";
        foreach($blogroll_items AS $item) {
            echo "
                <li style=\"list-style-image: url('https://plus.google.com/_/favicon?domain=".urlencode($item->feed->link)."');\"><a href=\"{$item->link}\">{$item->title}</a></li>";
//    [id] => 26
//    [feed_id] => 2
//    [title] => Video presentación Desarrollando América Latina
//    [link] => http://feedproxy.google.com/~r/picandocodigo/~3/tPPip0b611U/
//    [description] => Video presentación de Desarrollando América Latina 2012, les recomiendo verlo para que se hagan una idea de qué se trata la hackatón, y les inspire participar en la edición 2013 Se muestran organizadores, segmentos de la hackatón y ganadores de todo
//    [pubDate] => 1365429848
//    [guid] => http://picandocodigo.net/?p=7553
//    [guid_isPermaLink] => 0
//    [content_encoded] => 
        }
        echo "
            <ul>";
        
        
        
        
//            $this->beginWidget('zii.widgets.CPortlet', array(
//                'title'=>'Operations',
//            ));
//            $this->widget('zii.widgets.CMenu', array(
//                'items'=>$this->menu,
//                'htmlOptions'=>array('class'=>'operations'),
//            ));
//            $this->endWidget();
        ?>
    </div>
    <div id="content">
        <?php echo $content; ?>
    </div>
</div>
<?php $this->endContent(); ?>