function MetodoPagoVista(){
	this.factura;
	
	this.contenedor;
	
	this.select;
	this.botonTransferencia;
	
	this.indicadorDIV;
	this.indicador;
}

MetodoPagoVista.prototype.init = function(f){
	this.factura = f;
	
	this.contenedor = $('#fMetPago');
	
	this.select = this.contenedor.find('select');
	this.botonTransferencia = this.contenedor.find('.opcionTransBanc');
	
	this.indicadorDIV = this.contenedor.find('.indicadorMetodoPago');
	
	this.setTrans();
}

MetodoPagoVista.prototype.val = function(){
	if(this.indicador == "select"){
		return this.select.val();
	}
	else{
		return "transferencia";
	}
}

MetodoPagoVista.prototype.setSelect = function(){
	this.select.off('click');
	this.botonTransferencia.on('click', this, this.haciaTrans);
	this.indicador = "select";
}

MetodoPagoVista.prototype.setTrans = function(){
	this.botonTransferencia.off('click');
	this.select.on('click', this, this.haciaSelect);
	this.indicador = "transferencia";
}

MetodoPagoVista.prototype.haciaTrans = function(e){
	var obj = e.data;
	obj.indicadorDIV.css('margin-left', '50%');
	obj.setTrans();
}

MetodoPagoVista.prototype.haciaSelect = function(e){
	var obj = e.data;
	obj.indicadorDIV.css('margin-left', '0');
	obj.setSelect();
}