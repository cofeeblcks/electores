$('textarea.maxlength-textarea').maxlength({
    alwaysShow: true,
    warningClass: "hoverMaxlength hoverMaxlength-success",
    limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
    placement: 'top',
    // preText: 'usados ',
    // separator: ' de ',
    // postText: ' letras.',
    validate: true
});

$('input.maxlength-input').maxlength({
    alwaysShow: true,
    warningClass: "hoverMaxlength hoverMaxlength-success",
    limitReachedClass: "hoverMaxlength hoverMaxlength-danger",
    placement: 'top',
    validate: true
});

//reposicionamiento y eliminacion de validacion de maxlength
$(".maxlength-textarea.maxlength-input").on('change click mouseleave keypress', function(event) {
    if( event.type == 'click' ){
        $(this).trigger('maxlength.reposition');
    }
});