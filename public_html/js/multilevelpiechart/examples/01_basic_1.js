function drawchart() {
    var chart = new MultiLevelPieChart();
    chart.viewBox = '-400 -400 800 800';
    chart.tooltip.textFormat = function(sector) {

        var size = sector.value;
        if(size > 0) {
            var base = Math.log(size) / Math.log(1024);
            var suffixes = ['bytes', 'KiB', 'MiB', 'GiB', 'TiB'];
            size = Math.pow(1024, base - Math.floor(base));
            //        size = Math.round(Math.pow(1024, base - Math.floor(base)), 1);
            size = (Math.round(size * 10) / 10);
            size += ' '+ suffixes[Math.ceil(base)];
        }
        return sector.label+'\n'+size+'\n'+sector.percent+'%';
    };

    var root = chart.root;
    
    root.label = '/';
    root.value = 282167796;
    root.color = '#d4d7d1';

//    var root = chart.createSector({label: 'Raíz', value: 200});
//    chart.appendChild(root);

    var home = root.appendChild(chart.createSector({label: 'home', value: 269191104, color: '#f02828'}));
    var usr = root.appendChild(chart.createSector({label: 'usr', value: 8883640, color: '#f4512f'}));
    var lib = root.appendChild(chart.createSector({label: 'lib', value: 1827992, color: '#f4512f'}));
    var _var = root.appendChild(chart.createSector({label: 'var', value: 1437900, color: '#f4512f'}));

    var user1 = home.appendChild(chart.createSector({label: 'pablo', value: 264083216, color: '#e72727'}));
    
    var f1 = user1.appendChild(chart.createSector({label: 'Videos', value: 140754372, color: '#de2525'}));
    var f2 = user1.appendChild(chart.createSector({label: 'Escritorio', value: 20894060, color: '#7fd230'}));
    var f3 = user1.appendChild(chart.createSector({label: 'Documentos', value: 17219716, color: '#a3c345'}));
    var f4 = user1.appendChild(chart.createSector({label: '.torrents', value: 1, color: '#c3b559'}));
    var f5 = user1.appendChild(chart.createSector({label: 'Photos', value: 10301584, color: '#d9ab62'}));
    var f6 = user1.appendChild(chart.createSector({label: 'iso', value: 9816352, color: '#e1a64f'}));
    
    var f1_1 = f1.appendChild(chart.createSector({label: 'Películas', value: 73926472, color: '#d52424'}));
    var f1_2 = f1.appendChild(chart.createSector({label: 'Series', value: 61657196, color: '#7c80aa'}));

    f1_1.appendChild(chart.createSector({label: 'Infantiles', value: 4505424, color: '#cc2222'}));
    f1_1.appendChild(chart.createSector({label: 'The Blind Side', value: 1646824, color: '#c7292d'}));
    f1_1.appendChild(chart.createSector({label: 'Underworld', value: 1436632, color: '#c52c31'}));
    f1_1.appendChild(chart.createSector({label: 'Some_Like_it_Hot', value: 1430676, color: '#c32e34'}));

    var f1_2_1 = f1_2.appendChild(chart.createSector({label: 'Star_Trek-TOS', value: 29775892, color: '#777aa3'}));
        f1_2_1.appendChild(chart.createSector({label: 'S1', value: 11287812, color: '#72759c'}));
        f1_2_1.appendChild(chart.createSector({label: 'S2', value: 9314756, color: '#667ba4'}));
        f1_2_1.appendChild(chart.createSector({label: 'S3', value: 9173320, color: '#5d81a9'}));

    var f1_2_2 = f1_2.appendChild(chart.createSector({label: 'The_IT_crowd', value: 4821220, color: '#777aa3'}));
        f1_2_2.appendChild(chart.createSector({label: 'S03', value: 1410838, color: '#777aa3'}));
        f1_2_2.appendChild(chart.createSector({label: 'S03', value: 1410839, color: '#777aa3'}));
//        f1_2_2.appendChild(chart.createSector({label: 'S03', value: 1425388, color: '#777aa3'}));
//        f1_2_2.appendChild(chart.createSector({label: 'S01', value: 1072760, color: '#777aa3'}));
//        f1_2_2.appendChild(chart.createSector({label: 'S02', value: 1072080, color: '#777aa3'}));
//        f1_2_2.appendChild(chart.createSector({label: 'S04', value: 1071668, color: '#777aa3'}));
    
    var f2_1 = f2.appendChild(chart.createSector({label: 'basura', value: 20350548, color: '#7ac92e'}));
    
    var f3_1 = f3.appendChild(chart.createSector({label: 'DCIM', value: 5086588, color: '#9cbb42'}));
               f3_1.appendChild(chart.createSector({label: '101APPLE', value: 3531852, color: '#7ac92e'}));
               f3_1.appendChild(chart.createSector({label: '102APPLE', value: 307628, color: '#7ac92e'}));
               f3_1.appendChild(chart.createSector({label: '100APPLE', value: 1247100, color: '#7ac92e'}));
    f3.appendChild(chart.createSector({label: 'songbook', value: 3982948, color: '#a6b748'}));
    f3.appendChild(chart.createSector({label: 'Comics', value: 3472876, color: '#adb44c'}));
    
    var f5_1 = f5.appendChild(chart.createSector({label: '2007', value: 1138588, color: '#d52424'}));
    var f5_2 = f5.appendChild(chart.createSector({label: '2008', value: 1138588, color: '#d52424'}));
    var f5_3 = f5.appendChild(chart.createSector({label: '2010', value: 1138588, color: '#d52424'}));
    var f5_4 = f5.appendChild(chart.createSector({label: '2009', value: 1138588, color: '#d52424'}));
    var f5_5 = f5.appendChild(chart.createSector({label: '2006', value: 1138588, color: '#d52424'}));
    var f5_6 = f5.appendChild(chart.createSector({label: '2011', value: 1138588, color: '#d52424'}));
    
    var share = usr.appendChild(chart.createSector({label: 'share', value: 4246528, color: '#eb4e2e'}));
    share.appendChild(chart.createSector({label: 'games', value: 1442720, color: '#e24b2c'}));
    
    chart.draw('contenedor');
    global.chart = chart;
}
