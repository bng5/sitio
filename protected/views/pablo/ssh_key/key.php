<h1>Claves de autenticación SSH</h1>

<h2><?php echo $this->ssh_keys[$id]['id']; ?></h2>

<p>Huella de clave = <?php echo $this->ssh_keys[$id]['fingerprint']; ?></p>

<dl class="file">
    <dt><a href="<?php echo CController::createUrl($this->prefix.'ssh_keys/'.$id.'.pub'); ?>" class="mediafile mf_pub"><?php echo $this->ssh_keys[$id]['id']; ?>.pub</a></dt>
    <dd>
        <pre><?php echo $this->ssh_keys[$id]['content']; ?></pre>
    </dd>
</dl>

