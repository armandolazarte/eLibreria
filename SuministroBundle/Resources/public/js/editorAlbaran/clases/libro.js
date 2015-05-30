function Libro(albaran, isbn, existenciasArray){
	this.albaran;
	
	this.id;
	this.divId;
	
	this.tipo = 'libro';
	
	this.indexArray;
	
	this.isbnAutocomplete;
	this.botonCopiarInfo;
	this.estadoGlobal;
	this.estado;
	this.botonBorrarLibro;
	
	this.formDatosLibro;
	
	this.isbn;
	this.titulo;
	this.editorial;
	this.autores;
	this.estilos;
	
	this.botonActualizar;
	
	this.existencias = new Array();
	
	this.botonAnadirEexistencia;
	this.botonAnadirExistenciaMasiva;
	this.contenedorExistencias;
	this.masivoExistencias;
	
	this.init = function(albaran, isbn, existenciasArray){
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
			url: ruta_ajax_plantilla_libro,
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
				this.formDatosLibro = $plantilla.find('.form-datos-libro');
				
				this.botonActualizar = $plantilla.find('input[name="actualizar"]');
				
				this.botonAnadirExistencia = $plantilla.find('.anadirExistencia');
				this.botonAnadirExistenciaMasiva = $plantilla.find('.anadirExistenciasMasivas');
				this.contenedorExistencias = $plantilla.find('.jaula-existencias');
				this.masivoExistencias = $plantilla.find('.jaula-existencias-masivo');

				this.isbnAutocomplete.click(function(evento){evento.preventDefault(); return false;});
				this.isbnAutocomplete.autocomplete({
					source: function(request, response){
						$.ajax({
					        url: ruta_ajax_buscar_libros,
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
						
						libro.cargarInfoLibroAjax(new Array());
					}
				});
				
				this.editorial.autocomplete({
					source: function( request, response ){
						$.ajax({
							url: ruta_ajax_buscar_editoriales,
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
				
				this.botonCopiarInfo.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.copiarInfo();});
				this.botonBorrarLibro.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.borrar(); return false;});
				
				this.formDatosLibro.submit(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.actualizar(); return false;});
				this.botonAnadirExistencia.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.anadirExistenciaNueva(); return false;});
				this.botonAnadirExistenciaMasiva.click(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.anadirExistenciaNuevaConDatos(); return false;});
				
				this.isbn.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.titulo.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.editorial.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.autores.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
				this.estilos.change(this, function(evento){evento.preventDefault(); var libroActual = evento.data; libroActual.des(); return false;});
								
				if(isbn !== undefined){
					this.isbn.val(isbn);
					this.isbnAutocomplete.val(isbn);
					this.cargarInfoLibroAjax(existenciasArray);
				}
				
				this.setLibroEnContenedor($plantilla);
			}
		});
	}
	
	this.init(albaran, isbn, existenciasArray);
	
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
	
	this.getId = function(){
		return this.isbn.val();
	}
	
	this.isAct = function(){
		var res = false;
		
		if(this.estado != undefined){
			res = this.estado.hasClass('actualizado');
		}
		
		return res;
	}
	
	this.isActGlobal = function(){
		var res = false;
		
		if(this.isAct()){
			var elementosActualizados = true;
			
			for(var i = 0; i < this.existencias.length; i++){
				elementosActualizados &= this.existencias[i].isAct();
			}
			
			if(elementosActualizados){
				res = true;
			}
		}
		
		return res;
	}
	
	this.setLibroEnContenedor = function(cuerpo){
		cuerpo.prependTo(this.albaran.contenedorItems);
		this.albaran.contenedorItems.accordion("refresh");
		this.albaran.actItem();
	}
	
	this.copiarInfo = function(){
		this.isbn.val(this.isbnAutocomplete.val());
	}
	
	this.cargarInfoLibroAjax = function(existenciasArray){
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
				
				this.cargarExistenciasAjax(existenciasArray);
			}							
		});
		
		this.act();
		this.habilitarExistencias();
	}
	
	this.habilitarExistencias = function(){
		this.contenedorExistencias.accordion({
	        header: "> div > h5",
	        heightStyle: "content",
	        active: false,
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
		
		this.masivoExistencias.accordion({
	        header: "> div > h4",
	        heightStyle: "content",
	        active: false,
	        collapsible: true,
	        icons: { "header": "ui-icon-plus", "headerSelected": "ui-icon-minus" }
	      });
	
		this.contenedorExistencias.parent().parent().css('display','block');
	}
		
	this.borrar = function(){		
		var proceder = true;
		
		if(this.hasExistencias()){
			proceder &= confirm('Este libro contiene existencias que tambien se eliminarán.\n¿Desea continuar?');
		}
		
		if(proceder){
			this.des();
			
			//Borrar ejemplares
			for(var i = 0; i < this.existencias.length; i++){
				this.existencias[i].borrarExistencia();
			}
			
			this.albaran.borrarItem(this);
			this.divId.remove();
			this.albaran.actGlobal();
		}
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
						this.habilitarExistencias();
					}
				}
			});
		}
	}
	
	this.cargarExistenciasAjax = function(existenciasArray){
		if(existenciasArray.length > 0){
			for(var i = 0; i < existenciasArray.length; i++){
				this.anadirExistenciaExistente(existenciasArray[i]);
			}
		}
	}
	
	this.anadirExistenciaExistente = function(idExistencia){
		var existencia = new Existencia(this, idExistencia);
		var indexNuevoElemento = this.existencias.push(existencia);
	}
	
	this.anadirExistenciaNueva = function(){
		var existencia = new Existencia(this);
		var indexNuevoElemento = this.existencias.push(existencia);
	}
	
	this.anadirExistenciaNuevaConDatos = function(){
		var precioInit = this.masivoExistencias.find('input[name="precio"]');
		var ivaInit = this.masivoExistencias.find('select[name="iva"]');
		var beneficioInit = this.masivoExistencias.find('input[name="beneficio"]');
		var descuentoInit = this.masivoExistencias.find('input[name="descuento"]');
		var vendidoInit = this.masivoExistencias.find('input[name="vendido"]');
		var adquiridoInit = this.masivoExistencias.find('input[name="adquirido"]');
		
		var existencia = new Existencia(this, undefined, precioInit, ivaInit, descuentoInit, beneficioInit, vendidoInit, adquiridoInit);
		var indexNuevoElemento = this.existencias.push(existencia);
	}
	
	this.borrarExistencia = function(existencia){
		var indexElementoABorrar;
		
		for(var i = 0; i < this.existencias.length; i++){
			var existenciaActual = this.existencias[i];
			
			if(existenciaActual.id === existencia.id){
				indexElementoABorrar = i;
				break;
			}
		}
		
		this.existencias.splice(indexElementoABorrar, 1);
	}
	
	this.hasExistencias = function(){
		return this.existencias.length > 0;
	}
}

Libro.idTemporal = 1;