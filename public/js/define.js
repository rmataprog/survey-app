$(document).ready(function() {
    $('select').formSelect();
    var valid = true;
    var form = document.forms.item(0);
    var doc_root = document.getElementById('data').dataset.root;
    $('#view').on('click', function(e) {
        form.action = `${doc_root}define/defined.php`;
        valid = ['', null].includes($('#select_survey').val()) ? false : true;
        if(!valid) {
            $('.helper-text').toggleClass('hide');
            e.preventDefault();
        }
    });
    $('#copy').on('click', function(e) {
        valid = ['', null].includes($('#select_survey').val()) ? false : true;
        $('.helper-text').toggleClass('hide');
        if(!valid) {
            e.preventDefault();
        } else {
            var survey_id = $('#select_survey').val();
            form.action = `${ doc_root }define/copy.php?survey_id=${survey_id}`;
        }
    });
})