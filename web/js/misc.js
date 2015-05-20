$(window).bind('hashchange', function() {
    var treeType = window.location.hash.substr(1);

    $.getJSON('/provinces?tree_type=' + treeType, function(provinces) {
        ZUV.data.provinces = provinces;
        drawMap();
    });
});