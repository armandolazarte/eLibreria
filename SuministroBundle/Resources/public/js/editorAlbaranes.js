var $div_formulario,
	$idAlbaran,
	$numeroAlbaran,
	$fechaRealizacion,
	$fechaVencimiento,
	$contrato,
	$estado,
	$div_jaula_libros,
	$div_libros,
	$boton_anadir_libro;

delete($.ui.accordion.prototype._keydown);

$(document).ready(function(){
	$div_formulario = $('#form-datos-albaran');
	$div_jaula_libros = $('#jaula-libros');
	
	$idAlbaran = $div_formulario.find('#albaran-id');
	$numeroAlbaran = $div_formulario.find('#albaran-numero');
	$fechaRealizacion = $div_formulario.find('#albaran-fecha-realizacion');
	$fechaVencimiento = $div_formulario.find('#albaran-fecha-vencimiento');
	$contrato = $div_formulario.find('#albaran-contrato');
	
	$botonActualizar = $div_formulario.find('#actualizar-datos-albaran');
	$estado = $div_formulario.find('.estado');
	
	//Iniciando estado a sinActualizar si es para crear o Actualizado si se edita albaran
	if($idAlbaran.val() != ""){
		modificar_estado($estado, 'actualizado');
		habilitarJaulaLibros();
	}
	else{
		modificar_estado($estado, 'sinActualizar');
	}

	$numeroAlbaran.change(function(){modificar_estado($estado, 'sinActualizar')});
	$fechaRealizacion.change(function(){modificar_estado($estado, 'sinActualizar')});
	$fechaVencimiento.change(function(){modificar_estado($estado, 'sinActualizar')});
	$contrato.change(function(){modificar_estado($estado, 'sinActualizar')});
	
	$div_formulario.submit(actualizarAlbaran);
});

function funcion_onfocus(elemento, msg){
	return elemento.value=(elemento.value==msg) ? '' : elemento.value;
}

function funcion_onblur(elemento, msg){
	return elemento.value=(elemento.value=='') ? msg : elemento.value;
}

function modificar_estado($elemento, $estado){
	$elemento.removeClass('sinActualizar enProceso actualizado');
	$elemento.addClass($estado);
}

function actualizarAlbaran(event){
	event.preventDefault();
	
	if($estado.hasClass('sinActualizar')){
		$.ajax({
			url: ruta_ajax_registro_albaran,
			type: "POST",
			data: {
				idAlbaran: $idAlbaran.val(),
				numeroAlbaran: $numeroAlbaran.val(),
				contratoId: $contrato.val(),
				fechaRealizacion: $fechaRealizacion.val(),
				fechaVencimiento: $fechaVencimiento.val()
			},
			success: procesarAlbaranRegistrado
		});
	}
}

function procesarAlbaranRegistrado(data){
	if(data.estado){
		$idAlbaran.val(data.idAlbaran);
		
		modificar_estado($estado, 'actualizado');
		habilitarJaulaLibros();
	}
	else{
		//Errores
	}
}

