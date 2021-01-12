var $ = require('jquery');

require('bootstrap-sass');

$('.participant').on('click', function (e) {
    e.preventDefault();
    $('.chat-wrapper').empty();
    if ('content' in document.createElement('template')) {
        var name = $(this).text();
        var chatWrapper = document.querySelector('.chat-wrapper');
        $.ajax({
            url: '/ajaxConversations/' + $(this).attr('data-id'),
            method: 'GET',
            dataType: 'JSON',
            success: function (response) {
                var data = $.parseJSON(response)
                $('.participant-avatar').attr('src', data[0]["messageFrom"]["avatar"]);
                $('.participant-name').html(name);
                var currentParticipant = data.pop();

                var templateFrom = document.querySelector('#message-from-template');
                var templateTo = document.querySelector('#message-to-template');
                for (let i = 0; i < data.length; i++) {
                    if (parseInt(currentParticipant['currentUser']) !== parseInt(data[i]['messageFrom']['id'])) {
                        var cloneFrom = templateFrom.content.cloneNode(true);
                        var messageFrom = cloneFrom.querySelector('.participant-name');
                        var messageFromDate = cloneFrom.querySelector('.date-time');
                        var messageFromText = cloneFrom.querySelector('.message-text');

                        messageFrom.innerHTML = data[i]["messageFrom"]["name"];
                        messageFromDate.innerHTML = formatDate(new Date(data[i]["dateAdd"]["timestamp"]));
                        messageFromText.innerHTML = data[i]["messageText"];
                        chatWrapper.append(cloneFrom);
                    } else {
                        var cloneTo = templateTo.content.cloneNode(true);
                        var messageToDate = cloneTo.querySelector('.date-time');
                        var messageToText = cloneTo.querySelector('.message-text');

                        messageToDate.innerHTML = formatDate(new Date(data[i]["dateAdd"]["timestamp"]));
                        messageToText.innerHTML = data[i]["messageText"];
                        chatWrapper.append(cloneTo);
                    }
                }
            },
            fail: function () {
                var templateError = document.querySelector('#ajax-error');
                var clone = templateError.content.cloneNode(true);
                var messageError = clone.querySelector('.error-message');
                messageError.innerHTML = 'An error has occurred!';
                chatWrapper.append(clone);
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
