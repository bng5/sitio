<h1><?php echo $id; ?></h1>

<p>Huella de clave = <?php echo $key['fingerprint']; ?></p>

<dl class="file">
    <dt><a href="<?php echo CController::createUrl($this->prefix.'gnupg/'.$id.'.asc'); ?>" class="mediafile mf_asc"><?php echo $id; ?>.asc</a></dt>
    <dd>
        <pre><?php echo $key['content']; ?></pre>
    </dd>
</dl>
