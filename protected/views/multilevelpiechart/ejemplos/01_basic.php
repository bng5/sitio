<?php

ob_start();
include('js/multilevelpiechart/examples/01_basic.js');
$source = ob_get_clean();

Yii::app()->clientScript->registerScript('drawchart',"

{$source}

//});
", CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('init',"
//$(function() {

drawchart();

//});
", CClientScript::POS_READY);


/******************************************************************************/

//.
///home/bng5/public_html/bng5.net/public_html/protected/components
///home/bng5/public_html/bng5.net/public_html/protected/models
///usr/lib/php
///usr/local/lib/php_egg_logo_guid()

if (!@include 'geshi.php') {
//    echo get_include_path();
//    die('Could not find geshi.php - make sure it is in your include path!');
}

    
$geshi = new GeSHi($source, 'javascript');


$geshi->set_link_target('_blank');
    
// Use the PRE header. This means less output source since we don't have to output &nbsp;
// everywhere. Of course it also means you can't set the tab width.
$geshi->set_header_type(GESHI_HEADER_DIV);//GESHI_HEADER_NONE);//GESHI_HEADER_PRE);//
    
// Enable CSS classes. You can use get_stylesheet() to output a stylesheet for your code. Using
// CSS classes results in much less output source.
$geshi->enable_classes();
    
// Enable line numbers. We want fancy line numbers, and we want every 5th line number to be fancy
//$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);//GESHI_NO_LINE_NUMBERS);//GESHI_FANCY_LINE_NUMBERS, 6);//
//$geshi->start_line_numbers_at(50);
// Set the style for the PRE around the code. The line numbers are contained within this box (not
// XHTML compliant btw, but if you are liberally minded about these things then you'll appreciate
// the reduced source output).
//$geshi->set_overall_style('color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;', true);

// Set the style for line numbers. In order to get style for line numbers working, the <li> element
// is being styled. This means that the code on the line will also be styled, and most of the time
// you don't want this. So the set_code_style reverts styles for the line (by using a <div> on the line).
// So the source output looks like this:
//
// <pre style="[set_overall_style styles]"><ol>
// <li style="[set_line_style styles]"><div style="[set_code_style styles]>...</div></li>
// ...
// </ol></pre>
//$geshi->set_line_style('font-family:\'Courier New\', Courier, monospace; color: #003030;', 'font-weight: bold; color: #006060;', true);
//$geshi->set_code_style('color: #000020;', 'color: #000020;');

// Styles for hyperlinks in the code. GESHI_LINK for default styles, GESHI_HOVER for hover style etc...
// note that classes must be enabled for this to work.
$geshi->set_link_styles(GESHI_LINK, 'color: #000060;');
$geshi->set_link_styles(GESHI_HOVER, 'background-color: #f0f000;');

// Use the header/footer functionality. This puts a div with content within the PRE element, so it is
// affected by the styles set by set_overall_style. So if the PRE has a border then the header/footer will
// appear inside it.
//$geshi->set_header_content('GeSHi &copy; 2004, Nigel McNie. View source of example.php for example of using GeSHi');
//$geshi->set_header_content_style('font-family: Verdana, Arial, sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-bottom: 1px solid #d0d0d0; padding: 2px;');

// You can use <TIME> and <VERSION> as placeholders
//$geshi->set_footer_content('Parsed in <TIME> seconds,  using GeSHi <VERSION>');
//$geshi->set_footer_content_style('font-family: Verdana, Arial, sans-serif; color: #808080; font-size: 70%; font-weight: bold; background-color: #f0f0ff; border-top: 1px solid #d0d0d0; padding: 2px;');


Yii::app()->clientScript->registerCss('geshi', "

// Output the stylesheet. Note it doesn't output the <style> tag
".$geshi->get_stylesheet()."

.php {
	font-family: monospace;
	font-size:12px;
	border: 1px solid #d0d0d0;
	background-color: #f0f0f0;
}
.php .de1, .php .de2 {font-weight: normal;color: #000066;}
.php  {font-family: monospace;color: #000066; border: 1px solid #d0d0d0; background-color: #f0f0f0;}
.php a:link {color: #000060;}
.php a:hover {background-color: #f0f000;}
.php .imp {font-weight: bold; color: red;}
.php li {font-family: monospace; color:#BBBBBB; font-weight: normal; font-style: normal;}
.php .kw1 {color: #b1b100;}
.php .kw2 {color: #000000; font-weight: bold;}
.php .kw3 {color: #000066;}
.php .co1 {color: #808080; font-style: italic;}
.php .co2 {color: #808080; font-style: italic;}
.php .coMULTI {color: #808080; font-style: italic;}
.php .es0 {color: #000099; font-weight: bold;}
.php .br0 {color: #66cc66;}
.php .st0 {color: #ff0000;}
.php .nu0 {color: #cc66cc;}
.php .me1 {color: #006600;}
.php .me2 {color: #006600;}
.php .re0 {color: #0000ff;}
.php .re1 {color: #ff0000}
");

echo $geshi->parse_code();



/******************************************************************************/



?>







<div id="contenedor">
    
</div>
