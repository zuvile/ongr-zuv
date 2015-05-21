$(window).bind('hashchange', function() {
    var treeType = window.location.hash.substr(1);

    $('#type').text(treeType);
    $.getJSON('/provinces?tree_type=' + treeType, function(provinces) {
        ZUV.data.provinces = provinces;
        if(ZUV.API.currentRegion !== null) document.getElementById('province_info_' + ZUV.API.currentRegion).style.display='none';
        document.getElementById('info').style.display='block';
        drawMap();
    });

    $('#treeInfo').load('/treeInfo/' + treeType);
});

$(document).ready(
    function() {
        $('#type').text('Eglė');
        $('#treeInfo').load('/treeInfo/Eglė');
    }
);