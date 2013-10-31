var $idAlbaran,
	$numero,
	$fr,
	$fv,
	$cId,
	$estado_datos_albaran,
	$div_libros,
	nuevos_libros_id = 1,
	div_libro = '<div id="libro-%isbn%" class="libro"><h3><div class="borrarLibro">-</div><input class="tituloAcordeon" value="%isbn%" type="text"> | <input class="tituloAcordeon" value="%titulo%" type="text"></h3><div class="contenido-libro"></div></div>';

$(document).ready(function(){
	$botonAnadir = $('#botonAnadir');
	$idAlbaran = $('#albaran-id');
	$numero = $('#albaran-numero');
	$fr = $('#albaran-fecha-realizacion');
	$fv = $('#albaran-fecha-vencimiento');
	$cId = $('#albaran-contrato');
	$estado_datos_albaran = $('#formulario-datos-albaran').find('.estado');

	$div_libros = $('.libros-albaran');
	$div_libros.accordion({
        header: "> div > h3",
        active: false,
        collapsible: true,
        icons: { "header": "ui-icon-plus", "headerSelected": "ui-icon-minus" }
      })
      .sortable({
        axis: "y",
        handle: "h3",
        stop: function( event, ui ) {
          ui.item.children( "h3" ).triggerHandler( "focusout" );
        }
      });
	
	modificar_estado($estado_datos_albaran, 'sinActualizar');
	
	$('#actualizar-datos-albaran').click(function(event){
		event.preventDefault();
		modificar_estado($estado_datos_albaran, 'enProceso');
		registrar_albaran_ajax();
	});
	
	$botonAnadir.click(function(event){
		event.preventDefault();
		$div_libros.append(div_libro.replace('%isbn%', nuevos_libros_id).replace('%isbn%', 'ISBN').replace('%titulo%', 'Titulo libro'));
		
		$('#libro-' + nuevos_libros_id++).find('.borrarLibro').click(borrarLibroAlbaran);
		$div_libros.accordion("refresh");
	});
	
	$idAlbaran.change(get_libros_albaran_ajax);
});

function borrarLibroAlbaran(event){
	event.preventDefault();
	var r=confirm("Esta acción borrará todos los ejemplares existentes.\n¿Desea realizar la acción?");
	if (r==true)
	{
		$(event.target).off();
		$(event.target).parent().parent().remove();
	}
}

function objToString(obj) {
    var str = '';
    for (var p in obj) {
        if (obj.hasOwnProperty(p)) {
            str += p + '::' + obj[p] + '<br>';
        }
    }
    $('#visor').html(str);
}

function registrar_albaran_ajax(){		
	$.ajax({
		url: ruta_ajax_registro_albaran,
		type: 'POST',
		data: {
			numero: $numero.val(),
			fecha_real: $fr.val(),
			fecha_vencimiento: $fv.val(),
			contrato_id: $cId.val()
		},
		success: function(data){
			if(data.estado){
				modificar_estado($estado_datos_albaran, 'actualizado');
				$idAlbaran.val('hola').trigger('change');
			} 
		}
	});
}

function get_libros_albaran_ajax(){
	$.ajax({
		url: ruta_ajax_get_libros_albaran,
		type: 'POST',
		data: {
			id_albaran: $idAlbaran.val()
		},
		success: procesar_libros		
	});
}

function procesar_libros(data){
	if(data.estado){
		$div_libros.parent().show();
		
		var length = data.libros.length,
		    element = null;
		
		for (var i = 0; i < length; i++) {
			isbn = data.libros[i];
			
			$.ajax({
				url: ruta_ajax_get_datos_libro,
				type: 'POST',
				data: {
					isbn: isbn
				},
				success: procesar_datos_libro				
			});
		}
	}
}

function procesar_datos_libro(data){
	if(data.estado){		
		$div_libros.append(div_libro.replace(/%isbn%/gi, data.isbn).replace('%titulo%', data.titulo));
		$contenido = $('#libro-' + data.isbn).find('.contenido-libro');
		$contenido.append('<p>'+data.titulo+'</p>');
		
		$botonBorrar = $('#libro-' + data.isbn).find('.borrarLibro').click(borrarLibroAlbaran);		
		
		$div_libros.accordion("refresh");
	}
}

function modificar_estado($elemento, $estado){
	$elemento.removeClass('sinActualizar enProceso actualizado');	
	$elemento.addClass($estado);
}