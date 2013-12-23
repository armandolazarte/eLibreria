function Libro(albaran, isbn){
	this.albaran;
	
	this.id;
	
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
	
	this.isActualizado = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.init = function(albaran, isbn){
		this.albaran = albaran;
		
		if(isbn === undefined){
			this.id = Libro.idTemporal;
			Libro.idTemporal += 1;
		}
		else{
			this.id = isbn;
		}
		
		$.ajax({
			url: ruta_ajax_plantilla_datos_libro,
			context: this,
			success: function(data){
				var libro = this;
				var $plantilla = $(data.replace(/%isbn_id%/g, this.id));
				
				libro.isbnAutocomplete = $plantilla.find('.isbnAcordeon');
				libro.botonCopiarInfo = $plantilla.find('.botonCopiarInfo');
				libro.estado = $plantilla.find('.estadoLibro');
				libro.botonBorrarLibro = $plantilla.find('.borrarLibro');
				
				libro.isbn = $plantilla.find('input[name="isbn"]');
				libro.titulo = $plantilla.find('input[name="titulo"]');
				libro.editorial = $plantilla.find('input[name="editorial"]');
				libro.autores = $plantilla.find('.autores');
				libro.estilos = $plantilla.find('.estilos');
				
				libro.botonActualizar = $plantilla.find('input[name="actualizar"]');
				
				libro.botonAnadirEjemplar = $plantilla.find('.anadirEjemplar');
				libro.contenedorEjemplares = $plantilla.find('.jaula-ejemplares');

				libro.isbnAutocomplete.click(function(evento){evento.preventDefault(); return false;});
				libro.isbnAutocomplete.autocomplete({
					source: function(request, response){
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
					          			libro: libro,
						          		label: item.isbn + " - " + item.titulo + (item.editorial ? " [ " + item.editorial + " ]" : ""),
						          		value: item.isbn,
						          		isbn: item.isbn,
						          		titulo: item.titulo,
						          		editorial: item.editorial
						          	};    
					          }));
					        }
					      });
					},
					select: function(evento, ui){
						var libro = ui.item.libro;

						libro.isbn.val(ui.item.isbn);
						libro.titulo.val(ui.item.titulo);
						libro.editorial.val(ui.item.editorial);
					}
				});
				
				this.botonCopiarInfo.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.copiarInfo(); return false;});
				this.botonBorrarLibro.click(function(evento){evento.preventDefault(); return false;});
				
				this.botonActualizar.click(function(evento){evento.preventDefault(); return false;});
				this.botonAnadirEjemplar.click(function(evento){evento.preventDefault(); return false;});
								
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
}

Libro.idTemporal = 1;