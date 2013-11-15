
(function($) {
    $.fn.timeline = function() {
        var pattern = /(from|until) date_(\d+)-(\d+)-(\d+)/;
        var lapsos = [];
        var list = this.find('li');
        this.addClass('hidden');
        var lastDate;
        var fromDate;
        var dataColor;
        for(var i = (list.length - 1); i >= 0; i--) {
            li = list.eq(i);

            from = li.find('.from').attr('class').match(pattern);
            fromDate = new Date(parseInt(from[2]), (parseInt(from[3]) -1), parseInt(from[4]), 0, 0, 0, 0);
            if(lastDate && MonthDifference(lastDate, fromDate)) {
                item = {};
                item.a = null;
                item.startDate = lastDate;
                item.until = fromDate;
                item.b = MonthDifference(lastDate, item.until);
                item.color = null;
                lapsos.push(item); 
            }
            item = {};
            item.a = li.find('em').text();
            item.id = li.attr('id');
            item.startDate = fromDate;
            until = li.find('.until').attr('class').match(pattern);
            item.until = new Date(parseInt(until[2]), (parseInt(until[3], 10) -1), parseInt(until[4], 10), 0, 0, 0, 0);
            lastDate = item.until;
            item.b = MonthDifference(item.startDate, item.until);
            dataColor = li.attr('class').match(/^color_([0-9a-fA-F]{6})$/);
            item.color = dataColor[1];
            lapsos.push(item);
        }
        var startDate = lapsos[0].startDate;
        var total = (MonthDifference(startDate, new Date()) + 1);
        total = (100 / total);

        //var timeline = this.before($('<div id="timeline" style="height: 30px; overflow: hidden; white-space: nowrap;"></div>'));
        var timeline = document.createElement('div');
        timeline.id = "timeline";
        timeline.style.height = '30px';
        timeline.style.overflow = 'hidden';
        timeline.style.whiteSpace = 'nowrap';

        var lapso;
        var meses;

        for(i = 0; i < lapsos.length; i++) {
            lapso = timeline.appendChild(document.createElement('div'));

            meses = MonthDifference(lapsos[i].startDate, lapsos[i].until);
            //console.log(daysDifference(lapsos[i].startDate, lapsos[i].until)+' días');
            //lapso.setAttribute('style', 'width: '+(lapsos[i][1] * 10)+'px;height: 20px;background-color: '+randomColor()+';display: inline-block;');
            //        lapso.style.width = (lapsos[i][1] * 10)+'px';
            lapso.style.width = (meses * total)+'%';
            lapso.style.height = '100%';
            if(lapsos[i].a) {
                lapso.setAttribute('title', lapsos[i].a);
                lapso.className = 'selectable';
                lapso.style.backgroundColor = '#'+lapsos[i].color;//randomColor();
                lapso.setAttribute('data-info', lapsos[i].id);
            }
            lapso.style.display = 'inline-block';
        }
        
        timeline = $(timeline);
        this.parent().before(timeline);
        timeline.on('click', '.selectable', function() {
            list.parent().children('.visible').removeClass('visible');
            $('#'+$(this).data('info')).addClass('visible');
        });

        var years = $('<ul class="years"></ul>');
        var today = new Date();
        var currentYear = today.getFullYear();
        var year = startDate.getFullYear();
//        var width = (Math.round((12 * total) * 1000) / 1000)+'%';
        var width = (12 * total);
        var fullWidth = 0;
        do {
            li = $('<li><span>'+year+'</span></li>');
            if(year == currentYear) {
                width = (100 - fullWidth);
            }
            fullWidth += width;
            li.css('width', width+'%');
            years.append(li);
            year++
        }while(year <= currentYear);
        var div_years = $('<div></div>');
        div_years.append(years);
        this.parent().before(div_years);
    };
})(jQuery);



function randomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for(var i = 0; i < 6; i++) {
        color += letters[Math.round(Math.random() * 15)];
    }
    return color;
}

function daysDifference(startDate, endDate) {
    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24;

    // Convert both dates to milliseconds
    var date1_ms = startDate.getTime();
    var date2_ms = endDate.getTime();

    var difference_ms = Math.abs(date1_ms - date2_ms)
    
    return Math.round(difference_ms/ONE_DAY)
//return (startDate.getMonth() - endDate.getMonth()) + 12 * (startDate.getFullYear() - endDate.getFullYear());
}

function MonthDifference(endDate, startDate) {
    return ((startDate.getMonth() - endDate.getMonth()) + 12 * (startDate.getFullYear() - endDate.getFullYear()));
}

$(function() {
    return;
    
    var list = $('#lista').find('li');
    var li, from, until, item;
    var pattern = /(from|until) date_(\d+)-(\d+)-(\d+)/;
    var lapsos = [];
    var startDate;
    for(var i = 0; i < list.length; i++) {
        li = list.eq(i);
        item = {};
        item.a = li.find('em').text();
        from = li.find('.from').attr('class').match(pattern);
        until = li.find('.until').attr('class').match(pattern);
        item.startDate = new Date(parseInt(from[2]), (parseInt(from[3]) -1), parseInt(from[4]), 0, 0, 0, 0);
        item.until = new Date(parseInt(until[2]), (parseInt(until[3]) -1), parseInt(until[4]), 0, 0, 0, 0);
        item.b = MonthDifference(item.startDate, item.until);
        lapsos.unshift(item); 
    }

    var total = MonthDifference(lapsos[0].startDate, new Date());

    total = (100 / total);
    var date;
    var timeline = document.getElementById('timeline');
    var offsetWidth = timeline.offsetWidth;
    var lapso;
    var meses;
    for(i = 0; i < lapsos.length; i++) {
        lapso = timeline.appendChild(document.createElement('div'));
        
        date = lapsos[i].until;
        
        meses = MonthDifference(lapsos[i].startDate, lapsos[i].until);
        //console.log(daysDifference(lapsos[i].startDate, lapsos[i].until)+' días');
        lapso.setAttribute('title', lapsos[i].a+' _ '+lapsos[i].startDate.toLocaleString()+' / '+lapsos[i].until.toLocaleString());
        //lapso.setAttribute('style', 'width: '+(lapsos[i][1] * 10)+'px;height: 20px;background-color: '+randomColor()+';display: inline-block;');
        //        lapso.style.width = (lapsos[i][1] * 10)+'px';
        lapso.style.width = (lapsos[i].b * total)+'%';
        lapso.style.height = '100%';
        lapso.style.backgroundColor = randomColor();
        lapso.style.display = 'inline-block';
    }

});
