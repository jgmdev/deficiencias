<?php exit; ?>

row: 0
    field: title
        Javascript de Crear Reporte
    field;
    
    field: content
    //<script>
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
    
            $.geolocation.get({
                options: {
                    highAccuracy: false,
					maximumAge: 0,
					timeout: 20000 // 20 seconds
                },
                win: function(position){
                    $('body').unmask();
                    $('#coords').show();
                    $('#lon').val(position.coords.longitude);
                    $('#lat').val(position.coords.latitude);
                    
                    getAddressByCoords(position.coords.latitude, position.coords.longitude);
                },
                fail: function(position){
                    alert("No se pudo obtener su ubicación.\nEntre la dirección física del área lo mas certero posible.");
                    $('#address-container').show();
                    $('body').unmask();
                }
            });
        });
    field;
    
    field: rendering_mode
        js
    field;
row;
