$(document).ready(function() {

    /*this is to make the select menu work with materialize*/
    $('select').formSelect();

    var answers_array = ['a', 'b', 'c', 'd', 'e'];

    /*this is for adding options in each question*/

    $('#add_option').on('click', function(e) {
        e.preventDefault();
        var questions_amount = $('.questions').length;
        if(questions_amount <= 10) {

            var answers_amount = $('.answers').last().children().length;

            if(answers_amount < 5) {
                var question = questions_amount;
                var option = answers_array[answers_amount];
                $('.answers').last().append(`<li><input type="text" class="validate" name="q-${question}-${option}"><span class="helper-text"></span></li>`);
                $('.answers').last().find('li').each(function(i, ele) {
                    if(ele.lastElementChild.nodeName == 'SPAN') {
                        var icon_ele = document.createElement('i');
                        icon_ele.classList.add('material-icons');
                        icon_ele.classList.add("red-text");
                        icon_ele.textContent = 'close';
                        ele.appendChild(icon_ele);
                        icon_ele.addEventListener('click', function(e) {
                            var target = e.target;
                            var li_ele = target.parentElement;
                            var ol_ele = li_ele.parentElement;
                            li_ele.remove();
                            var new_amount = ol_ele.children.length;
                            for(var i = 0; i < new_amount; i++) {
                                var li_ele = ol_ele.children.item(i);
                                li_ele.firstElementChild.setAttribute('name', `q-${question}-${answers_array[i]}`);
                            }
                            if($('#add_option').hasClass('disabled')) {
                                $('#add_option').removeClass('disabled');
                            }
                            $('ol.answers').each(function(i, ol) {
                                var li_list = ol.getElementsByTagName('li');
                                var li_amount = li_list.length;
                                if(li_amount < 3) {
                                    for(var i = 0; i < 2; i++) {
                                        if(li_list.item(i).childElementCount > 2) {
                                            var li = li_list.item(i);
                                            var icon = li.lastChild;
                                            li.removeChild(icon);
                                        }
                                    }
                                }
                            });
                        }, false);
                    }
                });
            }

            if($('.answers').last().children().length == 5) {
                $(this).addClass('disabled');
            }
        }
    })

    /*this is for adding questions*/

    $('#add_question').on('click', function(e) {
        e.preventDefault();
        var questions_amount = $('.questions').length;
        if(questions_amount < 10) {
            var new_question = questions_amount + 1;
            var html = `<ol start="${new_question}" class="questions">
                            <li>
                                <input type="text" class="validate" name="q-${new_question}">
                                <span class="helper-text"></span>
                            </li>
                            <ol style="list-style-type: lower-latin;" class="answers">
                                <li><input type="text" class="validate" name="q-${new_question}-a"><span class="helper-text"></span></li>
                                <li><input type="text" class="validate" name="q-${new_question}-b"><span class="helper-text"></span></li>
                            </ol>
                        </ol>`;
            $('.questions').last().after(html);
            if($('#add_option').hasClass('disabled')) {
                $('#add_option').removeClass('disabled');
            }

            /*this is for adding the X icon for each question when there are more than one*/

            $('.questions').each((i, ol) => {
                if(ol.firstElementChild.lastElementChild.nodeName != 'I') {
                    var icon = document.createElement('i');
                    icon.classList.add("material-icons");
                    icon.classList.add("red-text");
                    icon.textContent = 'close';
                    ol.firstElementChild.appendChild(icon);
                    icon.addEventListener('click', function() {
                        ol.remove();
                        if($('.questions').length < 2) {
                            $('.questions').first().find('li i').first().remove();
                        };
                        $('.questions').each((i, ol) => {
                            ol.setAttribute('start', i + 1);
                            ol.firstElementChild.setAttribute('name', `q-${i + 1}`);
                            var inputs = ol.getElementsByClassName('answers').item(0).querySelectorAll('li input');
                            inputs.forEach(function(ele, j) {
                                ele.setAttribute('name', `q-${i + 1}-${answers_array[j]}`);
                            });
                        });
                        if($('#add_question').hasClass('disabled')) {
                            $('#add_question').removeClass('disabled');
                        }
                    }, false);
                }
            });
        }
        if(questions_amount + 1 == 10) {
            $(this).addClass('disabled');
        }
    });

    $('#cancel').on('click', function(e) {
        e.preventDefault();
        $('#title').val('');
        $('.questions').remove();
        $('#questions_list').prepend(`<ol class="questions">
                <li>
                    <input type="text" class="validate" name="q-1">
                    <span class="helper-text"></span>
                </li>
                <ol style="list-style-type: lower-latin;" class="answers">
                    <li><input type="text" class="validate" name="q-1-a"><span class="helper-text"></span></li>
                    <li><input type="text" class="validate" name="q-1-b"><span class="helper-text"></span></li>
                </ol>
            </ol>`);
    });

    $('#save').on('click', function(e) {
        var regex = /^[A-z0-9\s¿?]+$/;
        var valid = true;
        var title = document.getElementById('title');
        if(!regex.test(title.value) || title.value == '') {
            valid = false;
            toggle_validation_class(title, false);
            if(title.value == '') {
                title.nextElementSibling.setAttribute('data-error', "Title can't be empty");
            } else {
                title.nextElementSibling.setAttribute('data-error', "Title can only have alpha numberic characters and interrogation symbol");
            }
        } else {
            toggle_validation_class(title, true);
        }
        $('.questions').each((i, ol) => {
            var ele = ol.querySelectorAll('li input').item(0);
            var value = ele.value;
            if(!regex.test(value) || value == '') {
                valid = false;
                toggle_validation_class(ele, false);
                if(value == '') {
                    ele.nextElementSibling.setAttribute('data-error', "Questions can't be empty");
                } else {
                    ele.nextElementSibling.setAttribute('data-error', "Questions can only have alpha numberic characters and interrogation symbol");
                }
            } else {
                toggle_validation_class(ele, true);
            }
        });
        $('.answers li input').each((i, input) => {
            var value = input.value;
            if(!regex.test(value) || value == '') {
                valid = false;
                toggle_validation_class(input, false);
                if(value == '') {
                    input.nextElementSibling.setAttribute('data-error', "Options can't be empty");
                } else {
                    input.nextElementSibling.setAttribute('data-error', "Options can only have alpha numberic characters");
                }
            } else {
                toggle_validation_class(input, true);
            }
        });
        if(!valid) {
            e.preventDefault();
        }
    });
    function toggle_validation_class(ele, valid) {
        if(valid) {
            ele.classList.remove('invalid');
            ele.classList.add('valid');
        } else {
            ele.classList.remove('valid');
            ele.classList.add('invalid');
        }
    }
})