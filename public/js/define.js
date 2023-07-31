$(document).ready(function() {
    $('select').formSelect();
    var valid = true;
    var form = document.forms.item(0);
    var doc_root = document.getElementById('data').dataset.root;
    $('#view').on('click', function(e) {
        validator('define/defined', e);
    });
    $('#copy').on('click', function(e) {
        validator('define/copy', e);
    });
    function validator(path, e) {
        valid = ['', null].includes($('#select_survey').val()) ? false : true;
        if(!valid) {
            $('.helper-text').toggleClass('hide');
            e.preventDefault();
        } else {
            form.action = `${ doc_root }${ path }/${$('#select_survey').val()}`;
        }
    }
})