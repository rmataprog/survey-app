$(document).ready(function() {
    $('select').formSelect();
    var date = new Date;
    var time = date.getTime();
    var datepicker_elems = document.querySelectorAll('.datepicker');
    var datepicker_instances = M.Datepicker.init(datepicker_elems, { defaultDate: date, setDefaultDate: true, minDate: date });
    var start_datepicker = M.Datepicker.getInstance(datepicker_elems.item(0));
    var end_datepicker = M.Datepicker.getInstance(datepicker_elems.item(1));

    var time_elems = document.querySelectorAll('.timepicker');
    var time_instances = M.Timepicker.init(time_elems, { twelveHour: false, fromNow: 600000 });
    var start_timepicker = M.Timepicker.getInstance(time_elems.item(0));
    var end_timepicker = M.Timepicker.getInstance(time_elems.item(1));

    $('input[type="checkbox"]').on('change', function(e) {
        var checked = e.target.checked;
        if(checked) {
            $('.datepicker').first().hide();
            $('.timepicker').first().hide();
        } else {
            $('.datepicker').first().show();
            $('.timepicker').first().show();
        };
    });

    $('#start').on('click', function(e) {
        var valid = true;
        var start_immediately = $('input[type="checkbox"]')[0].checked;
        if((start_timepicker.time == undefined) && !start_immediately) {
            valid = false;
            console.log('you must select a start time');
        } else {
            if(!start_immediately) {
                var start_date = new Date(start_datepicker.date);
                var start_time = start_timepicker.time.split(':');
                start_date.setHours(start_time[0]);
                start_date.setMinutes(start_time[1]);
                if(new Date() > start_date) {
                    valid = false;
                    console.log('start time and date must be in the future');
                }
            }
        }
        if(end_timepicker.time == undefined) {
            valid = false;
            console.log('you must select an end time');
        } else {
            var end_date = new Date(end_datepicker.date);
            var end_time = end_timepicker.time.split(':');
            end_date.setHours(end_time[0]);
            end_date.setMinutes(end_time[1]);
            if(new Date() > end_date) {
                valid = false;
                console.log('end time and date must be in the future');
            } else {
                if(!start_immediately && start_date > end_date) {
                    valid = false;
                    console.log('end time and date must be later than the start date and time');
                }
            }
        }
        if(!valid) {
            e.preventDefault();
        }
    });
})