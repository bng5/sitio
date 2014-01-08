
$(function() {
    
    var admin_div = $('<div id="admin"></div>');
    admin_div.append('<div><a href="#header">#header</a></div>');
    
    var seccion = document.location.pathname.split('/');
    if(seccion[1] == 'bliki') {
        if(seccion[2]) {
            var cache_path = '?';
            if(document.location.search) {
                var get = document.location.search.substring(1).split('&');
                var param;
                for(var i = 0; i < get.length; i++) {
                    param = get[i].split('=');
                    if(param[0] == 'cache') {
                        continue;
                    }
                    cache_path += get[i]+'&';
                }
            }
//            var cache_path = document.location.search ? document.location.search+'&' : '?';
            admin_div.append('<div>Cache: <a href="'+cache_path+'cache=clear">clear</a> <a href="'+cache_path+'cache=no">no</a></div>');
    //        admin_div.
            var link;
            var id_comentario;
            $('dl.comentarios dt').each(function(index, Element) {
                id_comentario = Element.id.replace(/^comentario-/, '');
                // '+id_comentario+'
                link = $('<a href="/comentarios/'+id_comentario+'" class="admin-eliminar"><span>eliminar</span></a>');
                link.click(function(ev) {
                    ev.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: this.href,
                        dataType: 'json',
                        data: {id: this.id, status: 0}
                    })
                    .done(function(data, textStatus, jqXHR) {
                        
                    });
                });
                $(Element).append(link);
            });
        }
    }
    else if(seccion[1] == 'comentarios') {
        
    }
    
    admin_div.append('<div class="usuario"> <a href="/site/logout">Salir</a></div>');
    $(document.body).prepend(admin_div);
    
//    document.location.hash = '#header';
});
