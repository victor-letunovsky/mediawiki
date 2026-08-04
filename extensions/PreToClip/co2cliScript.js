function decodeBase64(base64) {
    return decodeURIComponent(Array.prototype.map.call(atob(base64), function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}

function fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.top = '0';
    ta.style.left = '-9999px';
    document.body.appendChild(ta);
    ta.select();
    try {
        document.execCommand('copy');
    } catch (err) {
        console.error('Failed to copy text', err);
    }
    document.body.removeChild(ta);
}

function co2cli(cId) {
    var el = document.getElementById(cId);
    if (el) {
        var text = el.innerText || el.textContent;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).catch(function(err) {
                console.error('navigator.clipboard.writeText failed, falling back', err);
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }
}

function cp2clpb(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).catch(function(err) {
            console.error('navigator.clipboard.writeText failed, falling back', err);
            fallbackCopy(text);
        });
    } else {
        fallbackCopy(text);
    }
}

function copyToClipboard(text) {
    cp2clpb(text);
}
