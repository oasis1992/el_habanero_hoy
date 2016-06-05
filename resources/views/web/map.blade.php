@extends('main')

@section('content')
    <div class="container">
        <div class="controls row">
            <div class="form-group col-md-6" style="display: block;">
                <select name="state" id="dropdown-state" class="form-control">
                    <option selected="selected" disabled="">Selecciona un estado</option>
                    <option id="01" value="Aguascalientes">Aguascalientes</option><option id="02" value="Baja California">Baja California</option><option id="03" value="Baja California Sur">Baja California Sur</option><option id="04" value="Campeche">Campeche</option><option id="07" value="Chiapas">Chiapas</option><option id="08" value="Chihuahua">Chihuahua</option><option id="05" value="Coahuila de Zaragoza">Coahuila de Zaragoza</option><option id="06" value="Colima">Colima</option><option id="09" value="Distrito Federal">Distrito Federal</option><option id="10" value="Durango">Durango</option><option id="11" value="Guanajuato">Guanajuato</option><option id="12" value="Guerrero">Guerrero</option><option id="13" value="Hidalgo">Hidalgo</option><option id="14" value="Jalisco">Jalisco</option><option id="15" value="México">México</option><option id="16" value="Michoacán de Ocampo">Michoacán de Ocampo</option><option id="17" value="Morelos">Morelos</option><option id="18" value="Nayarit">Nayarit</option><option id="19" value="Nuevo León">Nuevo León</option><option id="20" value="Oaxaca">Oaxaca</option><option id="21" value="Puebla">Puebla</option><option id="22" value="Querétaro">Querétaro</option><option id="23" value="Quintana Roo">Quintana Roo</option><option id="24" value="San Luis Potosí">San Luis Potosí</option><option id="25" value="Sinaloa">Sinaloa</option><option id="26" value="Sonora">Sonora</option><option id="27" value="Tabasco">Tabasco</option><option id="28" value="Tamaulipas">Tamaulipas</option><option id="29" value="Tlaxcala">Tlaxcala</option><option id="30" value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option><option id="31" value="Yucatán">Yucatán</option><option id="32" value="Zacatecas">Zacatecas</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                <select name="state" id="dropdown-state" class="form-control">
                    <option selected="selected" disabled="">Selecciona un producto</option>
                    <option id="01" value="Aguascalientes">Chile Habanero</option>
                </select>
            </div>
        </div>
    </div>

    <div>
        <div class="row">

            <div class="col-md-6">
                <div id="mapa" style="height: 600px;">
                    <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3728.389232659724!2d-89.61692459999998!3d20.856355999999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8f566e62b3cc5415%3A0xbcb1c2d381c1f7b3!2sFeria+Yucat%C3%A1n+Xmatkuil!5e0!3m2!1ses-419!2smx!4v1440689692122" width="100%" height="250px" frameborder="0" style="border:0" allowfullscreen></iframe> -->
                </div>
            </div>

            <div class="col-md-6">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-justified" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Tu ubicaciòn</a></li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Destino | origen</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        @include('my_location')
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">

                        <div class="row">
                            <div class="list">

                                <div class="table-bordered table-responsive">
                                    <table id="table" class="table">
                                        <tr>
                                            <td id="comercial">Origen</td>
                                            <td id="nacional">Destino</td>
                                        </tr>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
@endsection

@section('js')
    <script>
        var historicalOverlay;

        function initMap() {
            var map = new google.maps.Map(document.getElementById('mapa'), {
                zoom: 5,
                mapTypeId:google.maps.MapTypeId.SATELLITE,
                scrollwheel: false,
                center: {lat: 22.920401, lng:  -101.730510}

            });

            var myLatLng = {lat: 20.855980, lng:  -89.615734};
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: 'EconomiaCode'
            });



            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    $.ajax({
                        dataType: "json",
                        url: "api/latitude/"+pos.lat+"/longitude/"+pos.lng+"/now",
                      //  data: {lat:pos.lat, lon:pos.long},
                    }).done(function(data) {
                       console.log(data);
                        var state = document.getElementById('state');
                        var precio_estado = document.getElementById('precio_estado');
                        var promedio_nacional = document.getElementById('promedio_nacional');
                        var precio_sucursal = document.getElementById('precio_comercial');
                        precio_sucursal.innerHTML = data[0].sucursal;
                        state.innerHTML = state.innerHTML +" "+ data[0].origen[0];
                        precio_estado.innerHTML = data[0].precio_frec;
                        promedio_nacional.innerHTML = data[0].promedio_nacional;
                    });

                    $.ajax({
                        dataType: "json",
                        url: "api/latitude/null/longitude/null/now/1",
                        //  data: {lat:pos.lat, lon:pos.long},
                    }).done(function(data) {
                        console.log(data);
                        var table = document.getElementById('table');
                        for(var i =0; i< data.length; i++){

                            table.innerHTML += '<tr><td>'+ data[i].origen[0] +'</td>' + '<td>'+ data[i].destino[0] +" "+data[i].destino_especifico +'</td></tr>';

                        }

                    });

                   // infoWindow.setPosition(pos);
                   // infoWindow.setContent('Location found.');
                    map.setCenter(pos);
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                    'Error: The Geolocation service failed.' :
                    'Error: Your browser doesn\'t support geolocation.');

        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4DxbghI7Qf7pfyyxsc1gLjvsJiTavAQo&callback=initMap&signed_in=true">
    </script>
@endsection