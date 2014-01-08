<?php

//Yii::app()->clientScript->registerLinkTag('alternate', 'application/json', '/bliki.json');

?>

<h1>Comentarios</h1>

<?php

echo '
    <dl class="comentarios">';
$status_class = array(
    'class="pending"',
    '',
    'class="disabled"',
);
foreach($comments as $comment) {
    $avatar = $comment->getAvatar();
    $autor = CHtml::image($avatar, '[Avatar]', array('width' => 55)).' '.$comment->author;
    $classname = $status_class[$comment->status];
    echo '
        <dt '.$classname.' id="comentario-'.$comment->_id.'">
            <span class="author">'.($comment->author_website ? CHtml::link($autor, $comment->author_website, array('title' => $comment->author_website, 'rel' => 'external')) : $autor).'</span> 
            <a href="/bliki/'.$comment->post_id.'#comentario-'.$comment->_id.'"><span class="date" title="'.date(DATE_ATOM, $comment->created_at).'">'.$this->formato_fecha($comment->created_at).'</span></a>
            <span class="post">en <a href="/bliki/'.$comment->post_id.'">'.$comment->post_id.'</a></span>
        </dt>
        <dd>
            '.$comment->html.'
        </dd>';
}
echo '
    </dl>';

    
    
    /*

post_id, doc.remote_addr, doc.status, doc.created_at, doc.author, doc.author_avatar, doc.author_website, doc.message, doc.html, doc.auth

_id] => 6359c459501abff1285d3e7905001ee4
_rev] => 3-fa41cf99f9aa6b7175fcc95bdc4f48c0



?>

<div class="view">
    <h2><?php echo CHtml::link(CHtml::encode($data->title), array($data->path)); ?></h2>
</div>
    <?php
    echo CHtml::encode($data->summary);

        <b><?php echo CHtml::encode($data->getAttributeLabel('fecha_creado')); ?>:</b> <?php echo $this->formato_fecha($data->fecha_creado); ?> (<?php echo gettype($data->fecha_creado).' '.$data->fecha_creado; ?>)
        <b><?php echo CHtml::encode($data->getAttributeLabel('fecha_modificado')); ?>:</b> <?php echo $this->formato_fecha($data->fecha_modificado); ?> (<?php echo gettype($data->fecha_modificado).' '.$data->fecha_modificado; ?>)</pre>

 */
?>
    