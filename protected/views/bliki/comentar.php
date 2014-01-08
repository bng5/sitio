<?php

//Yii::app()->clientScript->registerCssFile('http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css');
Yii::app()->clientScript->registerCssFile('/css/smoothness/jquery-ui-1.10.2.custom.min.css');

Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/jquery-1.9.1.js');
Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/ui/1.10.2/jquery-ui.js');
//Yii::app()->clientScript->registerScriptFile('/js/jquery-1.9.1.js');
//Yii::app()->clientScript->registerScriptFile('/js/jquery-ui-1.10.2.custom.min.js');

Yii::app()->clientScript->registerScriptFile('https://login.persona.org/include.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile('/js/persona.js', CClientScript::POS_HEAD);

//Yii::app()->clientScript->registerScript('openid_dialog',"
//
//
//", CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile('/js/wikitags.js', CClientScript::POS_HEAD);

$field_class = array(
    'nombre' => '',
    'email' => '',
    'website' => '',
);
                
//Yii::app()->clientScript->registerScript('buttons',"
//    
//$(function() {
//
//
//    $('button[type=submit]').click(function(event) {
//
////    $('#comentario').submit(function(event) {
////console.log(event);
//
//        event.preventDefault();
//        
//        var form = this.form;
//        
//        var post_data = {Comentario: {}, Auth: {}};
//        post_data.Comentario.comentario = $('#comentario_comentario').val();
//        post_data.Auth.type = this.value;
//        
//$.ajax({
//    url: this.form.action,
//    dataType: 'html',
//    type: 'POST',
//    data: post_data
//}).done(function(data, textStatus, jqXHR) {
//
//});
//        
//        return false;
//    });
//});
//
//", CClientScript::POS_HEAD);


//if($exc) {
//    echo $exc->getMessage();
//}

if($comentario && $comentario->hasErrors()) {
//    var_dump($comentario->errors);
    echo '
    <div class="error">
        <ul>';
    foreach($comentario->errors as $key => $value) {
        $field_class[$key] = 'class="error"';
        echo "
            <li>{$value[0]}</li>";
    }
    echo '
        </ul>
    </div>';
}

?>

<form id="comentario" action="/bliki/<?php echo $post_id; ?>/comentarios" method="post"><!-- onsubmit="return enviarComentario(this);" -->
    <input type="hidden" id="auth_type" name="Auth[type]" value="" />
    <input type="hidden" id="Comentario_reply_to" name="Comentario[reply_to]" value="" />
    <ul class="form">
        <!-- 
        <li><label for="comentario_nombre">Nombre*</label> <input type="text" name="Comentario[nombre]" id="comentario_nombre" value="<?php echo $comentario->author; ?>" <?php 
        echo $field_class['nombre']; ?> /></li>
        <li><label for="comentario_email">E-mail*</label> <input type="text" name="Comentario[email]" id="comentario_email" value="<?php echo $comentario->author_email; ?>" <?php echo $field_class['email']; ?>/></li>
        <li><label for="comentario_website">Sitio web</label> <input type="text" name="Comentario[website]" id="comentario_website" value="<?php echo $comentario->author_website; ?>" <?php echo $field_class['website']; ?>/></li>
        -->
        <li><label for="comentario_comentario">Comentario*</label> 
            <div class="toolbar">
                <button type="button" onclick="insertTags(actual['a1_'], '**','**','Texto en negrita')" title="Texto en negrita"><img src="/img/silk/text_bold" alt="Texto en negrita" /></button>
                <button type="button" onclick="insertTags(actual['a1_'], '//','//','Texto en cursiva')" title="Texto en cursiva"><img src="/img/silk/text_italic" alt="Texto en cursiva" /></button>
                <button type="button" onclick="insertTags(actual['a1_'], '__','__','Texto subrayado')" title="Texto subrayado"><img src="/img/silk/text_underline" alt="Texto subrayado" /></button>
                <button type="button" onclick="insertarEnlace(actual['a1_'])" title="Enlace externo"><img src="/img/silk/link" alt="Enlace externo" /></button>
                <select onchange="highlightSource(this);" title="Resaltado de código">
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
             </div>
             <textarea name="Comentario[comentario]" id="comentario_comentario" rows="10" cols="60" <?php echo $field_class['website']; ?>><?php echo $comentario->message; ?></textarea>
        </li>
    </ul>
	<div class="auth_buttons">
        <ul>
            <li>
                <button type="submit" name="Auth[type]" value="openid" id="auth_type_openid"><img src="/img/icons/openid" alt="[ícono]" /> OpenID</button> <span id="auth_openid_span"><label for="auth_openid_identifier">URL:</label> <input type="text" name="Auth[openid_identifier]" id="auth_openid_identifier" value="" /></span>
            </li>
            <li>
                <button type="submit" name="Auth[type]" value="persona" class="persona" id="auth_type_persona"><img src="/img/icons/persona" alt="[ícono]" /> Persona (Requiere JavaScript)</button><input type="hidden" name="Auth[persona_assertion]" id="auth_persona_assertion" />
            </li>
            <li>
                <button type="submit" name="Auth[type]" value="twitter" class="twitter"><img src="/img/icons/b2_btn_icon" alt="[ícono]" /> Twitter</button>
            </li>
            <li>
                <button type="submit" name="Auth[type]" value="linkedin"><img src="/img/favicons/linkedin_com" alt="[ícono]" /> LinkedIn</button>
            </li>
            <li>
                <button type="submit" name="Auth[type]" value="ninguno">Ninguno</button>
            </li>
        </ul>
	</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton('Previsualizar'); ?>
	</div>
</form>

