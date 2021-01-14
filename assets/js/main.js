import quill from "quill";

var $ = require('jquery');

require('bootstrap-sass');

let chatWrapper = document.querySelector('.chat-wrapper');
let conversationId, messageFromId;
var offset = 0, disableScroll = false;
//  RETRIEVING MESSAGES OF A CONVERSATION
$('.participant').on('click', function (e) {
    e.preventDefault();
    $('.chat-wrapper').empty();
    $('.quill-editor').addClass('d-none');
    if ('content' in document.createElement('template')) {
        conversationId = $(this).attr('data-id');
        ajaxCall(chatWrapper, false, 0);
    } else {
        alert('Please upgrade your browser to support templates tag');
    }

});
//  RETRIEVING MESSAGES ON SCROLLING INTO A CONVERSATION
$('.chat-wrapper').scroll(function (e) {

    if ($(this).scrollTop() === 0) {
        offset += 5;
        if (!disableScroll)
            ajaxCall(chatWrapper, true, offset);

    }
})

function formatDate(date) {
    var curr_date = date.getDate();
    var curr_month = date.getMonth() + 1;
    var curr_year = date.getFullYear();
    var hour = date.getHours();
    var minutes = date.getMinutes();
    var seconds = date.getSeconds();
    return curr_date + '-' + curr_month + '-' + curr_year + ' ' + hour + ':' + minutes + ':' + seconds;

}

//  RETRIEVING MESSAGES
function ajaxCall(chatWrapper, scrolled, offset) {
    $.ajax({
        url: '/ajaxConversations/' + conversationId + '/' + offset,
        method: 'GET',
        dataType: 'JSON',
        async: 'false',
        global: 'false',
        success: function (response) {
            var data = $.parseJSON(response)
            if (data.length !== 1) {
                messageFromId = $('.participant-name');
                $('.participant-avatar').attr('src', data[0]["messageFrom"]["avatar"]);
                messageFromId.html(data[0]["messageFrom"]["name"]);
                messageFromId.attr('data-id', data[0]["messageFrom"]["id"]);
                messageFromId = messageFromId.attr('data-id');
                $('.quill-editor').removeClass('d-none');
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
                if (scrolled) {
                    chatWrapper.scrollTop = chatWrapper.scrollHeight
                }
            } else
                disableScroll = true;
        },
        error: function (xhr, status) {
            if (typeof this.statusCode[xhr.status] != 'undefined') {
                return false;
            }
        },
        statusCode: {
            500: function (response) {
                var templateError = document.querySelector('#ajax-error');
                var clone = templateError.content.cloneNode(true);
                var messageError = clone.querySelector('.error-message');
                messageError.innerHTML = 'An error has occurred! ' + response.statusText;
                chatWrapper.append(clone);
            }
        }
    });
};

//  QUILL EDITOR
var options = {
    debug: 'info',

    placeholder: 'Send a message...',
    readOnly: false,
    theme: 'snow'
};
var editor = new quill('#quill', options);

//  SEND MESSAGE

$('#send-message').on('click', function () {
    var data = {
        conversationId: conversationId,
        messageFrom: conversationId,
        messageTo: messageFromId,
        messageText: conversationId,
    };
    $.ajax({
        url: '/messages/new',
        method: 'POST',
        dataType: 'JSON',
        async: 'false',
        global: 'false',
        data: data,
        success: function () {

        }
    });
});