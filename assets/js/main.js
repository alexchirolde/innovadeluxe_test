var $ = require('jquery');

require('bootstrap-sass');

function getConversation() {
    $('.participant').on('click', function (e) {
        e.preventDefault();
        $.ajax({
            url: '/ajaxConversations/' + $(this).attr('data-id'),
            method: 'GET',
            dataType: 'JSON',
        });

    });
}
