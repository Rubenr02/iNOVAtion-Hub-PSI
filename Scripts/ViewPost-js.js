/* Check if comment word-count is under 200*/

document.getElementById('input-comment').addEventListener('input', function () {
    var words = this.value.match(/\S+/g);
    var wordCount = words ? words.length : 0;
    
    if (wordCount > 200) {
        // Trim the excess words
        var trimmedText = this.value.split(/\s+/, 200).join(' ');
        this.value = trimmedText;
        wordCount = 200;
    }

    document.getElementById('word-count').textContent = 'Words remaining: ' + (200 - wordCount);
});