function drawchart() {
    var chart = new MultiLevelPieChart();
    chart.tooltip.textFormat = '{label} {value}\n'+
    	'{percent}%';

    var root = chart.root;
    
    root.label = 'Raíz';
    root.value = 200;

//    var root = chart.createSector({label: 'Raíz', value: 200});
//    chart.appendChild(root);

    var xml = root.appendChild(chart.createSector({label: 'XML', value: 60}));
    var php = root.appendChild(chart.createSector({label: 'PHP', value: 30}));
    var css = root.appendChild(chart.createSector({label: 'CSS', value: 50}));

    var svg = xml.appendChild(chart.createSector({label: 'SVG', value: 25}));
    var docbook = xml.appendChild(chart.createSector({label: 'DocBook', value: 25}));

    var docbook5 = docbook.appendChild(chart.createSector({label: 'DocBook5', value: 15}));
    var docbook4 = docbook.appendChild(chart.createSector({label: 'DocBook4', value: 5}));

    chart.draw('contenedor');
}
