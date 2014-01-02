function Libro(albaran, isbn){
	this.albaran;
	
	this.id;
	this.divId;
	
	this.indexArray;
	
	this.isbnAutocomplete;
	this.botonCopiarInfo;
	this.estadoGlobal;
	this.estado;
	this.botonBorrarLibro;
	
	this.isbn;
	this.titulo;
	this.editorial;
	this.autores;
	this.estilos;
	
	this.botonActualizar;
	
	this.ejemplares = new Array();
	
	this.botonAnadirEjemplar;
	this.contenedorEjemplares;
	
	this.init = function(albaran, isbn){
		this.albaran = albaran;
		
		if(isbn === undefined){
			this.id = "libro-" + Libro.idTemporal;
			Libro.idTemporal += 1;
		}
		else{
			this.id = "libro-" + isbn;
		}
		
		var libro = this;
		
		$.ajax({
			url: ruta_ajax_plantilla_datos_libro,
			context: this,
			success: function(data){
				var libro = this;
				var $plantilla = $(data.replace(/%isbn_id%/g, this.id));
				
				this.divId = $plantilla;
				this.divId.data('libro', libro);
				
				this.isbnAutocomplete = $plantilla.find('.isbnAcordeon');
				this.botonCopiarInfo = $plantilla.find('.botonCopiarInfo');
				this.estadoGlobal = $plantilla.find('.estadoGlobal');
				this.estado = $plantilla.find('.estado');
				this.botonBorrarLibro = $plantilla.find('.borrarLibro');
				
				this.isbn = $plantilla.find('input[name="isbn"]');
				this.titulo = $plantilla.find('input[name="titulo"]');
				this.editorial = $plantilla.find('input[name="editorial"]');
				this.autores = $plantilla.find('.autores');
				this.estilos = $plantilla.find('.estilos');
				
				this.botonActualizar = $plantilla.find('input[name="actualizar"]');
				
				this.botonAnadirEjemplar = $plantilla.find('.anadirEjemplar');
				this.contenedorEjemplares = $plantilla.find('.jaula-ejemplares');

				this.isbnAutocomplete.click(function(evento){evento.preventDefault(); return false;});
				this.isbnAutocomplete.autocomplete({
					source: function(request, response){
						$.ajax({
					        url: ruta_ajax_get_isbn_existente,
					        context: this,
					        data: {
					          isbn: request.term,
					          maxRows: 12
					        },
					        type: "POST",
					        success: function( data ){
					          response( $.map( data.sugerencias, function(item){
					          		return {
						          		label: item.isbn + " - " + item.titulo + (item.editorial ? " [ " + item.editorial + " ]" : ""),
						          		value: item.isbn,
						          		isbn: item.isbn
						          	};    
					          }));
					        }
					      });
					},
					select: function(evento, ui){
						libro.isbn.val(ui.item.isbn);						
						
						libro.cargarInfoLibroAjax();
					}
				});
				
				this.editorial.autocomplete({
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
				
				this.botonCopiarInfo.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.copiarInfo(); return false;});
				this.botonBorrarLibro.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.borrar(); return false;});
				
				this.botonActualizar.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.actualizar(); return false;});
				this.botonAnadirEjemplar.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.anadirEjemplarNuevo(); return false;});
				
				this.isbn.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.titulo.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.editorial.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.autores.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.estilos.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
								
				if(isbn !== undefined){
					this.isbn.val(isbn);
					this.isbnAutocomplete.val(isbn);
					this.cargarInfoLibroAjax();
				}
				
				this.setLibroEnContenedor($plantilla);
			}
		});
	}
	
	this.init(albaran, isbn);
	
	this.des = function(){
		modificar_estado(this.estado, 'sinActualizar');
		this.desGlobal();
	}
	
	this.desGlobal = function(){
		modificar_estado(this.estadoGlobal, 'sinActualizar');
		this.albaran.desGlobal();
	}
	
	this.act = function(){
		modificar_estado(this.estado, 'actualizado');
		this.actGlobal();
	}
	
	this.actGlobal = function(){
		if(this.isActGlobal()){
			modificar_estado(this.estadoGlobal, 'actualizado');
			this.albaran.actGlobal();
		}
	}
	
	this.isAct = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.isActGlobal = function(){
		var res = false;
		
		if(this.isAct()){
			var elementosActualizados = true;
			
			for(var i = 0; i < this.ejemplares.length; i++){
				elementosActualizados &= this.ejemplares[i].isAct();
			}
			
			if(elementosActualizados){
				res = true;
			}
		}
		
		return res;
	}
	
	this.setLibroEnContenedor = function(cuerpo){
		cuerpo.appendTo(this.albaran.contenedorLibros);
		this.albaran.contenedorLibros.accordion("refresh");
	}
	
	this.copiarInfo = function(){
		this.isbn.val(this.isbnAutocomplete.val());
	}
	
	this.cargarInfoLibroAjax = function(){
		$.ajax({
			url: ruta_ajax_get_datos_libro,
			context: this,
			data: {
				isbn: this.isbn.val()
			},
			type: "POST",
			success: function(data){
				this.titulo.val(data.titulo);
				this.editorial.val(data.editorial);
				
				var autores = data.autores;
				for(var i = 0; i < autores.length; i++){
					var autorId = autores[i];
					
					this.autores.children('input').each(function(){
						var elemento = $(this);
						
						if(elemento.val() == autorId){
							elemento.prop('checked', true);
						}
					});
				}
				
				var estilos = data.estilos;
				for(var i = 0; i < estilos.length; i++){
					var estiloId = estilos[i];
					
					this.estilos.children('input').each(function(){
						var elemento = $(this);
						
						if(elemento.val() == estiloId){
							elemento.prop('checked', true);
						}
					});
				}
				
				this.cargarEjemplaresAjax();
			}							
		});
		
		this.act();
		this.habilitarEjemplares();
	}
	
	this.habilitarEjemplares = function(){
		this.contenedorEjemplares.accordion({
	        header: "> div > h5",
	        heightStyle: "content",
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
	
		this.contenedorEjemplares.parent().parent().css('display','block');
	}
		
	this.borrar = function(){
		
	}
	
	this.actualizar = function(){
		if(!this.isAct()){		
			var autoresArray = new Array();
			var estilosArray = new Array();
			
			this.autores.children('input:checked').each(function(){
				autoresArray.push($(this).val());
			});
			
			autoresArray = JSON.stringify(autoresArray);
			
			this.estilos.children('input:checked').each(function(){
				estilosArray.push($(this).val());
			});
			
			estilosArray = JSON.stringify(estilosArray);
			
			$.ajax({
				url: ruta_ajax_registro_libro,
				type: "POST",
				context: this,
				data: {
					isbn: this.isbn.val(),
					titulo: this.titulo.val(),
					editorial: this.editorial.val(),
					autores: autoresArray,
					estilos: estilosArray
				},
				success: function(data){
					if(data.estado){
						this.act();
						this.habilitarEjemplares();
					}
				}
			});
		}
	}
	
	this.cargarEjemplaresAjax = function(){
		$.ajax({
			url: ruta_ajax_get_ejemplares_libro_albaran,
			context: this,
			data: {
				idAlbaran: this.albaran.idAlbaran.val(),
				isbn: this.isbn.val()
			},
			type: "POST",
			success: function(data){	
				var ejemplaresRecibidos = data.ejemplares;				
				for(var i = 0; i < ejemplaresRecibidos.length; i++){
					this.anadirEjemplarExistente(ejemplaresRecibidos[i]);
				}
			}
		});
	}
	
	this.anadirEjemplarExistente = function(idEjemplar){
		var ejemplar = new Ejemplar(this, idEjemplar);
		var indexNuevoElemento = this.ejemplares.push(ejemplar);
	}
	
	this.anadirEjemplarNuevo = function(){
		var ejemplar = new Ejemplar(this);
		var indexNuevoElemento = this.ejemplares.push(ejemplar);
	}
	
	this.borrarEjemplar = function(ejemplar){
		var indexElementoABorrar;
		
		for(var i = 0; i < this.ejemplares.length; i++){
			var ejemplarActual = this.ejemplares[i];
			
			if(ejemplarActual.id === ejemplar.id){
				indexElementoABorrar = i;
				break;
			}
		}
		
		this.ejemplares.splice(indexElementoABorrar, 1);
	}
}

Libro.idTemporal = 1;