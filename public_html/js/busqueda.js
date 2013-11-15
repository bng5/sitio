
function setTitle(txt) {
    document.title = 'Resultados para: '+txt+' - Bng5.net';
}

function getQuery() {
    var query_str = document.location.search.substring(1);
    var vars = query_str.split('&');
    var query = {};
    for(var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }
    return query;
}
var query = getQuery();

function searchComplete(customSearch) {
    var data = {q: customSearch.Aa.query};
    data['p'] = customSearch.Aa.adPage ? String(customSearch.Aa.adPage) : '1';
    query = getQuery();
    if(!query.p) {
        query.p = '1';
    }
    var pagina = (parseInt(data.p) > 1) ? '&p='+data.p : '';
    
    if(data.q != query.q || data.p != query.p) {
        setTitle(data.q);
        window.history.pushState(data, customSearch.Aa.query, "/busqueda?q="+customSearch.Aa.query+pagina);
    }
}

window.onpopstate = function(event) {
    if(event.state) {
        var data = event.state;
        var pagina = data.p ? data.p : 1;
        document.getElementById('busqueda').elements.namedItem('q').value = data.q;
        setTitle(data.q);
        customSearchControl.execute(data.q, pagina);
    }
};

google.load('search', '1');
var customSearchControl;
function OnLoad() {

    customSearchControl = new google.search.CustomSearchControl('000505282032523587963:plblebphvbg');
    customSearchControl.setLinkTarget(google.search.Search.LINK_TARGET_SELF);
    
    var options = new google.search.DrawOptions();
    options.enableSearchResultsOnly();    
    customSearchControl.draw('gcs', options);
    customSearchControl.setSearchCompleteCallback(this, searchComplete);
  
    if(query.q) {
        var pagina = query.p ? query.p : 1;
        customSearchControl.execute(query.q, pagina);
    }
}
google.setOnLoadCallback(OnLoad);
