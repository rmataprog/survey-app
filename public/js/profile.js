$(document).ready(function() {
    var helper_texts = document.getElementsByClassName('helper-text');
    var name_regex = /^[a-zA-Z]{0,50}$/;
    var first_name = document.forms.item(0).first_name;
    var last_name = document.forms.item(0).last_name;

    $('#save').on('click', function(e) {
        var valid = true;

        if(!name_regex.test(first_name.value)) {
            error_message_adder(0, first_name, helper_texts, 'First name can only contain letters');
            valid = false;
        } else {
            class_toggler(0, first_name, helper_texts);
        }

        if(!name_regex.test(last_name.value)) {
            error_message_adder(1, last_name, helper_texts, 'Last name can only contain letters');
            valid = false;
        } else {
            class_toggler(1, last_name, helper_texts);
        }

        if(!valid) {
            e.preventDefault();
        }
    });

    $('#edit').on('click', function(e) {
        e.preventDefault();
        var save = $('#save');
        save.removeClass('hide');
        e.target.classList.add('hide');
        first_name.disabled = false;
        last_name.disabled = false;
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