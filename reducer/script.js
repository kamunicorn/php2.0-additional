document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.form-reducer').addEventListener('submit', (e) => {
        e.preventDefault();
        submitForm();
    });
});

// отправляет данные в formData, атрибут data - необязателен (данные, которые надо отправить дополнительно к данным с формы), форма - this
function submitForm() {
    let base_url = document.getElementById('base_url').value,
        request = new XMLHttpRequest();

    request.open('POST', 'r.php');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    request.send('base_url=' + encodeURIComponent(base_url));

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

    baseURL.innerHTML = '';
    shortURL.innerHTML = '';

    if (responseText[0] === '{') {
        let json = JSON.parse(responseText);
        if (json['type'] === 'error') {
            message.textContent = json['message'];
        } else if (json['type'] === 'OK') {
            message.textContent = json['message'];
            baseURL.innerHTML = wrapLink('Исходная ссылка', json['data']['baseURL']);
            shortURL.innerHTML = wrapLink('Короткая ссылка', json['data']['shortURL']);
        }
    } else if (responseText[0] === '<') {
        // message.innerHTML = responseText;
        // message.textContent = 'Ошибка на стороне сервера!';
        message.textContent = 'Ссылка не действительна от слова совсем - нет такого домена!';
    } else if (responseText === '' || responseText === 'null') {
        message.textContent = 'Нет ответа!';
    }
}

function wrapLink(str, link) {
    return `<b>${str}</b><br> <a target="_blank" href="${link}">${link}</a>`;
}