<?php

Yii::app()->clientScript->registerScriptFile('/js/calendar.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/calendar_es-uy.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/calendar-setup.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile('/css/calendario.css');

Yii::app()->clientScript->registerScriptFile('/js/edicion.js', CClientScript::POS_HEAD);

$this->breadcrumbs = array(
	'Bliki' => array('bliki/'),
);

/*
$form = $this->beginWidget('CActiveForm', array(
//    'id'=>'user-form',
//    'enableAjaxValidation'=>true,
//    'enableClientValidation'=>true,
//    'focus'=>array($model,'firstName'),
));


//{
//   "_id": "leer_joystick_con_php",
//   "_rev": "9-0123315765fecea63b7c5a3b5a8396f9",
//   "notoc": true,
//   "title": "Leer un joystick con PHP",
//   "tags": [
//       "joystick",
//       "php",
//       "linux",
//       "xml"
//   ],
//   "created_at": 1384479459,
//   "summary": "hola hola",
//   "content": "<p> En los sistemas operativos Unix y GNU/Linux un archivo de dispositivo es un archivo especial estandarizado en Filesystem Hierarchy Standard que se establece en el directorio /dev (en el caso de Solaris en /devices) en cuyos subdirectorios se establece un contacto con dispositivos de la máquina, ya sean reales, como un disco duro, o virtuales, como /dev/null. Esta flexibilidad capaz de abstraer el dispositivo y considerar solo lo fundamental, la comunicación, le ha permitido adaptarse a la rapidez de los cambios y a la variación de dispositivos que ha enriquecido a la computación. </p> <p> El archivo de dispositivo representa al dispositivo para comunicarlo con programas que se ejecutan en la máquina. No es un archivo propiamente dicho, sino que el usuario lo ve como un archivo. Para ello debe existir un driver apropiado para el dispositivo. </p> <p> Por ejemplo, el programa de edición de imágenes Gimp puede acceder al scanner a través del archivo de dispositivo /dev/scan. </p> <p> Existen varios tipos de dispositivos: </p> <pre>     c – character devices: dispositivos orientados a caracteres     b – block devices: dispositivos orientados a bloques     s – socket devices: dispositivos orientados a sockets </pre> <p> Los nombres de los archivos de dispositivos dependen del sistema operativo. </p>   This is the location where device files for your input devices are located.  <h3>Device Files: (taken from Wikipedia)</h3>      In Unix-like operating systems, a device file or special file is an interface for a device driver that appears in a file system as if it were an ordinary file...They allow software to interact with a device driver using standard input/output system calls, which simplifies many tasks and unifies user-space I/O mechanisms.            <p>             La forma usual de leer un joystick es a través de la librería <acronym>SDL</acronym>...         </p>          <docb:note>             <docb:title>Nota</docb:title>             <docb:para>No hay información útil.             </docb:para>         </docb:note>     <pre> http://www.pygame.org/docs/ref/joystick.html </pre>",
//}


echo '
<table>
    <tbody>';
foreach($model AS $k => $v) {
    echo "
        <tr>
            <td>".$form->label($model, $k)."</td>
            <td>";
    
    if(is_string($v)) {
        echo "<input type=\"text\" name=\"{$k}\" id=\"Post_{$k}\" value=\"{$v}\" />";
    }
    elseif(is_bool($v)) {
        echo "<input type=\"checkbox\" name=\"{$k}\" id=\"Post_{$k}\" value=\"{$v}\" />";
    }
    elseif(is_int($v)) {
        echo $v;
    }
    echo "
            </td>
        </tr>";
}
echo '
    </tbody>
</table>';

$this->endWidget();
*/

//var_dump($model->tags);
//exit;
if($model->_rev) {
    $is_new_record = false;
    $accion_actual = 'Editando '.$model->title;
    $this->breadcrumbs[$model->title] = array('bliki/'.$path);
    $this->breadcrumbs[] = 'edición';
}
else {
    $is_new_record = true;
    $accion_actual = 'Creando '.$path;
    $this->breadcrumbs[] = $accion_actual;
}

$sel_estado = array(
    0 => '',
    1 => '',
);
$sel_estado[(int) $model->public] = 'checked="true" ';
//$this->unshiftPageTitle($accion_actual);

?>

<h1><?php echo $accion_actual; ?></h1>

<?php

if(Yii::app()->user->hasFlash('success')) {
    echo '
    <div class="flash-success">
        <p>Cambios guardados.</p>
    </div>';
}

if($error) {
    echo '
    <div class="info important">
        <h3>Aviso</h3>
        <p>'.$error.'</p>
    </div>';
}

//if($prev) {
//    echo $model->post;
//}

if($preview) {
    
?>

<div id="articulo">
<?php echo $preview; ?>
</div>
<hr style="clear: both;" />

<?php
}

?>

<div class="form">

<form id="suscriptions-form" action="/bliki/<?php echo $path; ?>/editar" method="post">
    <table style="width: 100%;">
<?php

if(!$is_new_record) {
    echo "
        <tr>
            <td>
                <label>Rev</label>
                <input type=\"hidden\" name=\"Post[_rev]\" value=\"{$model->_rev}\" />
            </td>
            <td>
                <span>{$model->_rev}</span>
            </td>
        </tr>";    
}

?>

        <tr>
            <td>
                <label for="PostContent_id">Id</label>
            </td>
            <td>
                <input type="text" name="Post[_id]" value="<?php echo $model->_id; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="PostContent_path">Path</label>
            </td>
            <td>
                <input type="text" name="Post[path]" value="<?php echo $model->path; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="PostContent_titulo">Título</label>
            </td>
            <td>
                <input size="60" maxlength="255" name="Post[title]" id="PostContent_titulo" type="text" value="<?php echo $model->title; ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="Post_public">Público</label>
            </td>
            <td>
                <input type="checkbox" name="Post[public]" id="Post_public" value="1" <?php echo $sel_estado[1]; ?>/>
            </td>
        </tr>
        <tr>
            <td>
                <label for="PostContent_resumen">Resumen</label>
            </td>
            <td>
                <textarea style="display: block;" rows="10" cols="68" name="Post[summary]" id="PostContent_resumen"><?php echo htmlspecialchars($model->summary); ?></textarea>
            </td>
        </tr>
    	<tr>
            <td>
                <label for="PostContent_toc_habilitado">Toc Deshabilitado</label>
            </td>
            <td>
                <input name="Post[notoc]" id="PostContent_toc_habilitado" value="1" type="checkbox" <?php echo ($model->notoc ? 'checked="checked"' : ''); ?> />
            </td>
        </tr>
    	<tr>
            <td>
                <label for="PostContent_comentarios_habilitados">Comentarios Habilitados</label>
            </td>
            <td>
                <input name="Post[nocomments]" id="PostContent_comentarios_habilitados" value="1" <?php echo ($model->nocomments ? 'checked="checked"' : ''); ?> type="checkbox" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="PostContent_fecha_creado">Fecha Creado</label>
            </td>
            <td>
                <span id="mostrar_fecha1">Sábado 21 de Setiembre de 2013</span>&nbsp;&nbsp;<img src="/img/silk/calendar" id="tn_calendario1" style="cursor: pointer;" title="Abrir calendario" alt="Abrir calendario" /><input name="Post[created_at]" id="PostContent_fecha_creado" type="hidden" value="1379735929" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="PostContent_fecha_modificado">Fecha Modificado</label>
            </td>
            <td>
                <span id="mostrar_fecha2">No especificada</span>&nbsp;&nbsp;<img src="/img/silk/calendar" id="tn_calendario2" style="cursor: pointer;" title="Abrir calendario" alt="Abrir calendario" /><input name="Post[updated_at]" id="PostContent_fecha_modificado" type="hidden" />
            </td>
        </tr>
    	<tr>
            <td>
                <label for="PostContent_texto">Texto</label>
            </td>
            <td>
                <div>
    <!--                <button type="button" onclick="insertTags('PostContent_texto', '**','**','Texto en negrita')" title="Texto en negrita"><img src="/img/silk/text_bold" alt="Texto en negrita" /></button>
                    <button type="button" onclick="insertTags('PostContent_texto', '//','//','Texto en cursiva')" title="Texto en cursiva"><img src="/img/silk/text_italic" alt="Texto en cursiva" /></button>
                    <button type="button" onclick="insertTags('PostContent_texto', '__','__','Texto subrayado')" title="Texto subrayado"><img src="/img/silk/text_underline" alt="Texto subrayado" /></button>
                    <button type="button" onclick="insertarEnlace('PostContent_texto')" title="Enlace externo"><img src="/img/silk/link" alt="Enlace externo" /></button>-->
                    <select onchange="addAdmonition(this, 'PostContent_texto');" title="Avisos">
                        <option value="" class="label">Avisos</option>
                        <option value="caution" class="label">Precaución</option>
                        <option value="tip" class="label">Consejo</option>
                        <option value="note" class="label">Nota</option>
                        <option value="important" class="label">Importante</option>
                        <option value="warning" class="label">Advertencia</option>
                    </select>
                    <select onchange="highlightSource(this, 'PostContent_texto');" title="Resaltado de código">
                        <option value="" class="label">Resaltado de código</option>
                        <option value="4cs">4cs</option>
                        <option value="6502acme">6502acme</option>
                        <option value="6502kickass">6502kickass</option>
                        <option value="6502tasm">6502tasm</option>
                        <option value="68000devpac">68000devpac</option>
                        <option value="abap">abap</option>
                        <option value="actionscript-french">actionscript-french</option>
                        <option value="actionscript">actionscript</option>
                        <option value="actionscript3">actionscript3</option>
                        <option value="ada">ada</option>
                        <option value="algol68">algol68</option>
                        <option value="apache">apache</option>
                        <option value="applescript">applescript</option>
                        <option value="asm">asm</option>
                        <option value="asp">asp</option>
                        <option value="autoconf">autoconf</option>
                        <option value="autohotkey">autohotkey</option>
                        <option value="autoit">autoit</option>
                        <option value="avisynth">avisynth</option>
                        <option value="awk">awk</option>
                        <option value="bascomavr">bascomavr</option>
                        <option value="bash">bash</option>
                        <option value="basic4gl">basic4gl</option>
                        <option value="bf">bf</option>
                        <option value="bibtex">bibtex</option>
                        <option value="blitzbasic">blitzbasic</option>
                        <option value="bnf">bnf</option>
                        <option value="boo">boo</option>
                        <option value="c">c</option>
                        <option value="c_loadrunner">c_loadrunner</option>
                        <option value="c_mac">c_mac</option>
                        <option value="caddcl">caddcl</option>
                        <option value="cadlisp">cadlisp</option>
                        <option value="cfdg">cfdg</option>
                        <option value="cfm">cfm</option>
                        <option value="chaiscript">chaiscript</option>
                        <option value="cil">cil</option>
                        <option value="clojure">clojure</option>
                        <option value="cmake">cmake</option>
                        <option value="cobol">cobol</option>
                        <option value="coffeescript">coffeescript</option>
                        <option value="cpp">cpp</option>
                        <option value="cpp-qt">cpp-qt</option>
                        <option value="csharp">csharp</option>
                        <option value="css">css</option>
                        <option value="cuesheet">cuesheet</option>
                        <option value="d">d</option>
                        <option value="dcs">dcs</option>
                        <option value="delphi">delphi</option>
                        <option value="diff">diff</option>
                        <option value="div">div</option>
                        <option value="dos">dos</option>
                        <option value="dot">dot</option>
                        <option value="e">e</option>
                        <option value="epc">epc</option>
                        <option value="ecmascript">ecmascript</option>
                        <option value="eiffel">eiffel</option>
                        <option value="email">email</option>
                        <option value="erlang">erlang</option>
                        <option value="euphoria">euphoria</option>
                        <option value="f1">f1</option>
                        <option value="falcon">falcon</option>
                        <option value="fo">fo</option>
                        <option value="fortran">fortran</option>
                        <option value="freebasic">freebasic</option>
                        <option value="fsharp">fsharp</option>
                        <option value="gambas">gambas</option>
                        <option value="genero">genero</option>
                        <option value="genie">genie</option>
                        <option value="gdb">gdb</option>
                        <option value="glsl">glsl</option>
                        <option value="gml">gml</option>
                        <option value="gnuplot">gnuplot</option>
                        <option value="go">go</option>
                        <option value="groovy">groovy</option>
                        <option value="gettext">gettext</option>
                        <option value="gwbasic">gwbasic</option>
                        <option value="haskell">haskell</option>
                        <option value="hicest">hicest</option>
                        <option value="hq9plus">hq9plus</option>
                        <option value="html">html</option>
                        <option value="html5">html5</option>
                        <option value="icon">icon</option>
                        <option value="idl">idl</option>
                        <option value="ini">ini</option>
                        <option value="inno">inno</option>
                        <option value="intercal">intercal</option>
                        <option value="io">io</option>
                        <option value="j">j</option>
                        <option value="java5">java5</option>
                        <option value="java">java</option>
                        <option value="javascript">javascript</option>
                        <option value="jquery">jquery</option>
                        <option value="kixtart">kixtart</option>
                        <option value="klonec">klonec</option>
                        <option value="klonecpp">klonecpp</option>
                        <option value="latex">latex</option>
                        <option value="lb">lb</option>
                        <option value="lisp">lisp</option>
                        <option value="llvm">llvm</option>
                        <option value="locobasic">locobasic</option>
                        <option value="logtalk">logtalk</option>
                        <option value="lolcode">lolcode</option>
                        <option value="lotusformulas">lotusformulas</option>
                        <option value="lotusscript">lotusscript</option>
                        <option value="lscript">lscript</option>
                        <option value="lsl2">lsl2</option>
                        <option value="lua">lua</option>
                        <option value="m68k">m68k</option>
                        <option value="magiksf">magiksf</option>
                        <option value="make">make</option>
                        <option value="mapbasic">mapbasic</option>
                        <option value="matlab">matlab</option>
                        <option value="mirc">mirc</option>
                        <option value="modula2">modula2</option>
                        <option value="modula3">modula3</option>
                        <option value="mmix">mmix</option>
                        <option value="mpasm">mpasm</option>
                        <option value="mxml">mxml</option>
                        <option value="mysql">mysql</option>
                        <option value="newlisp">newlisp</option>
                        <option value="nsis">nsis</option>
                        <option value="oberon2">oberon2</option>
                        <option value="objc">objc</option>
                        <option value="objeck">objeck</option>
                        <option value="ocaml-brief">ocaml-brief</option>
                        <option value="ocaml">ocaml</option>
                        <option value="oobas">oobas</option>
                        <option value="oracle8">oracle8</option>
                        <option value="oracle11">oracle11</option>
                        <option value="oxygene">oxygene</option>
                        <option value="oz">oz</option>
                        <option value="pascal">pascal</option>
                        <option value="pcre">pcre</option>
                        <option value="perl">perl</option>
                        <option value="perl6">perl6</option>
                        <option value="per">per</option>
                        <option value="pf">pf</option>
                        <option value="php-brief">php-brief</option>
                        <option value="php">php</option>
                        <option value="pike">pike</option>
                        <option value="pic16">pic16</option>
                        <option value="pixelbender">pixelbender</option>
                        <option value="pli">pli</option>
                        <option value="plsql">plsql</option>
                        <option value="postgresql">postgresql</option>
                        <option value="povray">povray</option>
                        <option value="powerbuilder">powerbuilder</option>
                        <option value="powershell">powershell</option>
                        <option value="proftpd">proftpd</option>
                        <option value="progress">progress</option>
                        <option value="prolog">prolog</option>
                        <option value="properties">properties</option>
                        <option value="providex">providex</option>
                        <option value="purebasic">purebasic</option>
                        <option value="pycon">pycon</option>
                        <option value="python">python</option>
                        <option value="q">q</option>
                        <option value="qbasic">qbasic</option>
                        <option value="rails">rails</option>
                        <option value="rebol">rebol</option>
                        <option value="reg">reg</option>
                        <option value="robots">robots</option>
                        <option value="rpmspec">rpmspec</option>
                        <option value="rsplus">rsplus</option>
                        <option value="ruby">ruby</option>
                        <option value="sas">sas</option>
                        <option value="scala">scala</option>
                        <option value="scheme">scheme</option>
                        <option value="scilab">scilab</option>
                        <option value="sdlbasic">sdlbasic</option>
                        <option value="smalltalk">smalltalk</option>
                        <option value="smarty">smarty</option>
                        <option value="sql">sql</option>
                        <option value="systemverilog">systemverilog</option>
                        <option value="tcl">tcl</option>
                        <option value="teraterm">teraterm</option>
                        <option value="text">text</option>
                        <option value="thinbasic">thinbasic</option>
                        <option value="tsql">tsql</option>
                        <option value="typoscript">typoscript</option>
                        <option value="unicon">unicon</option>
                        <option value="uscript">uscript</option>
                        <option value="vala">vala</option>
                        <option value="vbnet">vbnet</option>
                        <option value="vb">vb</option>
                        <option value="verilog">verilog</option>
                        <option value="vhdl">vhdl</option>
                        <option value="vim">vim</option>
                        <option value="visualfoxpro">visualfoxpro</option>
                        <option value="visualprolog">visualprolog</option>
                        <option value="whitespace">whitespace</option>
                        <option value="winbatch">winbatch</option>
                        <option value="whois">whois</option>
                        <option value="xbasic">xbasic</option>
                        <option value="xml">xml</option>
                        <option value="xorg_conf">xorg_conf</option>
                        <option value="xpp">xpp</option>
                        <option value="yaml">yaml</option>
                        <option value="z80">z80</option>
                        <option value="zxbasic">zxbasic</option>
                    </select>
                    <textarea style="display: block; width: 100%;" rows="15" cols="68" name="Post[content]" id="PostContent_texto"><?php echo htmlspecialchars($model->content); ?></textarea>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <label>Tags <a onclick="agregarTag('tags')">Agregar</a></label>
            </td>
            <td>
                <ul id="tags">
                <?php
                foreach($model->tags AS $tag) {
                    echo '
                    <li><input name="Post[tags][]" type="text" value="'.$tag.'" /></li>';
                }
                ?>
                    <li><input name="Post[tags][]" type="text" value="" /></li>
                </ul>
            </td>
        </tr>
    </table>


	<div class="row buttons">
		<input type="submit" name="preview" value="Previsualizar" />
        <input type="submit" name="guardar" value="Guardar" />
        <input type="submit" name="publicar" value="Guardar/Publicar" />
    </div>

</form>

</div><!-- form -->

<?php

$formato = "%s";
$formatoMst = "%A, %d de %B de %Y, %H:%M hs.";
$mostrarHora = "true";

echo "
        
<script type=\"text/javascript\">
//<![CDATA[
Calendar.setup({
    inputField: \"Post_fecha_creado\",
    ifFormat: \"{$formato}\",
    displayArea: \"mostrar_fecha1\",
    daFormat: \"{$formatoMst}\",
    button: \"tn_calendario1\",
    showsTime: {$mostrarHora}
});
Calendar.setup({
    inputField: \"Post_fecha_modificado\",
    ifFormat: \"{$formato}\",
    displayArea: \"mostrar_fecha2\",
    daFormat: \"{$formatoMst}\",
    button: \"tn_calendario2\",
    showsTime: {$mostrarHora}
});
//]]>
</script>
";


?>