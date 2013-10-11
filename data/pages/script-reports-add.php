<?php exit; ?>

row: 0
    field: title
        Javascript de Crear Reporte
    field;
    
    field: content
    //<script>
        var coordsGet = false;
        
        function getAddressByCoords(lat, lon)
        {
            $('body').mask("Obteniendo direcciones");
            
            $('body').gmap3({
                getaddress:{
                    latLng:[lat, lon],
                    callback:function(results){
                        //Get Address
                        var address = '';
                        for(field in results[1].address_components){
                            for(type in results[0].address_components[field].types){
                                if(results[0].address_components[field].types[type] == 'route'){
                                    address += results[0].address_components[field].long_name;
                                    break;
                                }
                            }
                        }
                        
                        for(field in results[1].address_components){
                            for(type in results[0].address_components[field].types){
                                if(results[0].address_components[field].types[type] == 'administrative_area_level_2'){
                                    address += ' ' + results[0].address_components[field].long_name;
                                    break;
                                }
                            }
                        }
                        
                        $('#address').val(address);
                        
                        //Get city
                        for(field in results[0].address_components){
                            for(type in results[0].address_components[field].types){
                                if(results[0].address_components[field].types[type] == 'administrative_area_level_1'){
                                    $('#city').val(
                                        $('#city option:contains("'+results[0].address_components[field].long_name+'")').attr('value')
                                    );
                                    break;
                                }
                            }
                        }
                        
                        //Get postal code
                        for(field in results[0].address_components){
                            for(type in results[0].address_components[field].types){
                                if(results[0].address_components[field].types[type] == 'postal_code'){
                                    $('#zip').val(results[0].address_components[field].long_name);
                                    break;
                                }
                            }
                        }
                        
                        $('body').unmask();
                    }
                }
            });
        }
        
        $(document).ready(function(){
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
                        $('#coords').show();
                        $('#lon').val(position.coords.longitude);
                        $('#lat').val(position.coords.latitude);

                        getAddressByCoords(position.coords.latitude, position.coords.longitude);
                    }
                },
                fail: function(position){
                    $.geolocation.stop(coordsGet);
                    
                    alert(
                        "No se pudo obtener su ubicación.\n" + 
                        "Encienda su dispositivo GPS e intente refrescar la página nuevamente\n" + 
                        "para poder auto-detectar su ubicación actual.\n" + 
                        "También puede entrar la dirección física del área lo más certero posible."
                    );
                    $('#address-container').show();
                    $('body').unmask();
                }
            });
        });
    //</script>
    field;
    
    field: rendering_mode
        js
    field;
row;
