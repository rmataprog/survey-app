$(document).ready(function() {
    $('#submit').on('click', function(e) {
        e.preventDefault();
        var form_valid = true;
        $('form ol').each((i, ol) => {
            var valid = false;
            ol.querySelectorAll('input[type="radio"]').forEach((input, i) => {
                if(input.checked) {
                    valid = input.checked;
                };
            });

            if(!valid) {
                form_valid = valid;
                ol.getElementsByTagName('li').item(0).getElementsByTagName('span').item(0).textContent= 'Please answer this question';
            }
        });

        if(!form_valid) {
            $('#helper-top').text('Not all questions were answered, please check');
        }
    });
});