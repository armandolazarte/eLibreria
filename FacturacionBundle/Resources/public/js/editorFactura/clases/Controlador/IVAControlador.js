function IVAControlador(tipo){
	this.tipo = tipo;
	this.baseImponible = 0.0;	
}

IVAControlador.prototype.getTipo = function(){
	return (this.tipo * 100).toFixed(2);
}

IVAControlador.prototype.addBase = function(b){
	this.baseImponible = ((this.baseImponible * 10) + parseFloat(b * 10)) / 10;
}

IVAControlador.prototype.removeBase = function(b){
	this.baseImponible = this.baseImponible - parseFloat(b);
	
	if(this.baseImponible < 0){
		this.baseImponible = 0.0;
	}
}

IVAControlador.prototype.getIVA = function(){
	return (this.baseImponible * this.tipo).toFixed(2);
}

IVAControlador.prototype.getBaseImponible = function(){
	return this.baseImponible.toFixed(2);
}

IVAControlador.prototype.getTotal = function(){
	return (this.baseImponible * (1 + this.tipo)).toFixed(2);
}