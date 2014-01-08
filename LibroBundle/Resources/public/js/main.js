var $contenedorEjemplaresDatos = $('#ejemplar_datos');

$(document).ready(function(){
	var selectEjemplares = $('#ejemplares_encontrados_select');
	
	selectEjemplares.change(function(){
		var elemento = $(this);
		
		if(elemento.val() != ""){
			$.ajax({
				url: ruta_ajax_get_ejemplar_datos,
				data: {
					idEjemplar: elemento.val()
				},
				type: "POST",
				success: function(data){
					var $plantilla = $(data);					
					var $estado = $plantilla.find('.estado_ejemplar');
					
					$plantilla.find('.boton_actualizar').click($plantilla, function(evento){
						var $elemento = evento.data;
						
						$.ajax({
							url: ruta_ajax_ejemplar_set_loc,
							type: "POST",
							data: {
								idEjemplar: elemento.val(),
								idLoc: $elemento.find('#localizacion_select').val()
							},
							success: function(data){
								if(data.estado){
									modificar_estado($plantilla.find('.estado_ejemplar'), 'actualizado');
								}
							}
						});
					});
					
					$plantilla.find('#localizacion_select').change(function(){
						modificar_estado($estado, 'sinActualizar');
					});					
					
					$contenedorEjemplaresDatos.html($plantilla);
				}
			});
		}
		else{
			$contenedorEjemplaresDatos.html('');
		}
	});
});

function modificar_estado($elemento, $estado){
	$elemento.removeClass('sinActualizar actualizado');
	$elemento.addClass($estado);
}