//-----------------------------------------------------------------------------------------------------------//

$(document).keypress(function(e) {
    if(e.which == 13) {
        //Construction de la querystring a passer en GET, s'il y a un order on le garde
        var url = window.location.href;
        var querystring = '?';

        var indexStart = url.indexOf('ORDERBY');
        if(url.indexOf('ORDERBY') >= 0){
            var indexStop = url.indexOf('&', indexStart);
            if (indexStop != -1)
            {
                querystring += url.substring(indexStart, indexStop);
            }
            else
            {
                querystring += url.substring(indexStart);
            }
        }
        var elements = document.getElementsByClassName('field');
        $.each(elements, function () {
            if(this.value != '')
            {
                querystring += '&queryFields['+this.name+']='+this.value;
            }
        });

        if($(':focus').attr('class') == 'field') {
            window.location.href = window.location.pathname+querystring;
        }
    }
});


$(document).ready(function() {
    $('#selectionNbrResultatPage').on('change', function() {
        //Construction de la querystring a passer en GET, s'il y a un order on le garde
        var url = window.location.href;
        var querystring = '?';

        var indexStart = url.indexOf('ORDERBY');
        if(url.indexOf('ORDERBY') >= 0){
            var indexStop = url.indexOf('&', indexStart);
            if (indexStop != -1)
            {
                querystring += url.substring(indexStart, indexStop);
            }
            else
            {
                querystring += url.substring(indexStart);
            }
        }

        querystring += '&nbrResultatPage='+$('select[name=listeDeroulante]').val();
        window.location.href = window.location.pathname+querystring;
    });
});

///////////////////////////////////////////////////