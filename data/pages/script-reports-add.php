<?php exit; ?>

row: 0
    field: title
        Javascript de Crear Reporte
    field;
    
    field: content
    //<script>
        $(document).ready(function(){
            $('body').mask("Detectando su ubicación");
    
            $.geolocation.get({
                win: function(position){
                    $('body').unmask();
                    $('#coords').show();
                    $('#lon').val(position.coords.longitude);
                    $('#lat').val(position.coords.latitude);

                    $('body').mask("Detectando el pueblo");
                    $.ajax(
                        '<?=\Cms\Uri::GetUrl('api/town')?>',
                        {
                            data: {
                                lon: position.coords.longitude,
                                lat: position.coords.latitude
                            },
                            complete: function(data, message){
                                $('#city').val(data.responseText);
                                $('body').unmask();
                            }
                        }
                    );
                },
                fail: function(position){
                    alert("No se pudo obtener su ubicación.\nEntre la dirección física del área lo mas certero posible.");
                    $('#address').show();
                    $('body').unmask();
                }
            });
        });
    field;
    
    field: rendering_mode
        js
    field;
row;
