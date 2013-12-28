function Albaran(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, estGlobal, est, bAct, bNuevo, cLibro){
	//Atributos privados
	this.idAlbaran;
	
	this.formularioAlbaran;
	
	this.numeroIdentificacion;
	this.idContratoDistribucion;
	this.fechaRealizacion;
	this.fechaVencimiento;
	
	this.estado;
	this.estadoGlobal;
	this.botonActualizar;
	
	this.libros = new Array();
	
	this.botonAnadirLibro;
	this.contenedorLibros;
	
	this.des = function(){
		modificar_estado(this.estado, 'sinActualizar');
		this.desGlobal();
	}
	
	this.desGlobal = function(){
		modificar_estado(this.estadoGlobal, 'sinActualizar');
	}
	
	this.act = function(){
		modificar_estado(this.estado, 'actualizado');
		this.actGlobal();
	}
	
	this.actGlobal = function(){
		if(this.isActGlobal()){
			modificar_estado(this.estadoGlobal, 'actualizado');
		}
	}
	
	this.isAct = function(){
		return this.estado.hasClass('actualizado');
	}
	
	this.isActGlobal = function(){
		var res = false;
		
		if(this.isAct()){
			var elementosActualizados = true;
			
			for(var i = 0; i < this.libros.length; i++){
				elementosActualizados &= this.libros[i].isActGlobal();
			}
			
			if(elementosActualizados){
				res = true;
			}
		}
		
		return res;
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
						this.act();
						this.verContenedorLibro();
					}
				}
			});
		}		
	}
	
	this.anadirLibroExistente = function(isbn){
		this.libros.push(new Libro(this, isbn));
	}
	
	this.anadirLibroNuevo = function(){
		this.desGlobal();
		this.libros.push(new Libro(this));
	}
	
	this.cargarLibrosAlbaranAJAX = function(){
		var albaran = this;
		$.ajax({
			url: ruta_ajax_get_libros_albaran,
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
	
	//Contructor
	this.init = function(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, estGlobal, est, bAct, bNuevo, cLibro){		
		this.idAlbaran = idAlb;
		this.formularioAlbaran = formAlb;
		
		this.numeroIdentificacion = numIden;
		this.idContratoDistribucion = idContr;
		this.fechaRealizacion = fechaRea;
		this.fechaVencimiento = fechaVen;
		
		this.estado = est;
		this.estadoGlobal = estGlobal;
		this.botonActualizar = bAct;
		
		this.botonAnadirLibro = bNuevo;
		this.contenedorLibros = cLibro;

		this.numeroIdentificacion.change(function(){$albaran.des();});
		this.idContratoDistribucion.change(function(){$albaran.des();});
		this.fechaRealizacion.change(function(){$albaran.des();});
		this.fechaVencimiento.change(function(){$albaran.des();});
		
		this.formularioAlbaran.submit(function(evento){evento.preventDefault(); $albaran.act(); return false;});
		
		this.botonAnadirLibro.click(function(evento){evento.preventDefault(); $albaran.anadirLibroNuevo(); return false;});
				
		if(this.idAlbaran.val() != ""){
			//Albaran con datos
			
			this.act();
			this.verContenedorLibro();

			this.cargarLibrosAlbaranAJAX();
		}
	}
	
	this.init(idAlb, formAlb, numIden, idContr, fechaRea, fechaVen, estGlobal, est, bAct, bNuevo, cLibro);
}