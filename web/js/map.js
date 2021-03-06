ZUV.API = {
    _previousState: 'map',
    goTo: function(state) {
        document.getElementById('page_' + this._previousState).style.display='none';
        this._previousState = state;
        document.getElementById('page_' + state).style.display='block';
    },
    currentRegion: null
}

google.load('visualization', '1', {'packages': ['geomap']});
google.setOnLoadCallback(drawMap);

function drawMap() {
    var table = [
        ['City', 'Ratio', 'Label'],
        ['LT-AL', ZUV.data.provinces['Alytaus apskritis'], 'Alytus'],
        ['LT-KU', ZUV.data.provinces['Kauno apskritis'], 'Kaunas'],
        ['LT-KL', ZUV.data.provinces['Klaipėdos apskritis'], 'Klaipėda'],
        ['LT-MR', ZUV.data.provinces['Marijampolės apskritis'], 'Marijampolė'],
        ['LT-PN', ZUV.data.provinces['Panevėžio apskritis'], 'Panevėžys'],
        ['LT-SA', ZUV.data.provinces['Šiaulių apskritis'], 'Šiauliai'],
        ['LT-TA', ZUV.data.provinces['Šiaulių apskritis'], 'Tauragė'],
        ['LT-TE', ZUV.data.provinces['Telšių apskritis'], 'Telšiai'],
        ['LT-UT', ZUV.data.provinces['Utenos apskritis'], 'Utena'],
        ['LT-VL', ZUV.data.provinces['Vilniaus apskritis'], 'Vilnius']
    ];
    var data = google.visualization.arrayToDataTable(table);

    var options = {};
    options['region'] = 'LT';
    options['dataMode'] = 'regions';
    options['resolution'] = 'provinces';

    var container = document.getElementById('map_canvas');
    var geomap = new google.visualization.GeoMap(container);
    geomap.draw(data, options);
    google.visualization.events.addListener(geomap, 'regionClick', function(event) {
        if(ZUV.API.currentRegion !== null)        document.getElementById('province_info_' + ZUV.API.currentRegion).style.display='none';
        document.getElementById('info').style.display='none';
        document.getElementById('province_info_' + event.region).style.display='block';
        ZUV.API.currentRegion = event.region;
    });
}
