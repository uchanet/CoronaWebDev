<script>
  /* Initial Map */
  var map = L.map('map').setView([-7.9, 110.45], 10); //lat, long, zoom

  /* Tile Basemap */
  var basemap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    //attribution akan muncul di pojok kanan bawah
    attribution: '<?php echo $attribution; ?>'
  });
  basemap.addTo(map);

  /* GeoJSON Polygon */
  var adminpolygon = L.geoJson(null, {
    /* Style polygon */
    style: function(feature) {
      if (feature.properties.POSITIF <= 5) {
        return {
          opacity: 1,
          color: 'black',
          weight: 0.5,
          fillOpacity: 0.8,
          fillColor: 'rgb(254, 242, 0)'
        }
      } else if (feature.properties.POSITIF >= 6 && feature.properties.POSITIF <= 19) {
        return {
          opacity: 1,
          color: 'black',
          weight: 0.5,
          fillOpacity: 0.8,
          fillColor: 'rgb(195, 187, 34)'
        }
      } else if (feature.properties.POSITIF >= 20 && feature.properties.POSITIF <= 50) {
        return {
          opacity: 1,
          color: 'black',
          weight: 0.5,
          fillOpacity: 0.8,
          fillColor: 'rgb(244, 132, 32)'
        }
      } else if (feature.properties.POSITIF > 50) {
        return {
          opacity: 1,
          color: 'black',
          weight: 0.5,
          fillOpacity: 0.8,
          fillColor: 'rgb(221, 77, 87)'
        }
      } else {
        return {
          opacity: 1,
          color: 'black',
          weight: 0.5,
          fillOpacity: 0.8,
          fillColor: 'rgb(37, 150, 210)'
        }
      }
    },
    /* Highlight & Popup */
    onEachFeature: function(feature, layer) {
      layer.on({
        mouseover: function(e) { //Fungsi ketika mouse berada di atas obyek
          var layer = e.target; //variabel layer
          layer.setStyle({ //Highlight style
            weight: 2, //Tebal garis tepi polygon
            color: "gray", //Warna garis tepi polygon
            opacity: 1, //Transparansi garis tepi polygon
            fillColor: "cyan", //Warna tengah polygon
            fillOpacity: 0.7, //Transparansi tengah polygon
          });
        },
        mouseout: function(e) { //Fungsi ketika mouse keluar dari area obyek
          adminpolygon.resetStyle(e.target); //Mengembalikan style polygon ke style awal
          map.closePopup(); //Menutup popup
        },
        click: function(e) { //Fungsi ketika obyek di-klik
          var content = "<div class='card'>" +
            "<div class='card-header alert-primary text-center p-2'><strong>" + feature.properties.KECAMATAN + "</strong></div>" +
            "<div class='card-body p-0'>" +
            "<table class='table table-responsive-sm m-0'>" +
            "<tr><th class='p-2'>Kasus Positif</th><th class='p-2'>" + feature.properties.POSITIF + "</th></tr>" +
            "<tr><th class='p-2'>Jumlah ODP</th><th class='p-2'>" + feature.properties.ODP + "</th></tr>" +
            "<tr><th class='p-2'>Jumlah PDP</th><th class='p-2'>" + feature.properties.PDP + "</th></tr>" +
            "<tr><th class='p-2'>Dirawat</th><th class='p-2'>" + feature.properties.DIRAWAT + "</th></tr>" +
            "<tr><th class='p-2'>Sembuh</th><th class='p-2'>" + feature.properties.SEMBUH + "</th></tr>" +
            "<tr><th class='p-2'>Meninggal</th><th class='p-2'>" + feature.properties.MENINGGAL + "</th></tr>" +
            "</table>" +
            "</div>";
          adminpolygon.bindPopup(content); //Popup
        }
      });
      layer.bindTooltip(feature.properties.KECAMATAN, {
        direction: 'center',
        permanent: true,
        className: 'styleLabel'
      });
    }
  });
  /* memanggil data geojson polygon */
  $.getJSON("data/geojson.php", function(data) {
    adminpolygon.addData(data);
    map.addLayer(adminpolygon);
    map.fitBounds(adminpolygon.getBounds());
  });

  resetLabels([adminpolygon]);
  map.on("zoomend", function() {
    resetLabels([adminpolygon]);
  });
  map.on("move", function() {
    resetLabels([adminpolygon]);
  });
  map.on("layeradd", function() {
    resetLabels([adminpolygon]);
  });
  map.on("layerremove", function() {
    resetLabels([adminpolygon]);
  });

  /* Scale Bar */
  L.control.scale({
    maxWidth: 150,
    imperial: false,
  }).addTo(map);

  /* Legenda */
  var legend = new L.Control({
    position: 'bottomright'
  });
  legend.onAdd = function(map) {
    this._div = L.DomUtil.create('div', 'info');
    this.update();
    return this._div;
  };
  legend.update = function() {
    this._div.innerHTML = '<h5>Legenda</h5><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(254, 242, 0, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Kasus 1 - 5<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(195, 187, 34, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Kasus 6 - 19<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(244, 132, 32, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Kasus 20 - 50<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(221, 77, 87, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Kasus >50<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(37, 150, 210, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Tidak ada kasus'
  };
  legend.addTo(map);

  /* Data table */
  $(document).ready(function() {
    $('#dataTable').DataTable();
  });
</script>