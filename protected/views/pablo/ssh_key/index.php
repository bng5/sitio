    <h1 class="sectionedit5"><a name="claves_de_autenticacion_ssh" id="claves_de_autenticacion_ssh">Claves de autenticación SSH</a></h1>
    <div class="level2">

        <?php

        foreach($this->ssh_keys AS $k => $key) {
            echo '
        <p>
            <strong><a name="'.$k.'" id="'.$k.'" href="'.CController::createUrl($this->prefix.'ssh_keys/'.$k).'">'.$key['id'].'</a></strong><br />
            Huella de clave = '.$key['fingerprint'].'
        </p>';
        }

        ?>

    </div>
