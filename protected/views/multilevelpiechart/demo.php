<?php

$this->pageTitle = 'Multi-level pie chart';

// <style type="text/css">
// </style>

//Yii::app()->clientScript->registerCssFile('http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css');
//Yii::app()->clientScript->registerCssFile('http://labs.abeautifulsite.net/jquery-miniColors/jquery.miniColors.css');
Yii::app()->clientScript->registerCssFile('/css/jquery.miniColors.css');

Yii::app()->clientScript->registerCss('contenedor', "
#contenedor {
    width: 400px;
}

.placeholder {
    outline: 1px dashed #4183C4;
    /*-webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    margin: -1px;*/
}

.mjs-nestedSortable-error {
    background: #fbe3e4;
    border-color: transparent;
}

ol {
    margin: 0;
    padding: 0;
    padding-left: 30px;
}

ol.sortable, ol.sortable ol {
    margin: 0 0 0 25px;
    padding: 0;
    list-style-type: none;
}

ol.sortable {
    margin: 4em 0;
}

.sortable li {
    margin: 5px 0 0 0;
    padding: 0;
}

.sortable li div  {
    border: 1px solid #d4d4d4;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    border-color: #D4D4D4 #D4D4D4 #BCBCBC;
    padding: 6px;
    margin: 0;
    cursor: move;
    background: #f6f6f6;
    background: -moz-linear-gradient(top,  #ffffff 0%, #f6f6f6 47%, #ededed 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ffffff), color-stop(47%,#f6f6f6), color-stop(100%,#ededed));
    background: -webkit-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
    background: -o-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
    background: -ms-linear-gradient(top,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
    background: linear-gradient(to bottom,  #ffffff 0%,#f6f6f6 47%,#ededed 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#ededed',GradientType=0 );
}

");

// <script type="text/javascript">
///* <![CDATA[ */
///* ]]> */
// </script>

//Yii::app()->clientScript->registerScriptFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js');
Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/jquery-1.8.3.js');
Yii::app()->clientScript->registerScriptFile('http://code.jquery.com/ui/1.9.2/jquery-ui.js');

//Yii::app()->clientScript->registerScriptFile('http://labs.abeautifulsite.net/jquery-miniColors/jquery.miniColors.js');
Yii::app()->clientScript->registerScriptFile('/js/jquery.miniColors.min.js');


//Yii::app()->clientScript->registerScriptFile('http://mjsarfatti.com/sandbox/nestedSortable/jquery.ui.touch-punch.js');
Yii::app()->clientScript->registerScriptFile('http://mjsarfatti.com/sandbox/nestedSortable/jquery.mjs.nestedSortable.js');

Yii::app()->clientScript->registerScriptFile('/js/multilevelpiechart/multilevelpiechart.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('chartdata',"

$(function() {

    $('.sortable').nestedSortable({
        handle: 'div',
        items: 'li',
        toleranceElement: '> div',
        placeholder: 'placeholder',
        
        forcePlaceholderSize: true,
        helper:	'clone',
        opacity: .6,
        revert: 250,
        tabSize: 25,
        tolerance: 'pointer',
        //maxLevels: 3,
        //isTree: true,
        expandOnHover: 700
    });

    $('.color').miniColors();
    
    actualizar(document.forms[0]);
    
    $('.help').mouseover(function() {
        var position = $(this).position();
        // var parentWidth = 334;
        var tooltip = $(this).children('.tooltip');
        var parentWidth = ($(this).parent().width() - tooltip.width());

        if(position.left > parentWidth) {
            tooltip.css('left', (parentWidth - position.left)+'px');
        }
    });
});

function addSectors(parent, ol) {
    var input;
    var sector;
    var children;
    var label_val, value_el, value_val, color_val;
    ol.each(function(index, el) {
        input = $(el).find('div:first input');
        label_val = input.first().val();
        value_el = input.next();
        value_val = parseInt(value_el.val(), 10);
        color_val = value_el.next().val();
        sector = parent.appendChild(global.chart.createSector({label: label_val, value: value_val, color: color_val}));
        children = $(el).find('ol:first > li');
        if(children.length) {
            addSectors(sector, children);
        }
    });
}

function actualizar(form) {
    var chart = new MultiLevelPieChart();
    global.chart = chart;
    var input = $('#root_conf input');
    chart.root.label = input.first().val();
    var value_el = input.next();
    chart.root.value = parseInt(value_el.val(), 10);
    chart.root.color = value_el.next().val();
    chart.tooltip.textFormat = form.elements.namedItem('tooltip.textFormat').value;

    addSectors(chart.root, $('#sectors_conf > li'));

    $('#contenedor').html('');
    chart.draw('contenedor');
    return false;
}

", CClientScript::POS_HEAD);

?>

<h1>Demo</h1>

<div>
    <form action="" method="get" onsubmit="return actualizar(this)">

        <div>
            <textarea name="tooltip.textFormat" rows="2" cols="25">{label} {value}
{percent}%</textarea><div class="help"><div class="tooltip">
            <h4>Variables disponibles</h4>
            <dl>
              <dt>label <var>%{label}</var></dt>
              <dd></dd>
              <dt>value <var>%{value}</var></dt>
              <dd></dd>
              <dt>percent <var>%{percent}</var></dt>
              <dd></dd>
            </dl></div></div>
        </div>
        <div id="root_conf"><input type="text" name="label[]" value="Raíz" /> <input type="text" name="value" value="200" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#cccccc" size="6" /></div>        
        
        <ol id="sectors_conf" class="sortable">
            <li>
                <div><input type="text" name="label[]" value="XML" /> <input type="text" name="value[]" value="60" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#ff0000" size="6" /></div>
                <ol>
                    <li>
                        <div><input type="text" name="label[]" value="SVG" /> <input type="text" name="value[]" value="25" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#0000ff" size="6" /></div>
                        <ol>
                            <li id="list_14">
                                <div><input type="text" name="label[]" value="DocBook5" /> <input type="text" name="value[]" value="15" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#00ff00" size="6" /></div>
                            </li>
                            <li id="list_15">
                                <div><input type="text" name="label[]" value="DocBook4" /> <input type="text" name="value[]" value="5" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#ff9999" size="6" /></div>
                            </li>
                        </ol>
                    </li>
                    <li id="list_16">
                        <div><input type="text" name="label[]" value="DocBook" /> <input type="text" name="value[]" value="25" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#ff5555" size="6" /></div>
                    </li>
                </ol>
            </li>
            <li id="list_17">
                <div><input type="text" name="label[]" value="PHP" /> <input type="text" name="value" value="30" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#ffaaaa" size="6" /></div>
            </li>
            <li id="list_18">
                <div><input type="text" name="label[]" value="CSS" /> <input type="text" name="value" value="50" size="4" class="numeric" /> <input type="text" name="color[]" class="color" value="#aaffaa" size="6" /></div>
            </li>
        </ol>

        <div>
            <input type="submit" value="Actualizar" />
        </div>
    </form>
</div>
<div id="contenedor"></div>
    