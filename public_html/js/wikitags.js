
function reply(element, id) {
    var form = $('#comentario').clone();
    $('#comentario').remove();
    $(element).parent().next().append(form);
    form.effect('highlight');
    $('#Comentario_reply_to').val(id);
    return false;
}

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
                tagClose = tagClose.replace(/\n/g,'');
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

function highlightSource(select) {
    var lang = select.options[select.options.selectedIndex].value;
    select.options.selectedIndex = 0;
    insertTags('comentario_comentario', '<code '+lang+'>\n', '\n</code>', 'Código a resaltar');
}

var actual = {};
actual['a1_'] = 'comentario_comentario';
