
<div id="articulo">
    
<h1><?php
echo $titulo;
if(!Yii::app()->user->isGuest) {
    echo ' '.CHtml::link(CHtml::image('/img/silk/page_edit.png', 'Editar'), array("bliki/{$path}/editar"));
}
?></h1>

<?php

echo $html;

//    $model->id,
//    $model->path,
//    $model->fecha_creado,
//    $model->fecha_modificado

$cache_id_post = 'post:'.$post->_id;
if($cache && $texto = Yii::app()->cache->get($cache_id_post)) {
    echo $texto;
    echo '<p><em>Obtenido del caché</em></p>';
}
//else {
    ob_start();

echo '<pre>';
//var_dump(
////    'toc_habilitado', $post->toc_habilitado,
////    'comentarios_habilitados', $post->comentarios_habilitados,
//    ''
//);
echo '</pre>';
//    echo $post->texto;

    if(count($post->tags)) {
        echo '<div id="post_tags">Tags: ';
        foreach($post->tags AS $tag) {
            echo CHtml::link($tag, array("tags/{$tag}")).' ';
        }
        echo '</div>';
    }

    $buffer = ob_get_flush();
    if($cache) {
        Yii::app()->cache->set($cache_id_post, $buffer);
    }
//}
//if($this->beginCache($cache_id_post)) {
//    $this->endCache(); 
//}

?>


<h2 id="comentarios">Comentarios <a href="#comentarios" class="permalink">&#182;</a></h2>

<?php

if($cache && $comentarios = Yii::app()->cache->get('come:'.$post->id)) {
    echo $comentarios;
}
else {

//    $comentarios = array();
//    foreach($comments AS $comment) {
//        $key = $comment->reply_to ? 
//            $comment->reply_to : 
//            0;
//        $comentarios[$key][] = $comment;
//    }

    function mostrarComentarios($comentarios, $parent, $controller, $level = 0) {
        if(!count($comentarios))
            return;
        if($level == 0) {
            $class = ' class="comentarios"';
            $img_size = '55';
        }
        else {
            $class = '';
            $img_size = '40';
        }
        echo '
        <dl'.$class.'>';
        $class_primero = ' class="primero"';
        foreach($comentarios[$parent] AS $comment) {

            $avatar = $comment->author->getAvatar();
    //        $autor = CHtml::image('http://www.gravatar.com/avatar/'.$comment->author->email_hash.'?s='.$img_size.'&d=mm', 'Avatar').' '.$comment->nombre;
            $autor = CHtml::image($avatar, '[Avatar]', array('width' => $img_size)).' '.$comment->author->nombre;
            echo '
            <dt'.$class_primero.' id="comentario-'.$comment->id.'">
                <span class="author">'.($comment->author->link ? CHtml::link($autor, $comment->author->link, array('title' => $comment->website, 'rel' => 'external')) : $autor).'</span> 
                <a href="#comentario-'.$comment->id.'"><span class="date" title="'.date(DATE_ATOM, $comment->fecha_creado).'">'.$controller->formato_fecha($comment->fecha_creado).'</span></a> 
                <a class="comment-reply-link" href="#responder" onclick="return addComment.moveForm(\'-98590\', \'98590\', \'respond\', \'2545\')">Responder</a>
            </dt>
            <dd>
                '.$comment->html;
            if(array_key_exists($comment->id, $comentarios)) {
                mostrarComentarios($comentarios, $comment->id, $controller, ++$level);
                $level--;
            }
            echo '
            </dd>';
            $class_primero = '';
        }
        echo '
        </dl>';
    }


    ob_start();
//    mostrarComentarios($comentarios, 0, $this);
    echo '<p>Generado: '.date(DATE_ATOM).'</p>';
    $buffer = ob_get_flush();
    if($cache) {
        Yii::app()->cache->set('come:'.$post->id, $buffer);
    }
}

//if($post->comentarios_habilitados) {
////    $this->renderPartial('comentar', array(
////        'post' => $post,
////        'comentario' => $new_comment,
////    ));
//}

?>

</div>

<div id="lateral">
    <div>
        <ul>
            <li><a href="/bliki/<?php echo $path; ?>/source">Fuente del artículo</a></li>
            <li><a href="/bliki/<?php echo $path; ?>/historial">Historial de cambios</a></li>
        </ul>
    </div>

<?php

//$ts = Tag::model()->with('posts_count')->findAll();
//$tags = array();
//foreach($ts AS $t) {
//    $tags[$t->tag." ({$t->posts_count})"] = (int) $t->posts_count;
////    var_dump($t->titulo, $t->posts_count);
//}

//Yii::import('ext.TagCloud');
//$this->widget('TagCloud', array('tags' => $tags));

?>

</div>
