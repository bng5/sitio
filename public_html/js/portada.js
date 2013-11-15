swfobject.addDomLoadEvent(function() {
	var flashvars = {texto: document.getElementById('frase_portada').firstChild.data};
	var params = {wmode: "transparent"};
	swfobject.embedSWF("/img/texto_portada.swf", "frase_portada", "972", "325", "8", null, flashvars, params, null);
});