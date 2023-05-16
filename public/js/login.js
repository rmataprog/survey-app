$(document).ready(function() {
    $('#submit').on('click', function(e) {
        var valid = true;
        var helper_texts = document.getElementsByClassName('helper-text');
        var email_regex = /^[a-zA-Z0-9._]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        var email = document.forms.item(0).email;
        var password = document.forms.item(0).password;
        if(email.value == '' || !email_regex.test(email.value)) {
            helper_texts.item(0).setAttribute('data-error', 'Please provide a well formatted email');
            if(helper_texts.item(0).classList.contains('hide')) {
                helper_texts.item(0).classList.toggle('hide');
            }
            if(!email.classList.contains('invalid')) {
                email.classList.toggle('invalid');
            }
            valid = false;
        } else {
            if(!helper_texts.item(0).classList.contains('hide')) {
                helper_texts.item(0).classList.toggle('hide');
            }
            if(email.classList.contains('invalid')) {
                email.classList.toggle('invalid');
            }
        }
        if(password.value.length == 0) {
            helper_texts.item(1).setAttribute('data-error', 'Please provide a password');
            helper_texts.item(1).classList.toggle('hide');
            if(helper_texts.item(1).classList.contains('hide')) {
                helper_texts.item(1).classList.toggle('hide');
            }
            if(!password.classList.contains('invalid')) {
                password.classList.toggle('invalid');
            }
            valid = false;
        } else {
            if(!helper_texts.item(1).classList.contains('hide')) {
                helper_texts.item(1).classList.toggle('hide');
            }
            if(password.classList.contains('invalid')) {
                password.classList.toggle('invalid');
            }
        }
        if(!valid) {
            e.preventDefault();
        }
    });
})