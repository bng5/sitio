<?php

/*

<!-- link rel="openid2.provider" href="https://www.google.com/accounts/o8/ud?source=profiles" >

        <link rel="openid2.local_id" href="http://www.google.com/profiles/115450148292606387673" -->
<!-- link href="http://www.myopenid.com/server" rel="openid.server" / -->
<link href="http://bng5.net/openid/server.php" rel="openid.server" />
<link href="http://pablo.bng5.net" rel="openid.delegate" />
        <meta name="date" content="2011-03-13T22:51:58-0500" />

 */
?>

        <h2 class="sectionedit2" id="informacion_personal">Información personal <a class="permalink" href="#informacion_personal">¶</a></h2>
        <div class="level2">
            <dl>
                <dt id=" Nombre: "> Nombre: </dt>
                <dd> Pablo Bangueses</dd>
                <dt> Ubicación: </dt>
                <dd> América/Montevideo</dd>
                <dt> Edad:</dt>
                <dd> <?php echo $edad; ?> (febrero-1981)</dd>
            </dl>
        </div>

        <h2 class="sectionedit3" id="redes">Redes <a class="permalink" href="#redes">¶</a></h2>
        <div class="level2">
            <ul class="redes">
                <li id="web"><a href="http://bng5.net" rel="nofollow" title="Web">bng5.net</a></li>
                <li id="twitter"><a href="https://twitter.com/bng5" rel="nofollow" title="Twitter">twitter.com/bng5</a></li>
                <li id="delicious"><a href="https://delicious.com/bng5" rel="nofollow" title="Delicious">delicious.com/bng5</a></li>
                <li id="linkedin"><a href="http://uy.linkedin.com/in/pablobngs" rel="nofollow" title="LinkedIn" >linkedin.com/in/pablobngs</a></li>
                <li id="github"><a href="https://github.com/bng5/" rel="nofollow" title="GitHub">github.com/bng5</a></li>                
                <!-- li id="forosdelweb"><a href="http://www.forosdelweb.com/miembros/bng5/" target="_blank" title="Foros del Web"><img src="http://www.google.com/s2/favicons?domain=www.forosdelweb.com" alt="[ícono]" /> Foros del Web</a></li -->
                <!-- li id="sourceforge"><a href="https://sourceforge.net/users/bng5" rel="nofollow" title="SourceForge" ><img src="http://www.google.com/s2/favicons?domain=sourceforge.net" alt="[ícono]" /> SourceForge</a></li -->
            </ul>
        </div>

        <h2 class="sectionedit4" id="clave_publica_gnupg">Clave pública GnuPG <a class="permalink" href="#clave_publica_gnupg">¶</a></h2>
        <div class="level2">
            
            <?php
            
            foreach($this->keys AS $k => $key) {
                echo '
            <p>
                <strong>pub</strong>  '.sprintf($key['pub'], CController::createUrl($this->prefix.'gnupg/'.$k)).'<br/>
                <strong>uid</strong> '.$key['uid'].'<br/>';
                if(array_key_exists('sub', $key)) {
                    echo '
                <strong>sub</strong>  '.$key['sub'].'<br/>';
                }
                echo '
            </p>
            <hr style="margin: 0 0 1em;" />';
            }
            
            ?>
        </div>



        <h2 class="sectionedit5" id="claves_de_autenticacion_ssh">Claves de autenticación SSH <a class="permalink" href="#claves_de_autenticacion_ssh">¶</a></h2>
        <div class="level2">
            
            <?php
            
            foreach($this->ssh_keys AS $k => $key) {
                echo '
            <p>
                <strong><a id="'.$k.'" href="'.CController::createUrl($this->prefix.'ssh_keys/'.$k).'">'.$key['id'].'</a></strong><br />
                Huella de clave = '.$key['fingerprint'].'
            </p>';
            }
            
            ?>

        </div>
        


        <!-- div class="stylefoot">
            <div class="meta">
                <div class="doc">Última modificación: 2011/03/13 22:51 por pablo</div>
            </div>
        </div -->