function habilitarJaulaLibros(){
	$div_jaula_libros.show();
	$div_libros = $div_jaula_libros.find('.libros-albaran');
	$div_libros.accordion({
        header: "> div > h3",
        active: true,
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
	
	$boton_anadir_libro = $div_jaula_libros.find('.botonAnadir');
	$boton_anadir_libro.click(anadir_libro_nuevo);
	
}

var prototipoLibro;

prototipoLibro = '<div id="libro-%isbn_id%" class="libro" data="1">';
prototipoLibro += '<h3>';
prototipoLibro += '<div class="borrarLibro">-</div>';
prototipoLibro += '<div class="estadoAcordeon sinActualizar"></div>';
prototipoLibro += '<input id="libro-%isbn_id%-isbn" class="isbnAcordeon" maxlength="13" value="%isbn%" onfocus="funcion_onfocus(this, \'ISBN\')" onblur="funcion_onblur(this, \'ISBN\')" type="text">';
prototipoLibro += '</h3>';
prototipoLibro += '<div class="jaula-datos-libro">';
prototipoLibro += '<div class="datos"></div>';
prototipoLibro += '<div class="ejemplares"></div>';
prototipoLibro += '</div>';
prototipoLibro += '</div>';

var i = 1;

function anadir_libro_nuevo(){
	anadir_libro();
}

function anadir_libro_cargados_ajax(){
	anadir_libro('isbn');
}

function anadir_libro(isbn){
	var isbnId, isbnTitulo, $libroAnadido, $jaulaEjemplares;
	
	if(isbn == undefined){
		isbnId = i++;
		isbnTitulo = 'ISBN';
	}
	else{
		isbnId = isbn;
		isbnTitulo = isbn;
	}
	
	$libroAnadido = $(prototipoLibro.replace('%isbn_id%', isbnId).replace('%isbn_id%', isbnId).replace('%isbn%', isbnTitulo));
	
	$borrarLibro = $libroAnadido.find('.borrarLibro');
	$estadoLibro = $libroAnadido.find('.estadoAcordeon');
	$isbnAcordeon = $libroAnadido.find('.isbnAcordeon');

	$datosLibro = $libroAnadido.find('.datos');
	
	$borrarLibro.click(eventoClickBorrarLibro);
	$isbnAcordeon.click(prevenirDefault);
	
	$isbnAcordeon.autocomplete({
		source: ajaxCargarDatosLibroDesdeAutocomplete,
		select: cargarDatosLibroDesdeAutocomplete
	});
	
	$div_libros.append($libroAnadido);
	
	$datosLibro.load(ruta_ajax_plantilla_datos_libro, ajaxCargarNuevoLibroAnadido);		
				
	actualizarAcordeones();
}

function ajaxCargarNuevoLibroAnadido(response){
	$cargaAjax = $(this);
	$ejemplaresLibro = $cargaAjax.next();
	
	$botonSubmit = $cargaAjax.find('input[type="submit"]');
	
	$ejemplaresLibro.load(ruta_ajax_plantilla_ejemplares_libro, ajaxCargarEjemplaresParaNuevoLibroAnadido);
	
	actualizarAcordeones();
}

function ajaxCargarEjemplaresParaNuevoLibroAnadido(response){
	$respuesta = $(response);
	$jaulaEjemplares = $respuesta.find('.jaula-ejemplares');
		
	$jaulaEjemplares.accordion({
        header: "> div > h5",
        active: true,
        collapsible: true,
        icons: { "header": "ui-icon-plus", "headerSelected": "ui-icon-minus" }
      })
      .sortable({
        axis: "y",
        handle: "h5",
        stop: function( event, ui ) {
          ui.item.children( "h5" ).triggerHandler( "focusout" );
        }
      });
	
	actualizarAcordeones();
}

function ajaxCargarDatosLibroDesdeAutocomplete( request, response ){
	$.ajax({
        url: ruta_ajax_get_isbn_existente,
        data: {
          isbn: request.term,
          maxRows: 12
        },
        type: "POST",
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

function cargarDatosLibroDesdeAutocomplete( event, ui ) {
	id=event.target.id;
    
    $abuelo = $('#'+id).parent().parent();
    
    $datosLibroDiv = $abuelo.find('.datos');
    
    $isbn = $datosLibroDiv.find('input[name="isbn"]');
    $isbn.val(ui.item.isbn);
    
    $titulo = $datosLibroDiv.find('input[name="titulo"]');
    $titulo.val(ui.item.titulo);
    
    $editorial = $datosLibroDiv.find('input[name="editorial"]');
    $editorial.val(ui.item.editorial);

    $autores = $datosLibroDiv.find('.autores');
    $estilos = $datosLibroDiv.find('.estilos');
    
    $.ajax({
    	url: ruta_ajax_get_datos_libro,
    	type: "POST",
    	data: {
    		isbn: ui.item.isbn
    	},
    	success: function(data){
    		$autores.children('input').each(function(){
    			$inputCheckbox = $(this);
    			
    			if($.inArray(parseInt($inputCheckbox.val()), data.autores) > -1){
    				$inputCheckbox.attr('checked', true);
    			}
    		});
    		
    		$estilos.children('input').each(function(){
    			$inputCheckbox = $(this);
    			
    			if($.inArray(parseInt($inputCheckbox.val()), data.estilos) > -1){
    				$inputCheckbox.attr('checked', true);
    			}
    		});
    		
    		
    	}
    });
  }

function eventoClickBorrarLibro(event){
	
	
	return prevenirDefault(event);
}

function prevenirDefault(event){
	event.preventDefault();
	return false;
}

function actualizarAcordeones(){
	$('.ui-accordion').accordion("refresh");
}





















