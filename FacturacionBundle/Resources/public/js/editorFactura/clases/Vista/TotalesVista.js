function TotalesVista(){
	this.facturaVista;
	
	this.wrapTotales;
	this.plantillaIVA;
	
	this.ivas = {};
	
	this.wrapTotal;
}

TotalesVista.prototype.init = function(f){
	this.facturaVista = f;
	
	this.wrapTotales = $('#totalFactura_totales');
	this.plantillaIVA = this.wrapTotales.data('plantillaiva');
	
	this.wrapTotal = $('#wrap_total');
	this.modificarTotal((0.0).toFixed(2));
}

TotalesVista.prototype.modificarTotal = function(t){
	this.wrapTotal.find('.valor').html(t + ' €');
}

TotalesVista.prototype.actualizarTotales = function(contenedorIva){
	//arrayIvas - ContenedorIVA	
	var arrayIva = contenedorIva.getArrayIVAS();
	
	for(tipo in arrayIva){
		var iva = arrayIva[tipo];
		var p = this.plantillaIVA;		
		var i = this.ivas[tipo];
		
		if(typeof i == "undefined"){
			i = $(p);
			
			i.find('.iva').html(iva.getTipo() + ' %');
			i.find('.base').data('valor', iva.getBaseImponible());
			i.find('.base .valor').html(iva.getBaseImponible() + ' €');
			i.find('.imp .valor').html(iva.getIVA() + ' €');
			
			i.insertBefore('#wrap_total');			
			this.ivas[tipo] = i;
		}
		else{			
			i.find('.iva').html(iva.getTipo() + ' %');
			i.find('.base').data('valor', iva.getBaseImponible());
			i.find('.base .valor').html(iva.getBaseImponible() + ' €');
			i.find('.imp .valor').html(iva.getIVA() + ' €');			
		}		
	}
	
	for(tipo in this.ivas){
		var iva = this.ivas[tipo];
		
		if(parseFloat(iva.find('.base').data('valor')) <= 0.0){
			iva.remove();
			
			delete this.ivas[tipo];
		}
	}
	
	this.modificarTotal(contenedorIva.getTotal());
}