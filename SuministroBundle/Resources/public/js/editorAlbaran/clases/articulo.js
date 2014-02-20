function Articulo(albaran, ref, existenciasArray){
	this.albaran;
	
	this.id;
	this.divId;
	
	this.tipo = 'articulo';
	
	this.indexArray;
	
	this.refAutocomplete;
	this.botonCopiarInfo;
	this.estadoGlobal;
	this.estado;
	this.botonBorrarArticulo;
	this.formDatosArticulo;
	
	this.ref;
	this.titulo;
	
	this.botonActualizar;
	
	this.existencias = new Array();
	
	this.botonAnadirEexistencia;
	this.contenedorExistencias;
	
	this.init = function(albaran, ref, existenciasArray){
		this.albaran = albaran;
		
		if(ref === undefined){
			this.id = "articulo-" + Articulo.idTemporal;
			Articulo.idTemporal += 1;
		}
		else{
			this.id = "articulo-" + ref;
		}
		
		var articulo = this;
		
		$.ajax({
			url: ruta_ajax_plantilla_articulo,
			context: this,
			success: function(data){
				var articulo = this;
				var $plantilla = $(data.replace(/%ref%/g, this.id));
				
				this.divId = $plantilla;
				this.divId.data('articulo', articulo);
				
				this.refAutocomplete = $plantilla.find('.refAcordeon');
				this.botonCopiarInfo = $plantilla.find('.botonCopiarInfo');
				this.estadoGlobal = $plantilla.find('.estadoGlobal');
				this.estado = $plantilla.find('.estado');
				this.botonBorrarArticulo = $plantilla.find('.borrarArticulo');
				
				this.ref = $plantilla.find('input[name="ref"]');
				this.titulo = $plantilla.find('input[name="titulo"]');
				
				this.botonActualizar = $plantilla.find('input[name="actualizar"]');
				this.formDatosArticulo = $plantilla.find('.form-datos-articulo');
				
				this.botonAnadirExistencia = $plantilla.find('.anadirExistencia');
				this.contenedorExistencias = $plantilla.find('.jaula-existencias');

				this.refAutocomplete.click(function(evento){evento.preventDefault(); return false;});
				this.refAutocomplete.autocomplete({
					source: function(request, response){
						$.ajax({
					        url: ruta_ajax_buscar_articulos,
					        context: this,
					        data: {
					          ref: request.term,
					          maxRows: 12
					        },
					        type: "POST",
					        success: function( data ){
					          response( $.map( data.sugerencias, function(item){
					          		return {
						          		label: item.ref + " - " + item.titulo,
						          		value: item.ref,
						          		ref: item.ref
						          	};    
					          }));
					        }
					      });
					},
					select: function(evento, ui){
						articulo.ref.val(ui.item.ref);						
						
						articulo.cargarInfoArticuloAjax(new Array());
					}
				});
				
				
				this.botonCopiarInfo.click(this, function(evento){evento.preventDefault(); var artActual = evento.data; artActual.copiarInfo();});
				this.botonBorrarArticulo.click(this, function(evento){evento.preventDefault(); var artActual = evento.data; artActual.borrar(); return false;});
				
				this.formDatosArticulo.click(this, function(evento){evento.preventDefault(); var artActual = evento.data; artActual.actualizar(); return false;});
				this.botonAnadirExistencia.click(this, function(evento){evento.preventDefault(); var artActual = evento.data; artActual.anadirExistenciaNueva(); return false;});
				
				this.ref.change(this, function(evento){evento.preventDefault(); var artActual = evento.data; artActual.des(); return false;});
				this.titulo.change(this, function(evento){evento.preventDefault(); var artActual = evento.data; artActual.des(); return false;});
								
				if(ref !== undefined){
					this.ref.val(ref);
					this.refAutocomplete.val(ref);
					this.cargarInfoArticuloAjax(existenciasArray);
				}
				
				this.setArticuloEnContenedor($plantilla);
			}
		});
	}
	
	this.init(albaran, ref, existenciasArray);
	
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
		return this.ref.val();
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
	
	this.setArticuloEnContenedor = function(cuerpo){
		cuerpo.prependTo(this.albaran.contenedorItems);
		this.albaran.contenedorItems.accordion("refresh");
	}
	
	this.copiarInfo = function(){
		this.ref.val(this.refAutocomplete.val());
	}
	
	this.cargarInfoArticuloAjax = function(existenciasArray){
		$.ajax({
			url: ruta_ajax_get_datos_articulo,
			context: this,
			data: {
				ref: this.ref.val()
			},
			type: "POST",
			success: function(data){
				this.titulo.val(data.titulo);
				
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
	
		this.contenedorExistencias.parent().parent().css('display','block');
	}
		
	this.borrar = function(){		
		var proceder = true;
		
		if(this.hasExistencias()){
			proceder &= confirm('Este articulo contiene existencias que tambien se eliminarán.\n¿Desea continuar?');
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
			$.ajax({
				url: ruta_ajax_registro_articulo,
				type: "POST",
				context: this,
				data: {
					ref: this.ref.val(),
					titulo: this.titulo.val(),
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

Articulo.idTemporal = 1;