function Libro(albaran, isbn){
	this.albaran;
	
	this.id;
	this.divId;
	
	this.isbnAutocomplete;
	this.botonCopiarInfo;
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
			this.id = Libro.idTemporal;
			Libro.idTemporal += 1;
		}
		else{
			this.id = isbn;
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
				this.estado = $plantilla.find('.estadoLibro');
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
				
				this.botonCopiarInfo.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.copiarInfo(); return false;});
				this.botonBorrarLibro.click(function(evento){evento.preventDefault(); return false;});
				
				this.botonActualizar.click(function(evento){evento.preventDefault(); return false;});
				this.botonAnadirEjemplar.click(function(evento){evento.preventDefault(); return false;});
								
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
				
				
			}							
		});
		
		this.setLibroActualizado();
		this.habilitarEjemplares();
	}
	
	this.habilitarEjemplares = function(){
		
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
}

Libro.idTemporal = 1;