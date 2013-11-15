
<h2>Historial de cambios</h2>
<ul>
<?php

foreach($model->changelog AS $changelog) {
    //$changelog->type
    echo "<li><a href=\"/bliki/{$model->path}?rev={$changelog->rev}\">{$changelog->time}</a> {$changelog->desc}</li>";
}

?>
</ul>
