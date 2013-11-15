<?php

/**
 * Description of BlikiRenderer
 *
 * @author pablo
 */
class BlikiRenderer {
    
    public $doc = '';
    public $admonitions = array(
        'caution' => 'Precaución',
        'tip' => 'Consejo',
        'note' => 'Nota',
        'important' => 'Importante',
        'warning' => 'Advertencia',
    );
    
    public $positions = array(
        'head' => CClientScript::POS_HEAD,
        'load' => CClientScript::POS_LOAD,
        'ready' => CClientScript::POS_READY,
    );
    
    public function append(array $instruction) {
        $method = array_shift($instruction);
        if(method_exists($this, $method)) {
            $this->{$method}($instruction);
        }
        else {
            throw new Exception("No existe el método {$method}");
        }
    }

    public function header($instruction) {
        $this->doc .= "<h{$instruction['level']} id=\"{$instruction['name']}\">{$instruction['value']} <a href=\"#{$instruction['name']}\" class=\"permalink\">&#182;</a></h{$instruction['level']}>";
    }

    public function element_start($instruction) {
        $this->doc .= "<{$instruction['element']}";
        if(array_key_exists('attrs', $instruction) && is_array($instruction['attrs'])) {
            foreach($instruction['attrs'] AS $k => $v) {
                $this->doc .= " {$k}=\"{$v}\"";
            }
        }
        $this->doc .= ">";
    }
    
    public function element_end($instruction) {
        $this->doc .= "</{$instruction['element']}>";
    }
    
    public function text($instruction) {
        $this->doc .= $instruction['value'];
    }
    
    public function source($instruction) {
        if(array_key_exists('href', $instruction) && file_exists(trim($instruction['href'], '/ '))) {
            $link = true;
            $filename = basename($instruction['href']);
            $lang = pathinfo($instruction['href'], PATHINFO_EXTENSION);
            $instruction['value'] = file_get_contents(trim($instruction['href'], '/ '));
        }
        else {
            $link = false;
            $lang = $instruction['lang'];
        }
        $geshi = new GeSHi(preg_replace('/^\n/', '', $instruction['value']), $lang);
        
        // Use the PRE_VALID header. This means less output source since we don't have to output &nbsp;
        // everywhere. Of course it also means you can't set the tab width.
        // HEADER_PRE_VALID puts the <pre> tag inside the list items (<li>) thus producing valid HTML markup.
        // HEADER_PRE puts the <pre> tag around the list (<ol>) which is invalid in HTML 4 and XHTML 1
        // HEADER_DIV puts a <div> tag arount the list (valid!) but needs to replace whitespaces with &nbsp
        //            thus producing much larger overhead. You can set the tab width though.
        $geshi->set_header_type(GESHI_HEADER_PRE);

        // Enable CSS classes. You can use get_stylesheet() to output a stylesheet for your code. Using
        // CSS classes results in much less output source.
        $geshi->enable_classes();

        // Enable line numbers. We want fancy line numbers, and we want every 5th line number to be fancy
        if($instruction['linenumbers'] == 'true') {
            $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
        }

        // Set the style for the PRE around the code. The line numbers are contained within this box (not
        // XHTML compliant btw, but if you are liberally minded about these things then you'll appreciate
        // the reduced source output).
//        $geshi->set_overall_style('font: normal normal 90% monospace; color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', false);

        // Set the style for line numbers. In order to get style for line numbers working, the <li> element
        // is being styled. This means that the code on the line will also be styled, and most of the time
        // you don't want this. So the set_code_style reverts styles for the line (by using a <div> on the line).
        // So the source output looks like this:
        //
        // <pre style="[set_overall_style styles]"><ol>
        // <li style="[set_line_style styles]"><div style="[set_code_style styles]>...</div></li>
        // ...
        // </ol></pre>
//        $geshi->set_line_style('color: #003030;', 'font-weight: bold; color: #006060;', true);
//        $geshi->set_code_style('color: #000020;', true);

        // Styles for hyperlinks in the code. GESHI_LINK for default styles, GESHI_HOVER for hover style etc...
        // note that classes must be enabled for this to work.
        $geshi->set_link_styles(GESHI_LINK, 'color: #000060;');
        $geshi->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');

        // Use the header/footer functionality. This puts a div with content within the PRE element, so it is
        // affected by the styles set by set_overall_style. So if the PRE has a border then the header/footer will
        // appear inside it.
//        $geshi->set_header_content('<SPEED> <TIME>');
//        $geshi->set_header_content_style('font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;');

        // You can use <TIME> and <VERSION> as placeholders
//        $geshi->set_footer_content('Parsed in <TIME> seconds at <SPEED>, using GeSHi <VERSION>');
//        $geshi->set_footer_content_style('font-family: sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-top: 1px solid #d0d0d0; padding: 2px;');

        $this->style(array('value' => $geshi->get_stylesheet(true)), $lang);
        $this->doc .= '
            <dl class="source '.$lang.'">
                '.($link ? "<dt><a href=\"{$instruction['href']}\">{$filename}</a></dt>" : "<dt>{$lang}</dt>").'
                <dd>
                    '.$geshi->parse_code().'
                </dd>
            </dl>';
    }
    
    public function script($instruction) {
        Yii::app()->clientScript->registerScript($instruction['pos'], $instruction['value'], $this->positions[$instruction['pos']]);
    }
    
    public function style($instruction, $estilo = 'estilo') {
        Yii::app()->clientScript->registerCss($estilo, $instruction['value']);
    }
    
    public function admonition($instruction) {
//echo "
//<h3>".__METHOD__."</h3>";
//var_dump($instruction);
        $title = (array_key_exists('title', $instruction) && $instruction['title']) ? 
            $instruction['title'] : 
            $this->admonitions[$instruction['type']];
        $this->doc .= '
        <div class="aviso '.$instruction['type'].'">
            <h3>'.$title.'</h3>';
        foreach($instruction['value'] AS $p) {
            $this->doc .= '
            <p>'.str_replace("\n", "<br />\n", $p).'</p>';
        }
        $this->doc .= '
        </div>';
    }
    
    public function __toString() {
        return $this->doc;
    }
}
