function Libro(albaran, isbn){
	this.albaran;
	
	this.id;
	this.divId;
	
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
				
				this.isbn.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; modificar_estado(libroActual.estado, 'sinActualizar'); return false;});
				this.titulo.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; modificar_estado(libroActual.estado, 'sinActualizar'); return false;});
				this.editorial.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; modificar_estado(libroActual.estado, 'sinActualizar'); return false;});
				this.autores.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; modificar_estado(libroActual.estado, 'sinActualizar'); return false;});
				this.estilos.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; modificar_estado(libroActual.estado, 'sinActualizar'); return false;});
								
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
				
				this.habilitarEjemplares();
				this.cargarEjemplaresAjax();
			}							
		});
		
		this.setLibroActualizado();
		this.habilitarEjemplares();
	}
	
	this.habilitarEjemplares = function(){
		this.contenedorEjemplares.parent().parent().css('display','block');
	}
	
	this.setLibroActualizado = function(){
		var todoActualizado = true;
		
		for(var i = 0; i < this.ejemplares.length; i++){
			todoActualizado &= this.ejemplares[i].isActualizado();
		}
		
		if(todoActualizado){
			modificar_estado(this.estado, 'actualizado');
		}
	}
	
	this.isActualizado = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.borrar = function(){
		
	}
	
	this.actualizar = function(){
		if(!this.isActualizado()){		
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
						modificar_estado(this.estado, 'actualizado');
						this.habilitarEjemplares();
					}
				}
			});
		}
	}
	
	this.cargarEjemplaresAjax = function(){
		$.ajax({
			url: ruta_ajax_get_ejemplares_libro_albaran,
			data: {
				idAlbaran: this.idAlbaran.val()
			},
			type: "POST",
			success: function(data){	
				var librosRecibidos = data.libros;				
				for(var i = 0; i < librosRecibidos.length; i++){
					albaran.anadirLibroExistente(librosRecibidos[i]);
				}
			}
		});
	}
	
	this.anadirEjemplarExistente = function(idEjemplar){
		
	}
	
	this.anadirEjemplarNuevo = function(){
		
	}
}

Libro.idTemporal = 1;