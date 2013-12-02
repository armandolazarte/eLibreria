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
        heightStyle: "content",
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

prototipoLibro = '<div id="libro-%isbn_id%" class="libro">';
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
	
	var $libroAnadido = $(prototipoLibro.replace('%isbn_id%', isbnId).replace('%isbn_id%', isbnId).replace('%isbn%', isbnTitulo));
	
	var $borrarLibro = $libroAnadido.find('.borrarLibro');
	var $estadoLibro = $libroAnadido.find('.estadoAcordeon');
	var $isbnAcordeon = $libroAnadido.find('.isbnAcordeon');

	var $datosLibro = $libroAnadido.find('.datos');
	
	$borrarLibro.click(eventoClickBorrarLibro);
	$isbnAcordeon.click(prevenirDefault);
	
	$isbnAcordeon.autocomplete({
		source: ajaxCargarDatosLibroDesdeAutocomplete,
		select: cargarDatosLibroDesdeAutocomplete
	});
	
	$div_libros.append($libroAnadido);
	
	$libroAnadido.data('nEjemplares', 1);
	
	$datosLibro.load(ruta_ajax_plantilla_datos_libro, ajaxCargarNuevoLibroAnadido);		
				
	actualizarAcordeones();
}

function ajaxCargarNuevoLibroAnadido(response){
	var $cargaAjax = $(this);
	var $ejemplaresLibro = $cargaAjax.next();
	
	var $estadoLibro = $cargaAjax.parent().parent().find('.estadoAcordeon');
    
    var $isbn = $cargaAjax.find('input[name="isbn"]');
    var $titulo = $cargaAjax.find('input[name="titulo"]');
    var $editorial = $cargaAjax.find('input[name="editorial"]');
	
	$editorial.autocomplete({
		source: function( request, response ){
			$.ajax({
				url: ruta_ajax_get_editoriales_existente,
				type: "POST",
				data: {
						editorial: request.term
					},
				success: function(data){
						response( $.map(data.sugerencias, function(item){
								return {
										label: item.nombre,
										value: item.nombre
									};
							}) );
					}
			});
		}
    });
	
	$isbn.change(function(evento){modificar_estado($estadoLibro, 'sinActualizar')});
    $titulo.change(function(){modificar_estado($estadoLibro, 'sinActualizar')});
    $editorial.change(function(){modificar_estado($estadoLibro, 'sinActualizar')});

    var $autores = $cargaAjax.find('.autores');
    var $estilos = $cargaAjax.find('.estilos');
    
    $autores.find('input').each(function(){
    	$(this).change(function(){modificar_estado($estadoLibro, 'sinActualizar')});
    });
    
    $estilos.find('input').each(function(){
    	$(this).change(function(){modificar_estado($estadoLibro, 'sinActualizar')});
    });
	
    var $botonSubmit = $cargaAjax.find('input[type="submit"]');
	$botonSubmit.click(actualizarInformacionLibro);
	
	$ejemplaresLibro.load(ruta_ajax_plantilla_ejemplares_libro, ajaxCargarEjemplaresParaNuevoLibroAnadido);
	
	actualizarAcordeones();
}

