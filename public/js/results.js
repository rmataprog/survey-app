$(document).ready(function() {

    /* Google charts */

    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var header = ['Answers', 'Total'];

        var data = JSON.parse(document.getElementById('data').dataset.results);

        var answers = data.map((data) => {
            var r = [];
            data.answers.forEach(element => {
                r.push({ answer: element.content, total: element.total });
            });
            return r;
        });

        var options = {
            chartArea: {left:0,top:0,width:'100%',height:'100%'},
            width: 400
        };

        data.forEach((ele, index) => {
            var data = google.visualization.arrayToDataTable(createArray(header, answers[index]));
            var chart = new google.visualization.PieChart(document.getElementById(ele.question.id));
            return chart.draw(data, options);
        });
    };

    function createArray(header, input) {
        var response = [];
        response.push(header);
        input.forEach((answer) => {
            response.push([answer.answer, answer.total])
        });

        return response;
    }
});