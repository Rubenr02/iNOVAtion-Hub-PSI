var receiverID; 

console.log('Sender ID:', senderID);

function Message() {
    var message = $('#messageInput').val();

    if (receiverID !== null) {
        $.ajax({
            url: 'Php/sendMessage.php',
            type: 'POST',
            data: { senderID: senderID, receiverID: receiverID, message: message },
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error('Error sending message:', error);
            }
        });
    } else {
        console.log('No friend selected');
    }
}

function fetchAndDisplayMessages() {
    $.ajax({
        url: 'Php/fetchMessages.php',
        type: 'POST',
        data: { senderID: senderID, receiverID: receiverID },
        success: function (data) {
            // Append the received messages as HTML
            $('#chat-messages').append(data);
            
            // Scroll to the bottom to show the latest messages
            $('#chat-messages').scrollTop($('#chat-messages')[0].scrollHeight);
        },
        error: function (xhr, textStatus, errorThrown) {
            console.error('Error fetching messages:', textStatus, errorThrown);
        },
        complete: function () {
            // Schedule the next fetch after a short delay
            setTimeout(fetchAndDisplayMessages, 1000); // Adjust the interval as needed
        }
    });
}

