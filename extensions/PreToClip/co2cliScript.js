function co2cli(cId) {
    var el = document.getElementById(cId);
    if (el) {
        var text = el.innerText || el.textContent;
        navigator.clipboard.writeText(text);
    }
}
