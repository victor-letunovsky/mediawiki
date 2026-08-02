function co2cli(cId) {
    var el = document.getElementById(cId);
    if (el) {
        var text = el.innerText || el.textContent;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text);
        } else {
            var ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('Failed to copy text', err);
            }
            document.body.removeChild(ta);
        }
    }
}
