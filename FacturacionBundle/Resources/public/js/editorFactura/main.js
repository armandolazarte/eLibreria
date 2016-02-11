var editorFactura;

$('body').ready(function(){
	$('.wrap_itemsFactura').accordion({header: "> h3", heightStyle: "content", active: false, collapsible: true});
	$('.wrap_itemsFactura .item').accordion({header: "> h3", heightStyle: "content", active: false, collapsible: true});
	$('.item h3 input').on('keydown', function(e){e.stopPropagation();});
	$('.item h3 input').on('click', function(e){e.stopPropagation();});
	$('.item h3 .wrap_botonBorrarItem').on('click', function(e){e.stopPropagation();});
	$('.wrap_fechasVencimiento').accordion({header: "> h3", heightStyle: "content", active: false, collapsible: true});
	
	editorFactura = new FacturaControlador();
});