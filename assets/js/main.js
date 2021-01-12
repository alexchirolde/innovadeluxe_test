var $ = require('jquery');

require('bootstrap-sass');

$('.participant').on('click', function (e) {
    e.preventDefault();
    if ('content' in document.createElement('template')) {
        var name = $(this).text();
        $.ajax({
            url: '/ajaxConversations/' + $(this).attr('data-id'),
            method: 'GET',
            dataType: 'JSON',
            success: function (response) {
                var data = $.parseJSON(response)
                $('.participant-avatar').attr('src', data[0]["messageFrom"]["avatar"]);
                $('.participant-name').html(name);

                var templateFrom = document.querySelector('#message-from-template');
                var templateTo = document.querySelector('#message-to-template');
                var chatWrapper = document.querySelector('.chat-wrapper');
                for (let i = 0; i < data.length; i++) {

                    var cloneFrom = templateFrom.content.cloneNode(true);
                    var cloneTo = templateTo.content.cloneNode(true);
                    var messageFrom = cloneFrom.querySelector('.participant-name');
                    var messageFromDate = cloneFrom.querySelector('.date-time');
                    var messageFromText = cloneFrom.querySelector('.message-text');
                    var messageTo = cloneTo.querySelector('.participant-name');

                    messageFrom.innerHTML = data[i]["messageFrom"]["name"];
                    messageFromDate.innerHTML = formatDate(new Date(data[i]["dateAdd"]["timestamp"]));
                    messageFromText.innerHTML = data[i]["messageText"];

                    chatWrapper.append(cloneFrom);
                    // chatWrapper.append(messageTo);
                    // $('.chat-wrapper').html(
                    //     '<div class="container message-from">' +
                    //     '<div class="d-flex justify-content-between">' +
                    //     '<p class="participant-name">' + data[i]["messageTo"]["name"] + '</p>' +
                    //     '<p class="date-time">' + formatDate(new Date(data[i]["dateAdd"]["timestamp"])) + '</p>' +
                    //     '</div>' +
                    //     '</div>' +
                    //     '<div class="container message-to">' +
                    //     '<div class="d-flex justify-content-end">' +
                    //     '<p class="participant-name">Yo</p>' +
                    //     '<p class="date-time">' + formatDate(new Date(data[i]["dateAdd"]["timestamp"])) + '</p>' +
                    //     '</div>' +
                    //     '</div>'
                    //
                    //     // data[i]["dateAdd"])
                    // )
                    // console.log(data[i]["id"]);
                }
                console.log(data)

            }
        });
    } else {
        alert('Please upgrade your browser to support templates tag');
    }

});

function formatDate(date) {
    var curr_date = date.getDate();
    var curr_month = date.getMonth() + 1;
    var curr_year = date.getFullYear();
    var hour = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    return curr_date + '-' + curr_month + '-' + curr_year + ' ' + hour + ':' + minutes + ':' + seconds;

}
