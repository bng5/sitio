<h1>Convirtiendo wikitexto a instrucciones</h1>

<?php


define('DOKU_INC', '/home/pablo/public_html/bng5.net/public_html/protected/extensions/doku/');
define('DOKU_URL', 'http://localhost/~todos/doku2/');
define('DOKU_BASE', '/~todos/doku2/');
define('DOKU_SCRIPT','doku.php');

define('RFC2822_ATEXT',"0-9a-zA-Z!#$%&'*+/=?^_`{|}~-");
define('PREG_PATTERN_VALID_EMAIL', '['.RFC2822_ATEXT.']+(?:\.['.RFC2822_ATEXT.']+)*@(?i:[0-9a-z][0-9a-z-]*\.)+(?i:[a-z]{2,4}|museum|travel)');

require(DOKU_INC.'acronimos.php');
require(DOKU_INC.'dokuwiki.php');

require(DOKU_INC.'inc/parser/handler.php');
require(DOKU_INC.'inc/parser/lexer.php');
require(DOKU_INC.'inc/parser/parser.php');

require_once(DOKU_INC.'inc/parser/xhtml.php');

require(DOKU_INC.'inc/events.php');

Yii::app()->params->doku_event_handler = new Doku_Event_Handler();

require(DOKU_INC.'inc/geshi.php');

require_once DOKU_INC.'inc/common.php';
require_once DOKU_INC.'inc/confutils.php';
require_once DOKU_INC.'inc/pageutils.php';
require_once DOKU_INC.'inc/parserutils.php';
require_once DOKU_INC.'inc/infoutils.php';
require_once DOKU_INC.'inc/io.php';
require_once DOKU_INC.'inc/utf8.php';
//require_once DOKU_INC.'inc/auth.php';

require_once(DOKU_INC.'inc/Input.class.php');
$INPUT = new Input();

// Create the parser
global $Parser;
$Parser = new Doku_Parser();


class Handler extends Doku_Handler {
    function header($match, $state, $pos) {
        // get level and title
        $title = trim($match);
        $level = strspn($title,'=');
        if($level < 1)
            $level = 1;
        if($level > 6)
            $level = 6;
        $title = trim($title,'= ');

        if ($this->status['section'])
            $this->_addCall('section_close',array(),$pos);

        $this->_addCall('header',array($title,$level,$pos), $pos);

        $this->_addCall('section_open',array($level),$pos);
        $this->status['section'] = true;
        return true;
    }
}

 



class Parser_Mode_Header extends Doku_Parser_Mode {
    function connectTo($mode) {
        //we're not picky about the closing ones, two are enough
        $this->Lexer->addSpecialPattern(
                            '[ \t]*={3,}[^\n]+={3,}[ \t]*(?=\n)',
                            $mode,
                            'header'
                        );
    }

    function getSort() {
        return 50;
    }
}



 





foreach($post->comments AS $comment) {
    echo '<hr />';
    echo $comment->texto;
    $Parser->Handler = new Handler();
    $Parser->addMode('listblock',new Doku_Parser_Mode_ListBlock());
    $Parser->addMode('preformatted',new Doku_Parser_Mode_Preformatted());
    $Parser->addMode('header',new Parser_Mode_Header());

    $Parser->addMode('strong', new Doku_Parser_Mode_Formatting('strong'));
    $Parser->addMode('emphasis', new Doku_Parser_Mode_Formatting('emphasis'));
    $Parser->addMode('underline', new Doku_Parser_Mode_Formatting('underline'));
    $Parser->addMode('monospace', new Doku_Parser_Mode_Formatting('monospace'));
    $Parser->addMode('subscript', new Doku_Parser_Mode_Formatting('subscript'));
    $Parser->addMode('superscript', new Doku_Parser_Mode_Formatting('superscript'));
    $Parser->addMode('deleted', new Doku_Parser_Mode_Formatting('deleted'));

    $Parser->addMode('linebreak',new Doku_Parser_Mode_Linebreak());

    $Parser->addMode('unformatted',new Doku_Parser_Mode_Unformatted());
    $Parser->addMode('code',new Doku_Parser_Mode_Code());
    $Parser->addMode('quote',new Doku_Parser_Mode_Quote());

    $Parser->addMode('smiley',new Doku_Parser_Mode_Smiley(array_keys(_getSmileys())));

    $Parser->addMode('multiplyentity',new Doku_Parser_Mode_MultiplyEntity());
    $Parser->addMode('quotes',new Doku_Parser_Mode_Quotes());

    $Parser->addMode('internallink',new Doku_Parser_Mode_InternalLink());
    $Parser->addMode('media',new Doku_Parser_Mode_Media());
    $Parser->addMode('externallink',new Doku_Parser_Mode_ExternalLink());
    $Parser->addMode('emaillink',new Doku_Parser_Mode_EmailLink());
    $Parser->addMode('eol',new Doku_Parser_Mode_Eol());
    $instructions = $Parser->parse($comment->texto);
    echo '<pre>';
    print_r($instructions);
    echo '</pre>';





    $comment->instrucciones = serialize($instructions);
    
    
    
    $Renderer = new Doku_Renderer_XHTML();
    //$Renderer->smileys = _getSmileys();
    foreach($instructions as $instruction) {
        call_user_func_array(array(&$Renderer, $instruction[0]),$instruction[1]);
    }
    $comment->html = $Renderer->doc;
    
    $comment->save();

}

?>
    