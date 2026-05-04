let a = '';
let b = '';
let op = '';
let step = 1;

function press(num) {
    if (step === 1) {
        a += num;
        display();
    } else {
        b += num;
        display();
    }
}

function pressOp(operator) {
    if (a === '') return;
    op = operator;
    step = 2;
    display();
}

function display() {
    document.getElementById('display').value = a + ' ' + op + ' ' + b;
}

function clearDisplay() {
    a = '';
    b = '';
    op = '';
    step = 1;
    document.getElementById('display').value = '';
}

function backspace() {
    if (step === 1) {
        a = a.slice(0, -1);
    } else {
        if (b !== '') {
            b = b.slice(0, -1);
        } else {
            op = '';
            step = 1;
        }
    }
    display();
}

function calculate() {
    if (a === '' || b === '' || op === '') return;

    fetch('calculator.php?api=1', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ a, b, op })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            document.getElementById('display').value = data.error;
        } else {
            document.getElementById('display').value = data.result;
            a = data.result.toString();
            b = '';
            op = '';
            step = 1;
        }
    })
    .catch(error => {
        document.getElementById('display').value = 'Ошибка';
    });
}