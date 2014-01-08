
<div id="articulo">
    
<h1><?php
echo $titulo;
if(!Yii::app()->user->isGuest) {
    echo ' '.CHtml::link(CHtml::image('/img/silk/page_edit.png', 'Editar'), array("bliki/{$path}/editar"));
}
?></h1>

<?php

$this->path = "/bliki/{$path}";

$cache_id_post = 'post:'.$post->_id;
if(empty($cache) && $html = Yii::app()->cache->get($cache_id_post)) {
    // pass
}
else {
    $parser = new BlikiParser;
    $parser->parse($post->content);
    ob_start();
    echo $parser->getTocHtml();
    echo $this->_renderer($parser->getTokens());
    if(count($post->tags)) {
        echo '<div id="post_tags"><label>Tags:</label> ';
        foreach($post->tags AS $tag) {
            echo "<a href=\"/bliki?tag={$tag}\">{$tag}</a> ";
        }
        echo '</div>';
    }
    echo '<!-- Caché generado: '.date(DATE_ATOM).' -->';
    $html = ob_get_clean();
    if($cache != 'no') {
        Yii::app()->cache->set($cache_id_post, $html);
    }
}

echo $html;





?>
    
<h2 id="comentarios">Comentarios <a href="#comentarios" class="permalink">&#182;</a></h2>
        
<?php

//if($cache && $comentarios = Yii::app()->cache->get('come:'.$post->id)) {
//    echo $comentarios;
//}
//else {

$comentarios = array();
foreach($comments AS $comment) {
    $key = $comment->reply_to ? 
        $comment->reply_to : 
        0;
    $comentarios[$key][] = $comment;
}


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
        $avatar = $comment->getAvatar();
    //        $autor = CHtml::image('http://www.gravatar.com/avatar/'.$comment->author->email_hash.'?s='.$img_size.'&d=mm', 'Avatar').' '.$comment->nombre;
        $autor = CHtml::image($avatar, '[Avatar]', array('width' => $img_size)).' '.$comment->author;
        echo '
            <dt'.$class_primero.' id="comentario-'.$comment->_id.'">
                <span class="author">'.($comment->author_website ? CHtml::link($autor, $comment->author_website, array('title' => $comment->author_website, 'rel' => 'external')) : $autor).'</span> 
                <a href="'.$controller->path.'#comentario-'.$comment->_id.'"><span class="date" title="'.date(DATE_ATOM, $comment->created_at).'">'.$controller->formato_fecha($comment->created_at).'</span></a> 
                <a href="/comentarios/'.$comment->_id.'/respuesta" onclick="return reply(this, \''.$comment->_id.'\')" class="reply" title="Responder"><span>Responder</span></a>
';
//                <a class="comment-reply-link" href="#responder" onclick="return addComment.moveForm(\'-98590\', \'98590\', \'respond\', \'2545\')">Responder</a>
echo '
            </dt>
            <dd>
                '.$comment->html;
        if(array_key_exists($comment->_id, $comentarios)) {
            mostrarComentarios($comentarios, $comment->_id, $controller, ++$level);
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
mostrarComentarios($comentarios, 0, $this);
$buffer = ob_get_flush();
if($cache) {
    Yii::app()->cache->set('come:'.$post->_id, $buffer);
}


if($post->nocomments) {
    echo "Comentarios cerrados";
}
else {
    $this->renderPartial('comentar', array(
        'post_id' => $post->_id,
        'comentario' => $new_comment,
    ));
}

?>

</div>

<div id="lateral">
    <div>
<!--        <ul>
            <li><a href="/bliki/<?php echo $path; ?>/source">Fuente del artículo</a></li>
            <li><a href="/bliki/<?php echo $path; ?>/historial">Historial de cambios</a></li>
        </ul>-->
    </div>

<?php

try {
    $ts = Tag::model()->group('list/_view/tags');
    //var_dump($ts);
    //return;
    $tags = array();
    foreach($ts AS $t) {
        $tags[$t->key] = (int) $t->value;
    }
    Yii::import('ext.TagCloud');
    $this->widget('TagCloud', array('tags' => $tags));
} catch (Exception $exc) {
    $error = json_decode($exc->getResponse());
    echo '<p class="error">No fue posible cargar las etiquetas'.($error ? " ({$error->reason})" : '').'.</p>';
    Yii::log($exc->getMessage(), 'error');
}


?>

</div>
