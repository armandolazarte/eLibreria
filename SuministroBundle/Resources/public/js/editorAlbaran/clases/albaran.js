function Albaran(idAlb, numIden, idContr, fechaRea, fechaVen, est, bAct, bNuevo, cLibro){
	//Atributos privados
	var idAlbaran;
	
	var numeroIdentificacion;
	var idContratoDistribucion;
	var fechaRealizacion;
	var fechaVencimiento;
	
	var estado;
	var botonActualizar;
	
	var libros = new Array();
	var numLibroNuevo = 1;
	
	var botonAnadirLibro;
	var contenedorLibros;
	
	//Metodos	
	this.getIdAlbaran = function(){
		return idAlbaran.val();
	}
	
	this.getNumeroIdentificacion = function(){
		return numeroIdentificacion.val();
	}
	
	this.getContratoDistribucion = function(){
		return idContratoDistribucion.val();
	}
	
	this.getFechaRealizacion = function(){
		return fechaRealizacion.val();
	}
	
	this.getFechaVencimiento = function(){
		return fechaVencimiento.val();
	}
	
	this.getEstado = function(){
		return estado;
	}
	
	this.getBotonActualizar = function(){
		return botonActualizar;
	}
	
	this.getBotonAnadirLibro = function(){
		return botonAnadirLibro;
	}
	
	this.getContenedorLibros = function(){
		return contenedorLibros;
	}
	
	var setAlbaranModificado = function(){
		modificar_estado(estado, 'sinActualizar');
	}
	
	var setAlbaranActualizado = function(){
		modificar_estado(estado, 'actualizado');
	}
	
	var verContenedorLibro = function(){
		contenedorLibros.accordion({
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
		
		contenedorLibros.parent().css('display', 'block');
	}
	
	//Contructor
	var init = function(idAlb, numIden, idContr, fechaRea, fechaVen, est, bAct, bNuevo, cLibro){		
		idAlbaran = idAlb;
		
		numeroIdentificacion = numIden;
		idContratoDistribucion = idContr;
		fechaRealizacion = fechaRea;
		fechaVencimiento = fechaVen;
		
		estado = est;
		botonActualizar = bAct;
		
		botonAnadirLibro = bNuevo;
		contenedorLibros = cLibro;

		numeroIdentificacion.change(function(){setAlbaranModificado();});
		idContratoDistribucion.change(function(){setAlbaranModificado();});
		fechaRealizacion.change(function(){setAlbaranModificado();});
		fechaVencimiento.change(function(){setAlbaranModificado();});
				
		if(idAlbaran.val() != ""){
			//Albaran con datos
			
			setAlbaranActualizado();
			verContenedorLibro();
		}
	}
	
	init(idAlb, numIden, idContr, fechaRea, fechaVen, est, bAct, bNuevo, cLibro);
	
	//
}

