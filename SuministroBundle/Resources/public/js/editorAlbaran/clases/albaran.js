function Albaran(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, est, bAct, bNuevo, cLibro){
	//Atributos privados
	this.idAlbaran;
	
	this.formularioAlbaran;
	
	this.numeroIdentificacion;
	this.idContratoDistribucion;
	this.fechaRealizacion;
	this.fechaVencimiento;
	
	this.estado;
	this.botonActualizar;
	
	this.libros = new Array();
	
	this.botonAnadirLibro;
	this.contenedorLibros;
	
	this.setAlbaranModificado = function(){
		modificar_estado(this.estado, 'sinActualizar');
	}
	
	this.setAlbaranActualizado = function(){
		var actualizar = true;
		var i = 0;
		
		while(actualizar && this.libros[i]){
			var libro = this.libros[i];
			
			if(!libro.isActualizado()){
				actualizar = false;
			}
		}
		
		if(actualizar){
			modificar_estado(this.estado, 'actualizado');
		}
	}
	
	this.isEstado = function(estadoAComprobar){
		return this.estado.hasClass(estadoAComprobar);
	}
	
	this.verContenedorLibro = function(){
		this.contenedorLibros.accordion({
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
		
		this.contenedorLibros.parent().css('display', 'block');
	}
	
	this.actualizarInformacion = function(){
		if(this.isEstado('sinActualizar')){
			$.ajax({
				url: ruta_ajax_registro_albaran,
				context: this,
				type: "POST",
				data: {
					idAlbaran: this.idAlbaran.val(),
					numeroAlbaran: this.numeroIdentificacion.val(),
					contratoId: this.idContratoDistribucion.val(),
					fechaRealizacion: this.fechaRealizacion.val(),
					fechaVencimiento: this.fechaVencimiento.val()
				},
				success: function(data){
					if(data.estado){
						this.idAlbaran.val(data.idAlbaran);
						this.setAlbaranActualizado();
						this.verContenedorLibro();
					}
				}
			});
		}		
	}
	
	this.anadirLibroExistente = function(isbn){
		
	}
	
	this.anadirLibroNuevo = function(){
		
	}
	
	this.cargarLibrosAlbaranAJAX = function(){
		
	}
	
	//Contructor
	this.init = function(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, est, bAct, bNuevo, cLibro){		
		this.idAlbaran = idAlb;
		this.formularioAlbaran = formAlb;
		
		this.numeroIdentificacion = numIden;
		this.idContratoDistribucion = idContr;
		this.fechaRealizacion = fechaRea;
		this.fechaVencimiento = fechaVen;
		
		this.estado = est;
		this.botonActualizar = bAct;
		
		this.botonAnadirLibro = bNuevo;
		this.contenedorLibros = cLibro;

		this.numeroIdentificacion.change(function(){$albaran.setAlbaranModificado();});
		this.idContratoDistribucion.change(function(){$albaran.setAlbaranModificado();});
		this.fechaRealizacion.change(function(){$albaran.setAlbaranModificado();});
		this.fechaVencimiento.change(function(){$albaran.setAlbaranModificado();});
		
		this.formularioAlbaran.submit(function(evento){evento.preventDefault(); $albaran.actualizarInformacion(); return false;});
		
		this.botonAnadirLibro.click(function(evento){evento.preventDefault(); $albaran.anadirLibroNuevo(); return false;});
				
		if(this.idAlbaran.val() != ""){
			//Albaran con datos
			
			this.setAlbaranActualizado();
			this.verContenedorLibro();

			this.cargarLibrosAlbaranAJAX();
		}
	}
	
	this.init(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, est, bAct, bNuevo, cLibro);
}