function ajaxCargarEjemplaresParaNuevoLibroAnadido(response){
	var $respuesta = $(this);
	var $jaulaEjemplares = $respuesta.find('.jaula-ejemplares');
		
	$jaulaEjemplares.accordion({
        header: "> div > h5",
        heightStyle: "content",
        collapsible: true,
        icons: { "header": "ui-icon-plus", "headerSelected": "ui-icon-minus" }
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
	var id=event.target.id;
	    
    var $abuelo = $('#'+id).parent().parent();
    
    var $estadoLibro = $abuelo.find('.estadoAcordeon');
    var $datosLibroDiv = $abuelo.find('.datos');
    
    var  $isbn = $datosLibroDiv.find('input[name="isbn"]');
    $isbn.val(ui.item.isbn);
    
    var $titulo = $datosLibroDiv.find('input[name="titulo"]');
    $titulo.val(ui.item.titulo);
    
    var $editorial = $datosLibroDiv.find('input[name="editorial"]');
    $editorial.val(ui.item.editorial);
    
    $.ajax({
    	url: ruta_ajax_get_datos_libro,
    	context: $abuelo,
    	type: "POST",
    	data: {
    		isbn: ui.item.isbn
    	},
    	success: function(data){
    		var $autores = $(this).find('.autores');
    		var $estilos = $(this).find('.estilos');
    		
    		$autores.children('input').each(function(){
    			var $inputCheckbox = $(this);
    			
    			if($.inArray(parseInt($inputCheckbox.val()), data.autores) > -1){
    				$inputCheckbox.attr('checked', true);
    			}
    		});
    		
    		$estilos.children('input').each(function(){
    			var $inputCheckbox = $(this);
    			
    			if($.inArray(parseInt($inputCheckbox.val()), data.estilos) > -1){
    				$inputCheckbox.attr('checked', true);
    			}
    		});
    		
    		modificar_estado($estadoLibro, 'actualizado');
    		habilitarEjemplares($(this));
    		actualizarAcordeones();
    	}
    });

	actualizarAcordeones();
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

function actualizarInformacionLibro(evento){
	$datosLibro = $(evento.target).parent().parent().parent().parent(); 
	$estadoLibro = $datosLibro.find('.estadoAcordeon');
	isbn = $datosLibro.find('input[name="isbn"]').val();
	
	if($estadoLibro.hasClass('sinActualizar') && isbn != ""){
		titulo = $datosLibro.find('input[name="titulo"]').val();
		editorial = $datosLibro.find('input[name="editorial"]').val();
		$autores = $datosLibro.find('.autores');
		$estilos = $datosLibro.find('.estilos');
	
		autoresArray = new Array();
		estilosArray = new Array();
		
		$autores.children('input:checked').each(function(){
			autoresArray.push($(this).val());
		});
		
		autoresArray = JSON.stringify(autoresArray);
		
		$estilos.children('input:checked').each(function(){
			estilosArray.push($(this).val());
		});
		
		estilosArray = JSON.stringify(estilosArray);
		
		$.ajax({
			url: ruta_ajax_registro_libro,
			type: "POST",
			context: $datosLibro,
			data: {
				isbn: isbn,
				titulo: titulo,
				editorial: editorial,
				autores: autoresArray,
				estilos: estilosArray
			},
			success: actualizarDatosLibro
		});
		
		//Ajax para actualizar / crear el libro y si todo ha ido bien, se cambia estado a OK y se muestra ejemplares
		
	}
}

function actualizarDatosLibro(data){
	if(data.estado){
		var $estadoLibro = $(this).find('.estadoAcordeon');
		modificar_estado($estadoLibro, 'actualizado');
		habilitarEjemplares($(this));
		$(this).parent().accordion("refresh");
	}
}

function habilitarEjemplares($divJaulaLibro){
	var $ejemplares = $divJaulaLibro.find('.ejemplares');
	var $botonAnadirEjemplar = $ejemplares.find('.anadirEjemplar');
	$ejemplares.show();
	
	$botonAnadirEjemplar.click(anadirEjemplarALibro);
}

function anadirEjemplarALibro(event){
	var $jaulaEjemplares = $(event.target).parent().next('.jaula-ejemplares');
	
	$.ajax({
		url: ruta_ajax_plantilla_ejemplar,
		context: $jaulaEjemplares,
		success: inyectarPlantillaEjemplaresEnJaula
	});
}

function inyectarPlantillaEjemplaresEnJaula(data){
	var $contenedorLibro = $(this).parent().parent().parent().parent();
	var idEjemplar = $contenedorLibro.data('nEjemplares');
	
	data = data.replace(/%idEjemplar%/g, idEjemplar);
	$contenedorLibro.data('nEjemplares', parseInt(idEjemplar) + 1);
	
	$(this).append(data);

	$(this).accordion("refresh");
	$contenedorLibro.parent().accordion("refresh");
}
















