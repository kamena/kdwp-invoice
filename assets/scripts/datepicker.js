jQuery(document).ready(function(){
    // Check to make sure the input box exists
    if( 0 < jQuery('#datepicker').length ) {
        jQuery('#datepicker').datepicker();
    }
});