async function sendRequest() {
    let response = await fetch('template/response.php', {
        method: 'POST', body: new FormData(document.forms.pageForm)
    });
    if (response.ok) {
        document.getElementById("result").innerHTML = await response.text();
    }
}