
<h1 class="sectionedit4"><a name="clave_publica_gnupg" id="clave_publica_gnupg">Claves públicas GnuPG</a></h1>
<div class="level2">

    <?php

    foreach($this->keys AS $k => $key) {
        echo '
    <p>
        <strong>pub</strong>  '.sprintf($key['pub'], CController::createUrl($this->prefix.'gnupg/'.$k)).'<br/>
        <strong>uid</strong> '.$key['uid'].'<br/>
        <strong>sub</strong>  '.$key['sub'].'<br/>
    </p>
    <hr style="margin: 0 0 1em;" />';
    }

    ?>
</div>
