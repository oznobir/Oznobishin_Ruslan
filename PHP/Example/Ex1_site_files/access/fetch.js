async function sendRequest() {
    let response = await fetch('', {
        method: 'POST', body: new FormData(document.forms.page)
    });
    if (response.ok) {
        document.getElementById("result").innerHTML = await response.text();
    }
}