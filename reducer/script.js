document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.form-reducer').addEventListener('submit', (e) => {
        e.preventDefault();
        submitForm();
    });
});

// отправляет данные в formData, атрибут data - необязателен (данные, которые надо отправить дополнительно к данным с формы), форма - this
function submitForm() {
    let url_base = document.getElementById('url_base').value,
        request = new XMLHttpRequest();

    request.open('POST', 'handler.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('url_base=' + encodeURIComponent(url_base));
    console.log('url_base=' + encodeURIComponent(url_base));

    request.onreadystatechange = function() {
        if (request.readyState === 4 && request.status === 200) {
            showResponse(request.responseText);
        }
    };
}

function showResponse(responseText) {
    let responseBox = document.querySelector('.response'),
        message = responseBox.querySelector('.response__message'),
        baseURL = responseBox.querySelector('.response__base-url'),
        shortURL = responseBox.querySelector('.response__short-url');

    if (responseText[0] === '<') {
        message.innerHTML = responseText;
    } else if (responseText === '' || responseText === 'null') {
        message.textContent = 'Нет ответа!';
    } else {
        json = JSON.parse(responseText);
        if (json['type'] === 'error') {
            message.textContent = json['message'];
        } else if (json['type'] === 'OK') {
            message.textContent = json['message'];
            baseURL.innerHTML = wrapLink('Исходная ссылка', json['data']['base_url']);
            shortURL.innerHTML = wrapLink('Короткая ссылка', json['data']['short_url']);
        } else {
            responseBox.textContent = responseText;
        }
    }
}

function wrapLink(str, link) {
    return `<b>${str}</b><br> <a target="_blank" href="${link}">${link}</a>`;
}