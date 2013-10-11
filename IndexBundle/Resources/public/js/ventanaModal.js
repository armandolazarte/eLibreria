function vm_quienPulsa(e, element)
{
    e = e || event;
    var target = e.target || e.srcElement;
    if(target.id==element.id)
        return true;
    else
        return false;
}

function vm_opacidad()
{
	document.getElementById('ventanaModal').style.opacity = 0;
	setTimeout(vm_cerrarVentana, 400);
}

function vm_cerrarVentana()
{
	document.getElementById('ventanaModal').style.display = 'none';
}

function vm_cerrar(event, element)
{
    if(vm_quienPulsa(event, element))
    {
    	vm_opacidad();
    }
}