<?php exit; ?>

row: 0

    field: title
        Handle listing of coordinates on homepage
    field;
    
    field: content
        //<script>
        var cityVal = '';
        var typeVal = '';
        var lonVal = '';
        var latVal = '';
        
        function LoadAll()
        {
            $.ajax(
                '<?=\Cms\Uri::GetUrl('api/list')?>',
                {
                    data: {
                        city: cityVal, 
                        type: typeVal
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
                        type: typeVal
                    },
                    dataType: 'json',
                    complete: function(data, message){
                        GenerateResults($.parseJSON(data.responseText));
                    }
                }
            );
        }
        
        function GenerateResults(data)
        {
            var html = '';
            
            $('#reports').html('');
            $('#qty_reported').html('0');
            //console.log(data);
            
            if(data.stats.amount_returned > 0)
            {
                html += '<table>';
                for(prop in data.reports)
                {
                    var report = data.reports[prop];
 
                    html += '<tr>';
                    
                    html += '<td><a class="location"></a></td>';
                    
                    html += '<td>';
                    html += report.city;
                    html += ', ' + report.country + ' | ' + report.type_str + '</td>';
                    
                    html += '<td>'+report.age+'</td>';
                    
                    html += '<td>';
                    html += '<a title="'+report.id+'" class="confirm">Confirmar</a>';
                    html +='</td>';
                    
                    html += '</tr>';
                }
                html += '</table>';
                
                $('#reports').html(html);
                $('#qty_reported').html(data.stats.amount_returned);
            }
        }
        
        $(document).ready(function(){
            $("#town .near").show();

            $('body').mask("Detectando su ubicación");

            $.geolocation.get({
                win: function(position){
                    $('body').unmask();
                    $('#city').prepend('<option value="near">En mi área</option>');
                    $('#city').val('near');
                    
                    lonVal = position.coords.longitude;
                    latVal = position.coords.latitude;
                    
                    LoadByCoords();
                },
                fail: function(position){
                    alert("No se pudo obtener su ubicación.\nMostrando todos los reportes.");
                    $('body').unmask();
                    LoadAll();
                }
            });
            
            $('#city').change(function(){
                cityVal = $('#city').val();
                
                if(cityVal == 'near')
                    LoadByCoords();
                else
                    LoadAll();
            });
            
            $('#type').change(function(){
                typeVal = $('#type').val();
                
                if(cityVal == 'near')
                    LoadByCoords();
                else
                    LoadAll();
            });
            
            
        });
    field;
    
    field: rendering_mode
        js
    field;
    
row;