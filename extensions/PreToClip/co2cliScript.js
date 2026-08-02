function decodeBase64(base64) {
    return decodeURIComponent(Array.prototype.map.call(atob(base64), function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}

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

function cp2clpb(text) {
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

function copyToClipboard(text) {
    cp2clpb(text);
}
