$(document).ready(function () {
    var containerWizard = "wizard-ordenes";
    $('#'+containerWizard).smartWizard('reset');
    initSmartwizard(containerWizard);
})

function initSmartwizard(idContainer) {
    // Smart Wizard
    $('#'+idContainer).smartWizard({
        selected: 0,
        keyNavigation: false,
        theme: 'default',
        transitionEffect:'slide',
        showStepURLhash: true,
        toolbarSettings: {
            // toolbarPosition: 'start',
            toolbarButtonPosition: 'start',
        },
        lang: {  // Language variables
            next: 'Siguiente',
            previous: 'Anterior'
        },
    });

    $("#"+idContainer).on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
        //alert("You are on step "+stepNumber+" now");
        if(stepPosition === 'first'){
           $("#prev-btn").addClass('disabled');
           $('#btnModalRegistro').addClass('btn-danger');
           $('#btnModalRegistro').attr("disabled",true);
        }else if(stepPosition === 'final'){
           $("#next-btn").addClass('disabled');
           $('#btnModalRegistro').removeClass('btn-danger');
           $('#btnModalRegistro').addClass('btn-success');
           $('#btnModalRegistro').attr("disabled",false);
        }else{
           $("#prev-btn").removeClass('disabled');
           $("#next-btn").removeClass('disabled');
           $('#btnModalRegistro').addClass('btn-danger');
           $('#btnModalRegistro').attr("disabled",true);
        }
    });

    $("#"+idContainer).on("leaveStep", function(e, anchorObject, stepNumber, stepDirection) {
        return validarcampos("#frmRegistro");
    });
}