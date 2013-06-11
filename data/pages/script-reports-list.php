<?php exit; ?>

row: 0

    field: title
        Handle listing of coordinates on homepage
    field;
    
    field: content
        //<script>
        var page = 0;
        var cityVal = '';
        var typeVal = '';
        var lonVal = '';
        var latVal = '';
        var coordsGet = false;
        var watch = false;
        var alertedReports = new Array();
        
        function LoadAll()
        {
            $.ajax(
                '<?=\Cms\Uri::GetUrl('api/list')?>',
                {
                    data: {
                        city: cityVal, 
                        type: typeVal,
                        page: page
                    },
                    dataType: 'json',
                    complete: function(data, message){
                        GenerateResults($.parseJSON(data.responseText));
                    }
                }
            );
        }
        
        function LoadByCoords()
        {
            $.ajax(
                '<?=\Cms\Uri::GetUrl('api/list')?>',
                {
                    data: {
                        lon: lonVal, 
                        lat: latVal,
                        type: typeVal,
                        page: page
                    },
                    dataType: 'json',
                    complete: function(data, message){
                        GenerateResults($.parseJSON(data.responseText));
                    }
                }
            );
        }
        
        function GetDistance(lat, lon, element)
        {
            $('body').gmap3({
                getdistance:{
                    options:{
                        origins:[[latVal, lonVal]],
                        destinations:[[lat, lon]],
                        travelMode: google.maps.TravelMode.DRIVING
                    },
                    callback: function(results, status){
                        var html = "";
                        if (results){
                            for (var i = 0; i < results.rows.length; i++){
                                var elements = results.rows[i].elements;
                                for(var j=0; j<elements.length; j++){
                                    switch(elements[j].status){
                                        case "OK":
                                            html += elements[j].distance.text + " (" + elements[j].duration.text + ")<br />";
                                            break;
                                        case "NOT_FOUND":
                                        case "ZERO_RESULTS":
                                            break;
                                    }
                                }
                            }
                        }

                        //Wait until element is created
                        while($(element).lenght <= 0){}
                        
                        $(element).html(html);
                    }
                }
            });
        }
        
        function GenerateResults(data)
        {
            cityVal = $('#city').val();
            
            var html = '';
            
            $('#reports').html('');
            $('#qty_reported').html('0');
            //console.log(data);
            
            if(data.stats.amount_returned > 0)
            {
                html += '<div class="list-container">';
                html += '<table class="list">';
                for(prop in data.reports)
                {
                    var report = data.reports[prop];
                    
                    var near_class = '';
                    if(report.distance_unit == 'pies' || (report.distance_unit == 'mi' && report.distance < 0.3)){
                        near_class = ' near';
                        
                        if(watch && !alertedReports[report.id]){
                            alertedReports[report.id] = true;
                            playAlert();
                        }
                    }
 
                    html += '<tr data-id="'+report.id+'" class="row'+near_class+'">';
                    
                    var style='background: transparent url(themes/deficiency/images/location.png) no-repeat center;';
                    
                    switch(report.type){
                        case '0':
                            style='background: transparent url(themes/deficiency/images/deficiency-hole.png) no-repeat center;';
                            break;
                        case '1':
                            style='background: transparent url(themes/deficiency/images/deficiency-broken-pipe.png) no-repeat center;';
                            break;
                        case '3':
                            style='background: transparent url(themes/deficiency/images/deficiency-traffic-lights.png) no-repeat center;';
                            break;
                        case '4':
                            style='background: transparent url(themes/deficiency/images/deficiency-rock-slide.png) no-repeat center;';
                            break;
                        case '5':
                            style='background: transparent url(themes/deficiency/images/deficiency-electric-pole.png) no-repeat center;';
                            break;
                    }
                    
                    html += '<td><a class="location" style="'+style+'" href="reports/view?id='+report.id+'"></a></td>';
                    
                    html += '<td class="details">';
                    html += '<a href="reports/view?id='+report.id+'">';
                    html += '<div class="route">' + report.line1 + '</div>';
                    html += '<span class="city">';
                    html += report.city + ', ' + 'PR';
                    html += '</span>';
                    html += '<div class="type">' + report.type_str + '</div>';
                    html += '</a>';
                    html += '</td>';
                    
                    if(cityVal != 'near'){
                        html += '<td>'+report.age+'</td>';
                    }
                    else{
                        /*html += '<td class="distance-'+report.id+'">-</td>';
                        GetDistance(report.latitude, report.longitude, '.distance-'+report.id);*/
                                                        
                        html += '<td class="distance-'+report.id+'">'+report.distance+' '+report.distance_unit+'<br />'+report.arrival_time+'</td>';
                    }
                        
                    
                    html += '<td class="confirms" style="display: none">';
                    html += '<a data-id="'+report.id+'" class="confirm" href="reports/confirm?id='+report.id+'">Confirmar</a>';
                    html +='</td>';
                    
                    html += '</tr>';
                }
                html += '</table>';
                html += '</div>';
                
                var stats = data.stats;
                
                html += '<table id="navigation">';
                html += '<tr>';
                html += '<td class="previous">';
                if(stats.current_page > 1){
                    html += '<a id="previous_page">&lt; Anterior</a>';
                }
                html += '</td>';
                html += '<td class="pages">';
                html += stats.current_page + '/' + stats.total_pages;
                html += '</td>';
                html += '<td class="next">';
                if(stats.current_page < stats.total_pages){
                    html += '<a id="next_page">Siguiente &gt;</a>';
                }    
                html += '</td>';
                html += '</tr>';
                html += '</table>';
                
                $('#reports').html(html);
                $('#qty_reported').html(data.stats.total_reports);
                
                if(stats.current_page > 1){
                    $('#previous_page').click(function(){
                        page = stats.current_page - 1;
                        if(cityVal == 'near')
                            LoadByCoords();
                        else
                            LoadAll();
                    });
                }
                
                if(stats.current_page < stats.total_pages){
                    $('#next_page').click(function(){
                        page = stats.current_page + 1;
                        if(cityVal == 'near')
                            LoadByCoords();
                        else
                            LoadAll();
                    });
                }  
                
                if($(window).innerWidth() > 470){
                    $('.confirms').show();
                }
                
                $('.row').click(function(){
                   location.href = 'reports/view?id=' +  $(this).attr('data-id');
                });
            }
        }
        
        function playAlert()
        {
            var sound = document.getElementById('alert-sound');
            
            if(sound.paused){
                sound.play();

                setTimeout(
                    function(){
                        sound.pause();
                    },
                    7000
                );
            }
        }
        
        $(document).ready(function(){
            $("#town .near").show();

            $('body').mask("Detectando su ubicación");

            coordsGet = $.geolocation.watch({
                options: {
                    enableHighAccuracy: true,
					maximumAge: 0,
					timeout: 10000 // 10 seconds
                },
                win: function(position){
                    if(position.coords.accuracy < 15){
                        $.geolocation.stop(coordsGet);

                        $('body').unmask();
                        $('#city').prepend('<option value="near">En mi área</option>');
                        $('#city').val('near');

                        lonVal = position.coords.longitude;
                        latVal = position.coords.latitude;

                        LoadByCoords();

                        //Add monitoring button
                        $('.filter .monitor-button').css('display', 'block');
                        $('.monitor-button').click(function(){
                            alertedReports = new Array();
                            if($(this).html() == 'Monitorear'){
                                watch = $.geolocation.watch({
                                    options: {
                                        enableHighAccuracy: true,
                                        maximumAge: 0,
                                        timeout: 10000 // 10 seconds
                                    },
                                    win: function(position){
                                        if(typeof(refreshWatch) == 'undefined'){
                                            lonVal = position.coords.longitude;
                                            latVal = position.coords.latitude;
                                            page = 1;

                                            LoadByCoords();
                                            
                                            refreshWatch = setTimeout(
                                                function(){
                                                    clearTimeout(refreshWatch);
                                                    refreshWatch = undefined;
                                                },
                                                3000 //Monitor every 3 seconds
                                            );
                                        }
                                    }
                                });

                                $(this).html('Detener Monitoreo');
                            }
                            else{
                                $.geolocation.stop(watch);
                                watch = false;
                                $(this).html('Monitorear');
                            }

                        });
                    }
                },
                fail: function(position){
                    $.geolocation.stop(coordsGet);
                    
                    alert("No se pudo obtener su ubicación.\nMostrando todos los reportes.");
                    $('body').unmask();
                    LoadAll();
                }
            });
            
            $('#city').change(function(){
                cityVal = $('#city').val();
                
                page = 1;
                
                if(cityVal == 'near')
                    LoadByCoords();
                else
                    LoadAll();
            });
            
            $('#type').change(function(){
                cityVal = $('#city').val();
                typeVal = $('#type').val();
                
                page = 1;
                
                if(cityVal == 'near')
                    LoadByCoords();
                else
                    LoadAll();
            });
            
            $(window).resize(function(){
                if($(window).innerWidth() > 470){
                    $('.confirms').show();
                }
                else{
                    $('.confirms').hide();
                }
            });
            
        });
    field;
    
    field: rendering_mode
        js
    field;
    
row;