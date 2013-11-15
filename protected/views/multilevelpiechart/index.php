<?php

$this->pageTitle = 'Multi-level pie chart';

// <style type="text/css">
// </style>

Yii::app()->clientScript->registerCss('contenedor', "
#contenedor {
    width: 400px;
}
");

// <script type="text/javascript">
///* <![CDATA[ */
///* ]]> */
// </script>

Yii::app()->clientScript->registerScriptFile('/js/multilevelpiechart/multilevelpiechart.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScript('chartdata',"

function drawchart() {
    var chart = new MultiLevelPieChart();
    chart.root.label = 'Raíz';
    chart.root.value = 200;
    chart.tooltip.textFormat = '{label} {value}\\n'+
    	'{percent}%';

    var xml = chart.root.appendChild(chart.createSector({label: 'XML', value: 60}));//, color: '#ff0000'
    var php = chart.root.appendChild(chart.createSector({label: 'PHP', value: 30}));//, color: '#0000ff'
    var css = chart.root.appendChild(chart.createSector({label: 'CSS', value: 50}));//, color: '#00ff00'

    var svg = xml.appendChild(chart.createSector({label: 'SVG', value: 25}));//, color: '#FF9999'
    var docbook = xml.appendChild(chart.createSector({label: 'DocBook', value: 25}));//, color: '#FF5555'

    var docbook5 = docbook.appendChild(chart.createSector({label: 'DocBook5', value: 15}));//, color: '#FFAAAA'
    var docbook4 = docbook.appendChild(chart.createSector({label: 'DocBook4', value: 5}));//, color: '#FFAAAA'

    chart.draw('contenedor');
    //console.log(chart);
    global.chart = chart;
}

window.addEventListener('load', drawchart, false);

", CClientScript::POS_HEAD);

// Yii::app()->clientScript->registerScript('drawchart',"
//
//drawchart();
//
//", CClientScript::POS_LOAD);//READY);
 
?>

<h1><?php echo $this->pageTitle; ?></h1>
<h2>Demo</h2>
<p><a href="/multilevelpiechart/demo">Demo</a></p>


<div id="contenedor"></div>
