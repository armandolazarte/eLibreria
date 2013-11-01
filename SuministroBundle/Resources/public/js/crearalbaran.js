var $idAlbaran,
	$numero,
	$fr,
	$fv,
	$cId,
	$estado_datos_albaran,
	$div_libros,
	nuevos_libros_id = 1,
	div_libro = '<div id="libro-%isbn%" class="libro">';
	div_libro += '<h3><div class="borrarLibro">-</div><div class="estadoAcordeon sinActualizar"></div>';
	div_libro += '<input class="isbnAcordeon" maxlength="13" value="%isbn%" onfocus="this.value=(this.value==\'ISBN\') ? \'\' : this.value;" onblur="this.value=(this.value==\'\') ? \'ISBN\' : this.value;" type="text">';
	div_libro += ' | <input class="tituloAcordeon" value="%titulo%" onfocus="this.value=(this.value==\'Titulo libro\') ? \'\' : this.value;" onblur="this.value=(this.value==\'\') ? \'Titulo libro\' : this.value;" type="text">';
	div_libro += ' | <input class="editorialAcordeon" value="%editorial%" onfocus="this.value=(this.value==\'Editorial\') ? \'\' : this.value;" onblur="this.value=(this.value==\'\') ? \'Editorial\' : this.value;" type="text"></h3>';
	div_libro += '<div class="contenido-libro"></div></div>';
	delete($.ui.accordion.prototype._keydown);

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
		$div_libros.find('p').remove();
		$div_libros.append(div_libro.replace('%isbn%', nuevos_libros_id).replace('%isbn%', 'ISBN').replace('%titulo%', 'Titulo libro').replace('%editorial%', 'Editorial'));
		
		$('#libro-' + nuevos_libros_id).find('.borrarLibro').click(borrarLibroAlbaran);
		$('#libro-' + nuevos_libros_id).find('.isbnAcordeon').autocomplete({source: buscarISBNLibro, select: seleccionarISBNLibro});
		$('#libro-' + nuevos_libros_id).find('.editorialAcordeon').autocomplete({source: buscarEditorialLibro});
		$('#libro-' + nuevos_libros_id).find('.isbnAcordeon').click(borrarClick);
		$('#libro-' + nuevos_libros_id).find('.editorialAcordeon').click(borrarClick);
		$('#libro-' + nuevos_libros_id++).find('.tituloAcordeon').click(tituloModificado);
		$div_libros.accordion("refresh");
	});
	
	$idAlbaran.change(get_libros_albaran_ajax);
});

function tituloModificado(event){
	event.preventDefault();
	$(this).addClass('porGuardar');
	$estado = $(this).siblings('.estadoAcordeon');
	
	modificar_estado($estado, 'enProceso');
	return false;
}

function borrarClick(event){
	event.preventDefault();
	return false;
}

function borrarLibroAlbaran(event){
	event.preventDefault();
	var r=confirm("Esta acción borrará todos los ejemplares existentes.\n¿Desea realizar la acción?");
	if (r==true)
	{
		$(event.target).off();
		$(event.target).parent().parent().remove();
	}
	
	return false;
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

function seleccionarISBNLibro( event, ui ) {
	target = event.target;
	
	$padre = $(target.parentElement.parentElement);
	
	$padre.attr('id', 'libro-' + ui.item.isbn);
	$padre.find('.isbnAcordeon').removeClass('porGuardar');
	$padre.find('.tituloAcordeon').val(ui.item.titulo).removeClass('porGuardar');
	$padre.find('.editorialAcordeon').val(ui.item.editorial).removeClass('porGuardar');
	modificar_estado($padre.find('.estadoAcordeon'), 'actualizado');
	$padre.find('.contenido-libro').html('');
	$div_libros.accordion("refresh");
}

function buscarISBNLibro(request, response ) {
	$padre = this.element.parent().parent();
	
	this.element.addClass('porGuardar');
	
	$estado = $padre.find('.estadoAcordeon');
	modificar_estado($estado, 'enProceso');
	
	$padre.find('.contenido-libro').html('Por favor, actualize los datos del libro introducido antes de continuar.');
	$div_libros.accordion("refresh");
	
    $.ajax({
        url: ruta_ajax_get_isbn_existente,
        type: 'POST',
        data: {
          isbn: request.term
        },
        success: function( data ) {        	
          response( $.map( data.sugerencias, function(item){
          		return {
	          		label: item.isbn + " - " + item.titulo + (item.editorial ? " [ " + item.editorial + " ]" : ""),
	          		value: item.isbn,
	          		isbn: item.isbn,
	          		titulo: item.titulo,
	          		editorial: item.editorial
	          	};    
          }));
        }
      });
}

function buscarEditorialLibro(request, response ) {	
	$padre = this.element.parent().parent();
	
	this.element.addClass('porGuardar');
	
	$estado = $padre.find('.estadoAcordeon');
	modificar_estado($estado, 'enProceso');
	
	$padre.find('.contenido-libro').html('Por favor, actualize los datos del libro introducido antes de continuar.');
	$div_libros.accordion("refresh");
	
	$.ajax({
		url: ruta_ajax_get_editoriales_existente,
		type: 'POST',
		data: {
			editorial: request.term
		},
		success: function(data){
			response( $.map(data.sugerencias, function(item){
					return {
						label: item.nombre,
						value: item.nombre
					};
			}));
		}
	});
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
		
		if(length < 1){
			$div_libros.append('<p>Sin Libros</p>');
		}
		else{
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
}

function procesar_datos_libro(data){
	if(data.estado){		
		$div_libros.append(div_libro.replace(/%isbn%/gi, data.isbn).replace('%titulo%', data.titulo).replace('%editorial%', data.editorial));
		$contenido = $('#libro-' + data.isbn).find('.contenido-libro');
		$contenido.append('<p>'+data.titulo+'</p>');
		
		$botonBorrar = $('#libro-' + data.isbn).find('.borrarLibro').click(borrarLibroAlbaran);	
		$('.isbnAcordeon').click(borrarClick);	
		$('.tituloAcordeon').click(borrarClick);	
		$('.editorialAcordeon').click(borrarClick);		
		$div_libros.accordion("refresh");
	}
}

function modificar_estado($elemento, $estado){
	$elemento.removeClass('sinActualizar enProceso actualizado');
	$elemento.addClass($estado);
	
	if($elemento.hasClass('enProceso')){
		$elemento.click(actualizarLibro);
	}
}

var actualizando = 0;

function actualizarLibro(event){
	if(actualizando == 0){
		actualizando = 1;
		
		event.preventDefault();
		target = event.target;
		
		$padre = $(target.parentElement.parentElement);
	
		$isbn = $padre.find('.isbnAcordeon');
		$titulo = $padre.find('.tituloAcordeon');
		$editorial = $padre.find('.editorialAcordeon');
		$estado = $padre.find('.estadoAcordeon');
		$contenido = $padre.find('.contenido-libro');
	
		$estadoAjax = $.ajax({
			url: ruta_ajax_registro_libro,
			type: 'POST',
			data: {
				isbn: $isbn.val(),
				titulo: $titulo.val(),
				editorial: $editorial.val(),
			},
			success: function(data){
				if(data.estado){
					actualizando = 0;
					
					$isbn.removeClass('porGuardar');
					$titulo.removeClass('porGuardar');
					$editorial.removeClass('porGuardar');
					
					modificar_estado($estado, 'actualizado');
					
					//$contenido.load(ruta_ajax_cargar_contenido);
				}
			},
			error: function(){
				actualizando = 0;
			}
		});
	}
	
	return false;
}