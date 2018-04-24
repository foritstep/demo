function test_schedule() {
    var all = document.getElementById("w0").elements;
    var arr = [];

    for(var i = 0; i < all.length; i++) {
        if(all[i].name.indexOf('arr') !== -1 && all[i].value != 0) {
            var t = all[i].name.split(/[^0-9]/).filter(_ => _.length).map(_ => Number.parseInt(_) + 1);
            arr.push({ classroom_id: Number.parseInt(all[i].value), number: t[0], day: t[1] });
        }
    }

    $.get(test_path, { "schedules": JSON.stringify(arr) }, (data) => {
        var data = JSON.parse(data);
        if(data.length) $('#alert').show();
        else $('#alert').hide();
    });
}