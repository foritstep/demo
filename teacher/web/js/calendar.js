$(function () {
    data_array = data_array.map((i) => (i.time = new Date(i.time), i) );
    
    format = (d) => d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();

    $('#calendar').datepicker({
        inline: true,
        locale: 'ru',
        format: 'DD.MM.YYYY',
        beforeShowDay: function(date) {
            for(var i = 0; i < data_array.length; i++) {
                if(format(date) == format(data_array[i].time)) {
                    return { enabled: true, classes: 'calendar-day', tooltip: data_array[i].name }
                }
            }
        }
    }).on('changeDate', (e) => {
        $('#description-wrapper').empty();
        for(var i = 0; i < data_array.length; i++) {
            if(format(e.date) == format(data_array[i].time)) {
                tr = $('<tr>').appendTo('#description-wrapper');
                $('<th>', { text: data_array[i].classroom }).appendTo($(tr));
                $('<th>', { text: data_array[i].name }).appendTo($(tr));
                $('<th>', { text: '' }).appendTo($(tr));
                $('<th>', { text: data_array[i].group }).appendTo($(tr));
                $('<a>', { 
                    href: link_to_lesson + data_array[i].course + "&date=" + format(data_array[i].time),
                    text: 'Добавить занятие',
                    class: 'btn btn-primary'
                }).appendTo($('<th>')).parent().appendTo($(tr));
            }
        }
    });
});