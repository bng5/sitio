<?php

foreach($dataProvider AS $tag) {
    echo "
        <div><a href=\"/tags/{$tag->tag}\">{$tag->tag}</a> ({$tag->posts_count})</div>";

}

?>
