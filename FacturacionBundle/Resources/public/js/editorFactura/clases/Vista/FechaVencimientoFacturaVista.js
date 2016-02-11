function FechaVencimientoFacturaVista(){
	this.factura;
	
	$('.dateJquery').datepicker({ 
		dateFormat: 'dd-mm-yy',
		beforeShow: function (textbox, instance) {
	        instance.dpDiv.css({
	                marginLeft: textbox.offsetWidth + 'px'
	        });
		}
	});
}

FechaVencimientoFacturaVista.prototype.init = function(f){
	this.factura = f;
}