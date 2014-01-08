<?php

$avatar = $comment->getAvatar();
$autor = CHtml::image($avatar, '[Avatar]', array('width' => 55)).' '.$comment->author;
$classname = $comment->status ? '' : 'class="disabled"';

echo '
<dl>    
    <dt '.$classname.' id="comentario-'.$comment->_id.'">
        <span class="author">'.($comment->author_website ? CHtml::link($autor, $comment->author_website, array('title' => $comment->author_website, 'rel' => 'external')) : $autor).'</span> 
        <a href="/bliki/'.$comment->post_id.'#comentario-'.$comment->_id.'"><span class="date" title="'.date(DATE_ATOM, $comment->created_at).'">'.$this->formato_fecha($comment->created_at).'</span></a>
        <span class="post">en <a href="/bliki/'.$comment->post_id.'">'.$comment->post_id.'</a></span>
    </dt>
    <dd '.$classname.'>
        '.$comment->html.'
    </dd>
</dl>';
