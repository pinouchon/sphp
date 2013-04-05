$(document).ready(function() {
    $('.datepicker').datepicker();
    $('.dropdown-timepicker').timepicker({
        defaultTime: 'current',
        minuteStep: 15,
        disableFocus: true,
        template: 'dropdown'
    });
    $(".chzn-select").chosen({
    create_option: true,
    // persistent_create_option decides if you can add any term, even if part
    // of the term is also found, or only unique, not overlapping terms
    persistent_create_option: true,
    create_option_text: 'Create new tag'
  });
    $(".chzn-select-deselect").chosen({
        allow_single_deselect:true
    });
    $('.alert-message a.close').live('click', function(){
        $(this).parent().parent('.c-alert').slideUp('slow');
    });
    $('.bottom_tooltip').tooltip({
        placement: 'bottom'
    });
    $('.left_tooltip').tooltip({
        placement: 'left'
    });
    $('.right_tooltip').tooltip({
        placement: 'right'
    });
    $('.top_tooltip').tooltip();
    $('.dropdown-menu.dropdown-user-account').click(function(event){
        event.stopPropagation();
    });
    $('#myEditor').wysihtml5();
    $('.accordion-body.collapse.in').prev('.accordion-heading').addClass('acc-active');
    $('.accordion-heading').live('click', function(){
        $('.accordion-heading').removeClass('acc-active');
        $(this).addClass('acc-active');
    });
    $('#example').dataTable({
        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span4'i><'span8'p>>",
        "sPaginationType": "bootstrap",
        "oLanguage": {
            "sLengthMenu": "_MENU_ records per page"
        }
    });
});

/**
 * flash
 */
function flash(type, message) {
    if (type != 'error' && type != 'success' && type != 'info') {
        alert('unknown flash type: ' + type);
    }
    var title = '';
    var body = '';
    if (message instanceof Object) {
        title = message.title;
        body = message.body;
    } else {
        body = message;
    }
    $('.alert-' + type + ' h4').text(title);
    $('.alert-' + type + ' p').text(body);
    $('.alert-' + type).show();
    window.scrollTo(0, 0);
}