function FacturaVista(){
	this.factura;
	
	this.numDoc;
	this.fechaEmision;
	
	this.metodoPago = new MetodoPagoVista();
	
	this.itemsFactura = new ItemFacturaVista();
		
	this.totales = new TotalesVista();
	
	this.fechasVencimiento = new FechaVencimientoFacturaVista();
	
	this.observaciones;
	
	this.botonGuardar;
	
	this.estado;
}

FacturaVista.prototype.init = function(factura){
	this.factura = factura;
	
	this.numDoc = $('#fNumDoc');
	this.fechaEmision = $('#fFecEmi');
	this.observaciones = $('#fObs');
	this.botonGuardar = $('#fBotonGuardar');
	this.estado = $('#fEstado');
	
	this.metodoPago.init(this);
	this.itemsFactura.init(this);
	this.totales.init(this);
	this.fechasVencimiento.init(this);

	this.inicializarInput(this.numDoc);
	this.inicializarInput(this.fechaEmision);
}

FacturaVista.prototype.inicializarInput = function(i){
	i.data('textInd', i.val());
	i.css('color', '#d9d9d9');
	
	i.change(function(e){
		var $i = $(this);
		
		if($i.val() == ""){
			$i.val($i.data('textInd'));
			$i.css('color', '#d9d9d9');
		}
	});
	
	i.click(function(e){
		var $i = $(this);
		
		if($i.val() == $i.data('textInd')){
			$i.val('');
			$i.css('color', 'inherit');
		}
	});
	
	i.focusout(function(e){
		var $i = $(this);
		
		if($i.val() == ""){
			$i.val($i.data('textInd'));
			$i.css('color', '#d9d9d9');
		}
	});
}

FacturaVista.prototype.actualizarTotales = function(arrayIvas){
	this.totales.actualizarTotales(arrayIvas);
}

FacturaVista.prototype.getMetodoPago = function(){
	return this.metodoPago.val();
}