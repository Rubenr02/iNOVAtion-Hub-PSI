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




