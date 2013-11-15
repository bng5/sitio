<h1><?php
echo $post->titulo;
if(!Yii::app()->user->isGuest) {
    echo ' '.CHtml::link(CHtml::image('/img/silk/page_edit.png', 'Editar'), array("bliki/{$post->path}/editar"));
}
?></h1>

<?php

//    $model->id,
//    $model->path,
//    $model->fecha_creado,
//    $model->fecha_modificado


if($cache && $post = Yii::app()->cache->get('p:'.$model->path)) {
    echo $post;
}
else {
    ob_start();

    echo $post->post;

    if(count($post->tags)) {
        echo '<div id="post_tags">Tags: ';
        foreach($post->tags AS $tag) {
            echo CHtml::link($tag->tag, array("tags/{$tag->tag}")).' ';
        }
        echo '</div>';
    }

    $buffer = ob_get_flush();
    Yii::app()->cache->set('p:'.$model->path, $buffer);
    
    
}


?>


<h2 id="comentarios">Comentarios</h2>

<?php

if($cache && $comentarios = Yii::app()->cache->get('c:'.$model->path)) {
    echo $comentarios;
}
else {

    $comentarios = array();
    foreach($post->comments AS $comment) {
        $key = $comment->reply_to ? 
            $comment->reply_to : 
            0;
        $comentarios[$key][] = $comment;
    }
    
    define('DOKU_INC', '/home/pablo/public_html/bng5.net/public_html/protected/extensions/doku/');
    require_once(DOKU_INC.'inc/parser/xhtml.php');
    require(DOKU_INC.'acronimos.php');
    require(DOKU_INC.'dokuwiki.php');

    require(DOKU_INC.'inc/geshi.php');

    require_once DOKU_INC.'inc/common.php';
    require_once DOKU_INC.'inc/confutils.php';
    require_once DOKU_INC.'inc/pageutils.php';
    require_once DOKU_INC.'inc/parserutils.php';
    require_once DOKU_INC.'inc/infoutils.php';
    require_once DOKU_INC.'inc/io.php';
    require_once DOKU_INC.'inc/utf8.php';
    //require_once DOKU_INC.'inc/auth.php';


    function mostrarComentarios($comentarios, $parent, $controller, $level = 0) {
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
            $autor = CHtml::image($avatar, '[Avatar]', array('width' => $img_size)).' '.$comment->nombre;
            echo '
            <dt'.$class_primero.' id="comentario-'.$comment->id.'">
                <span class="author">'.($comment->website ? CHtml::link($autor, $comment->website, array('title' => $comment->website, 'rel' => 'external')) : $autor).'</span> 
                <a href="#comentario-'.$comment->id.'"><span class="date" title="'.date(DATE_ATOM, $comment->fecha_creado).'">'.$controller->formato_fecha($comment->fecha_creado).'</span></a> 
                <a class="comment-reply-link" href="/2009/operador-ternario-operador-condicional/?replytocom=98590#respond" onclick="return addComment.moveForm(\'-98590\', \'98590\', \'respond\', \'2545\')">Responder</a>
            </dt>
            <dd>';
    //            <p>'.$comment->instrucciones.'</p>';
    $instrucciones = unserialize($comment->instrucciones);
    //echo '<pre>
    //';
    //echo htmlspecialchars(print_r($instrucciones, true));
    //echo '</pre>';
    if($instrucciones) {
        $Renderer = new Doku_Renderer_XHTML();
        //$Renderer->smileys = _getSmileys();
        foreach($instrucciones as $instruction) {
            call_user_func_array(array(&$Renderer, $instruction[0]),$instruction[1]);
        }
        echo $Renderer->doc;
    }
    //            <p>'.$comment->texto.'</p>';
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
    mostrarComentarios($comentarios, 0, $this);
    echo '<p>Generado: '.date(DATE_ATOM).'</p>';
    $buffer = ob_get_flush();
    Yii::app()->cache->set('c:'.$model->path, $buffer);
}

//foreach($comentarios[0] AS $comment) {
////    $autor = CHtml::image('http://www.gravatar.com/avatar/'.md5($comment->author->email_hash).'?s=55&d=mm', 'Avatar').' '.$comment->nombre;
//    $autor = CHtml::image('http://www.gravatar.com/avatar/'.$comment->author->email_hash.'?s=55&d=mm', 'Avatar').' '.$comment->nombre;
//    echo '
//        <dt class="user">';
//var_dump($comment->reply_to);
//    echo '
//            <span class="author">'.($comment->website ? CHtml::link($autor, $comment->website, array('title' => $comment->website, 'rel' => 'external')) : $autor).'</span> 
//            <a href="#comment-'.$comment->id.'"><span class="date" title="'.date(DATE_ATOM, $comment->fecha_creado).'">'.$this->formato_fecha($comment->fecha_creado).'</span></a> 
//            <a class="comment-reply-link" href="/2009/operador-ternario-operador-condicional/?replytocom=98590#respond" onclick="return addComment.moveForm(\'-98590\', \'98590\', \'respond\', \'2545\')">Responder</a>
//        </dt>
//        <dd>
//            <p>'.$comment->texto.'</p>
//        </dd>';
//}





if($post->comentarios_habilitados) {

    $this->renderPartial('comentar', array(
        'post' => $post,
    ));
            
?>



<?php
    
$form = $this->beginWidget('CActiveForm', array(
    'action' => "/bliki/{$post->path}/comentar",
	'id' => 'comentar',
	'enableAjaxValidation' => false,
));

    
echo $form->errorSummary($new_comment);

?>
    
	<div class="row">
        <?php
//var_dump(
//    $new_comment->id,
//    $new_comment->post_id,
//    $new_comment->author_id,
//    $new_comment->nombre,
//    $new_comment->remote_addr,
//    $new_comment->fecha_creado,
//    $new_comment->texto
//);
//        echo $form->labelEx($new_comment, 'nombre');
//        echo $form->textField($new_comment, 'nombre');
//        echo $form->error($new_comment, 'nombre');
//
////        echo $form->labelEx($new_comment->author, 'email');
////        echo $form->textField($new_comment->author, 'email');
////        echo $form->error($new_comment->author, 'email');
//        
//        echo $form->labelEx($new_comment, 'texto');
//        echo $form->textArea($new_comment, 'texto', array ( ));
//        echo $form->error($new_comment, 'texto');
        
        ?>
    </div>
    


<?php

$this->endWidget();

}

?>
    