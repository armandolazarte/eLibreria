function waitCierre(){
	setTimeout(opacidad, 2500, 'flash');
}

function opacidad(elemento){
	document.getElementById(elemento).style.opacity = 0;
	setTimeout(cerrarInfo, 400, elemento);
}

function cerrarInfo(elemento){
	document.getElementById(elemento).style.display = 'none';
	document.getElementById(elemento).style.opacity = 0;
}

function cierre(event, element)
{
	if(quienPulsa(event, element))
	{
		opacidad(elemento);
	}
}

function quienPulsa(e, element)
{
	e = e || event;
	var target = e.target || e.srcElement;
	if(target.id==element.id)
		return true;
	else
		return false;
}

$('.dateJquery').datepicker({ 
	dateFormat: 'dd-mm-yy',
	beforeShow: function (textbox, instance) {
        instance.dpDiv.css({
                marginLeft: textbox.offsetWidth + 'px'
        });
	}
});
$('.timeJquery').timepicker({
	addSliderAccess: true,
	sliderAccessArgs: { touchonly: false },
	beforeShow: function (textbox, instance) {
        instance.dpDiv.css({
                marginLeft: textbox.offsetWidth + 'px'
        });
	}
});
$('.dateTimeJquery').datetimepicker({
	dateFormat: 'dd-mm-yy',
	addSliderAccess: true,
	sliderAccessArgs: { touchonly: false },
	beforeShow: function (textbox, instance) {
        instance.dpDiv.css({
                marginLeft: textbox.offsetWidth + 'px'
        });
	}
});