Reveal.addEventListener( 'ready', function( event ) {
    //reload the page if its the last slide, so the states will be updated
    Reveal.addEventListener( 'slidechanged', function( event ) {
        Reveal.getTotalSlides();
        if(Reveal.isLastSlide()){
            $("#jirastic-spinner").html('<div id="reload-overlay"><i id="spinner" class="fa fa-refresh fa-spin"></i></div>');
            setTimeout(function(){location.reload()}, 100);
        }
    } );
    toggleDescriptionTestIsctruction();
} );

function toggleDescriptionTestIsctruction() {
    $('.descriptionDiv').hide();
    var showDescription = true;

    $('.toggleDescTest').click(function(){
        if(showDescription) {
            $(this).html('<i class="fa fa-eye-slash "></i> ' + Translator.trans('hide summary'));
            $('.descriptionDiv').show();
            $('.testInstructionDiv').hide();
        } else {
            $(this).html('<i class="fa fa-eye"></i> ' + Translator.trans('show summary'));
            $('.descriptionDiv').hide();
            $('.testInstructionDiv').show();
        }
        showDescription = !showDescription;
    });
}