$(document).ready(function() {
    $('#submit').on('click', function(e) {
        var valid = true;
        var helper_texts = document.getElementsByClassName('helper-text');
        var email_regex = /^[a-zA-Z0-9._]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        var email = document.forms.item(0).email;
        var password = document.forms.item(0).password;
        var password_regex = /^[a-zA-Z0-9!#$%&_^]{8,12}$/;
        var confirm = document.forms.item(0).confirm;
/*
        if(email.value == '' || !email_regex.test(email.value)) {
            error_message_adder(0, email, helper_texts, 'Please provide a well formatted email');
            valid = false;
        } else {
            class_toggler(0, email, helper_texts);
        }

        if(password.value.length == 0) {
            console.log('leng');
            error_message_adder(1, password, helper_texts, 'Please provide a password');
            valid = false;
        } else if (!password_regex.test(password.value)) {
            console.log('test');
            error_message_adder(1, password, helper_texts, 'Password must have between 8 and 12 characters, containt alpha numberic charaters. !#$%&_^ are allowed');
            valid = false;
        } else {
            class_toggler(1, password, helper_texts);
        }

        if(confirm.value != password.value) {
            error_message_adder(2, confirm, helper_texts, 'Password and confirmation password must match, please check');
            valid = false;
        } else {
            class_toggler(2, confirm, helper_texts);
        }

        if(!valid) {
            e.preventDefault();
        }*/
    });

    function error_message_adder(item, element, helper_texts, message) {
        helper_texts.item(item).setAttribute('data-error', message);
        helper_texts.item(item).classList.toggle('hide');
        if(helper_texts.item(item).classList.contains('hide')) {
            helper_texts.item(item).classList.toggle('hide');
        }
        if(!element.classList.contains('invalid')) {
            element.classList.toggle('invalid');
        }
    }

    function class_toggler(item, element, helper_texts) {
        if(!helper_texts.item(item).classList.contains('hide')) {
            helper_texts.item(item).classList.toggle('hide');
        }
        if(element.classList.contains('invalid')) {
            element.classList.toggle('invalid');
        }
    }
})