$(document).ready(function(){
    //$("#town .near").show();
    
    $('body').mask("Detectando su ubicación");
    
    $.geolocation.get({
        win: function(position){
            $('body').unmask();
        }, 
        fail: function(position){
            alert("No se pudo obtener su ubicación");
            $('body').unmask();
        }
    });
});


