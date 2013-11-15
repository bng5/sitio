<?php

//      toc="true">
//print_r($model->keywords);
//print_r($model->tags);
//print_r($model->changelog);


Yii::app()->clientScript->registerScriptFile('/js/calendar.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/calendar_es-uy.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/calendar-setup.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile('/css/calendario.css');

Yii::app()->clientScript->registerScript('edicion', "

// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
function insertTags(area, tagOpen, tagClose, sampleText) {
    var txtarea = document.getElementById(area);
    var selText, isSample = false;

    if (document.selection  && document.selection.createRange) { // IE/Opera
        //save window scroll position

        var winScroll;
        if (document.documentElement && document.documentElement.scrollTop)
            winScroll = document.documentElement.scrollTop
        else if (document.body)
            winScroll = document.body.scrollTop;
        //get current selection
        txtarea.focus();
        var range = document.selection.createRange();
        selText = range.text;
        //insert tags
        checkSelectedText();
        range.text = tagOpen + selText + tagClose;
        //mark sample text as selected
        if(isSample && range.moveStart) {
            if (window.opera)
                tagClose = tagClose.replace(/\\n/g,'');
            range.moveStart('character', - tagClose.length - selText.length);
            range.moveEnd('character', - tagClose.length);
        }
        range.select();
        //restore window scroll position
        if (document.documentElement && document.documentElement.scrollTop)
            document.documentElement.scrollTop = winScroll
        else if (document.body)
            document.body.scrollTop = winScroll;
    }
    else if (txtarea.selectionStart || txtarea.selectionStart == '0') { // Mozilla
        //save textarea scroll position
        var textScroll = txtarea.scrollTop;
        //get current selection
        txtarea.focus();
        var startPos = txtarea.selectionStart;
        var endPos = txtarea.selectionEnd;
        selText = txtarea.value.substring(startPos, endPos);
        //insert tags
        if (!selText) {
            selText = sampleText;
            isSample = true;
        }
        else if (selText.charAt(selText.length - 1) == ' ') { //exclude ending space char
            selText = selText.substring(0, selText.length - 1);
            tagClose += ' ';
        }
        txtarea.value = txtarea.value.substring(0, startPos) + tagOpen + selText + tagClose + txtarea.value.substring(endPos, txtarea.value.length);
        //set new selection
        if(isSample) {
            txtarea.selectionStart = startPos + tagOpen.length;
            txtarea.selectionEnd = startPos + tagOpen.length + selText.length;
        }
        else {
            txtarea.selectionStart = startPos + tagOpen.length + selText.length + tagClose.length;
            txtarea.selectionEnd = txtarea.selectionStart;
        }
        //restore textarea scroll position
        txtarea.scrollTop = textScroll;
    }
}

function insertarEnlace(areaTexto) {
    var txtarea = document.getElementById(areaTexto);
    txtarea.focus();
    var startPos = txtarea.selectionStart;
    var endPos = txtarea.selectionEnd;
    selText = txtarea.value.substring(startPos, endPos);
    var url = prompt('Dirección de destino', selText);
    if(url) {
        var tit = prompt('Texto del enlace', selText);
        if(tit)
            url = url+'|'+tit;
        txtarea.value = txtarea.value.substring(0, startPos) + url + txtarea.value.substring(endPos, txtarea.value.length);
        txtarea.selectionStart = startPos;
        txtarea.selectionEnd = txtarea.selectionStart + url.length;
        insertTags(areaTexto, '[[', ']]', '')
    }
}

function highlightSource(select, textarea) {
    var lang = select.options[select.options.selectedIndex].value;
    select.options.selectedIndex = 0;
    insertTags(textarea, '<p:source lang=\"'+lang+'\" linenumbers=\"false\"><![CDATA[\\n', '\\n]]></p:source>', 'Código...');
}

function addAdmonition(select, textarea) {
    var type = select.options[select.options.selectedIndex].value;
    var title = select.options[select.options.selectedIndex].text;
    select.options.selectedIndex = 0;
    insertTags(textarea, '<docb:'+type+'>\\n    <docb:title>'+title+'</docb:title>\\n    <docb:para>', '</docb:para>\\n</docb:'+type+'>\\n', '');
}

var actual = {};
actual['a1_'] = 'comentario_comentario';

function agregarTag(attr) {
    var list = document.getElementById(attr+'s');
    var li = document.createElement('li');
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'PostContent[attr][]';
    li.appendChild(input);
    list.appendChild(li);
}

", CClientScript::POS_HEAD);
//echo '<pre>';
//print_r($model->attributes);
//echo '</pre>';


echo '<p><tt>'.basename(__FILE__).'</tt></p>
';



$this->breadcrumbs = array(
	'Bliki' => array('bliki/'),
);

if($model->isNewRecord) {
    $accion_actual = 'Creando '.$path;
    $this->breadcrumbs[] = $accion_actual;
}
else {
    $accion_actual = 'Editando '.$model->titulo;
    $this->breadcrumbs[$model->titulo] = array('bliki/'.$path);
    $this->breadcrumbs[] = 'edición';
}

$sel_estado = array(
    0 => '',
    1 => '',
);
$sel_estado[$estado] = 'checked="true" ';
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

?>

<div class="form">

<form id="suscriptions-form" action="/bliki/<?php echo $path; ?>/editar" method="post">
    <ul class="form">
        <li>
            <label for="PostContent_id">Id</label>
            <span><?php echo ($model->id ? $model->id : 'No'); ?></span>
        </li>
        <li>
            <label>Estado</label>
            <div>
                <input type="radio" name="PostContent[estado]" id="PostContent_estado1" value="1" <?php echo $sel_estado[1]; ?>/> <label for="PostContent_estado1">Público</label>
                <input type="radio" name="PostContent[estado]" id="PostContent_estado0" value="0" <?php echo $sel_estado[0]; ?>/> <label for="PostContent_estado0">Privado</label>
            </div>
        </li>
        <li>
            <label for="PostContent_titulo">Titulo</label>
            <input size="60" maxlength="255" name="PostContent[titulo]" id="PostContent_titulo" type="text" value="<?php echo $model->titulo; ?>" />
    	<li>
            <label for="PostContent_path">Path</label>
            <input size="60" maxlength="255" name="PostContent[path]" id="PostContent_path" type="text" value="<?php echo $path; ?>" />
        </li>
        <li>
            <label for="PostContent_resumen">Resumen</label>
            <textarea style="display: block;" rows="10" cols="68" name="PostContent[resumen]" id="PostContent_resumen"><?php echo htmlspecialchars($model->resumen); ?></textarea>
        </li>
    	<li>
            <label for="PostContent_texto">Texto</label>
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
                <textarea style="display: block;" rows="15" cols="68" name="PostContent[texto]" id="PostContent_texto"><?php echo htmlspecialchars($model->sourceText); ?></textarea>
            </div>
        </li>
    	<li>
            <label for="PostContent_toc_habilitado">Toc Habilitado <?php var_dump($model->toc_habilitado); ?></label>
            <input name="PostContent[toc_habilitado]" id="PostContent_toc_habilitado" value="1" type="checkbox" <?php echo ($model->toc_habilitado ? 'checked="checked"' : ''); ?> />
        </li>
    	<li>
            <label for="PostContent_comentarios_habilitados">Comentarios Habilitados <?php var_dump($model->comentarios_habilitados); ?></label>
            <input name="PostContent[comentarios_habilitados]" id="PostContent_comentarios_habilitados" value="1" <?php echo ($model->comentarios_habilitados ? 'checked="checked"' : ''); ?> type="checkbox" />
        </li>
        <li>
            <label for="PostContent_fecha_creado">Fecha Creado</label>
            <span id="mostrar_fecha1">Sábado 21 de Setiembre de 2013</span>&nbsp;&nbsp;<img src="/img/silk/calendar" id="tn_calendario1" style="cursor: pointer;" title="Abrir calendario" alt="Abrir calendario" /><input name="PostContent[fecha_creado]" id="PostContent_fecha_creado" type="hidden" value="1379735929" />
        </li>
        <li>
            <label for="PostContent_fecha_modificado">Fecha Modificado</label>
            <span id="mostrar_fecha2">No especificada</span>&nbsp;&nbsp;<img src="/img/silk/calendar" id="tn_calendario2" style="cursor: pointer;" title="Abrir calendario" alt="Abrir calendario" /><input name="PostContent[fecha_modificado]" id="PostContent_fecha_modificado" type="hidden" />
        </li>
        <li>
            <label>Tags <a onclick="agregarTag('tag')">Agregar</a></label>
            <ul id="tags" style="margin-left: 10em;">
            <?php
            foreach($model->tags AS $tag) {
                echo '
                <li><input name="PostContent[tags][]" type="text" value="'.$tag.'" /></li>';
            }
            ?>
                <li><input name="PostContent[tags][]" type="text" value="" /></li>
            </ul>
        </li>
        <li>
            <label>Keywords <a onclick="agregarTag('keyword')">Agregar</a></label>
            <ul id="keywords" style="margin-left: 10em;">
            <?php
            foreach($model->keywords AS $keywords) {
                echo '
                <li><input name="PostContent[keyword][]" type="text" value="'.$keywords.'" /></li>';
            }
            ?>
                <li><input name="PostContent[keyword][]" type="text" value="" /></li>
            </ul>
        </li>
        <li>
            <label>Changelog</label>
            <ul id="changelog" style="margin-left: 10em;">
            <?php
            foreach($model->changelog AS $tag) {
                echo '
                <li><input name="PostContent[changelog_text][]" type="text" value="'.$tag.'" /></li>';
            }
            ?>
                <li><input name="PostContent[changelog_text][]" type="text" value="" /></li>
            </ul>
        </li>
    </ul>


	<div class="row buttons">
		<input type="submit" name="previsualizar" value="Previsualizar" />
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