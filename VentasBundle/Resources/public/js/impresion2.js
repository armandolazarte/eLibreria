function imprimirInforme(){
	$("#contenedor").addClass("impresion");
	$("#cuerpo").addClass("impresion");
	$("#contenido").addClass("impresion");
	$("#resultadoBusqueda").addClass("impresion");
	$("#resultadoBusqueda").siblings().hide();
	$("#resultadoBusqueda").parent().siblings().hide();
	$("#resultadoBusqueda").parent().parent().siblings().hide();
	$("#resultadoBusqueda").parent().parent().parent().siblings().hide();
	window.print()
	$("#resultadoBusqueda").siblings().show();
	$("#resultadoBusqueda").parent().siblings().show();
	$("#resultadoBusqueda").parent().parent().siblings().show();
	$("#resultadoBusqueda").parent().parent().parent().siblings().show();
	$("#contenedor").removeClass("impresion");
	$("#cuerpo").removeClass("impresion");
	$("#contenido").removeClass("impresion");
	$("#resultadoBusqueda").removeClass("impresion");
}

$("#BotonImpresion").click(imprimirInforme);