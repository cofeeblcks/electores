Inputmask.extendAliases({
	'myDecimal': {
		alias: "numeric",
		digits: 1,
		allowPlus: false,
		allowMinus: false,
		radixPoint: ','
		//min: 30,
		//max: 400
	},
    'decialTwoPoints': {
		alias: "numeric",
		digits: 2,
		allowPlus: false,
		allowMinus: false,
		radixPoint: ','
		//min: 30,
		//max: 400
	},
	'numerico': {
		alias: "numeric",
		digits: 0,
		allowPlus: false,
		allowMinus: false,
		rightAlign: false,
		setvalue: 0
		// min: 60,
		// max: 250
	}
});

$(".decimal-punto").inputmask("myDecimal");
$(".decimal-dos-puntos").inputmask("decialTwoPoints");
$('.numerico').inputmask('numerico');

$(function() {
    $('.money').mask('#.##0,00', {reverse: true});
    $('.money-int').mask('000.000.000.000', {reverse: true});
    $('.percent').mask('##0,00%', {reverse: true});
});