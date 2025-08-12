<?php
if ($_POST['action'] == 'generate') {
    $type = $_POST['type'] ?? 'password';

    if ($type == 'passphrase') {
        $words = file('words_alpha.txt', FILE_IGNORE_NEW_LINES);
        $count = $_POST['word_count'] ?? 4;
        $result = [];

        for ($i = 0; $i < $count; $i++) {
            $result[] = $words[rand(0, count($words) - 1)];
        }

        echo implode('-', $result);
    } else {
        $chars = '';

        if ($_POST['lower'] == 'true') $chars .= 'abcdefghijklmnopqrstuvwxyz';
        if ($_POST['upper'] == 'true') $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($_POST['num'] == 'true')   $chars .= '0123456789';
        if ($_POST['sym'] == 'true')   $chars .= '!@#$%^&*';

        if (!$chars) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        }

        $password = '';
        $length = $_POST['length'] ?? 12;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }

        echo $password;
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Password Generator 🔐</title>
    <link rel="icon" href="assets/icon.png" type="image/png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center
        }

        .box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            color: white;
            max-width: 350px
        }

        .password {
            background: rgba(255,255,255,0.9);
            color: black;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 18px;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }


        input[type="range"] {
            width: 100%;
            margin: 10px 0
        }

        .btn {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 20px;
            margin: 5px;
            cursor: pointer;
            font-size: 16px
        }

        .copy-btn {
            background: linear-gradient(45deg, #4ecdc4, #2ecc71)
        }

        .adv-btn {
            background: linear-gradient(45deg, #9b59b6, #8e44ad)
        }

        .advanced {
            margin: 15px 0;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            display: none
        }

        .option {
            margin: 8px 0;
            text-align: left
        }

        .option input {
            margin-right: 5px
        }

        .notify {
            position: fixed;
            top: 20px;
            right: 20px;
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            transform: translateX(300px);
            transition: transform 0.3s
        }

        .notify.show {
            transform: translateX(0)
        }

        .success {
            background: #2ecc71
        }

        .info {
            background: #3498db
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>🔐 Password Generator</h2>

        <div>
            <input type="radio" id="pwd" name="type" value="password" checked onchange="toggleType()">
            <label for="pwd">Password</label>
            <input type="radio" id="phrase" name="type" value="passphrase" onchange="toggleType()">
            <label for="phrase">Passphrase</label>
        </div>

        <div id="lenSec">
            Length: <span id="len">12</span>
            <input type="range" id="length" min="8" max="40" value="12" oninput="document.getElementById('len').textContent=this.value">
        </div>

        <div id="wordSec" style="display:none">
            Words: <span id="wlen">4</span>
            <input type="range" id="wordCount" min="2" max="6" value="4" oninput="document.getElementById('wlen').textContent=this.value">
        </div>

        <div id="advBtn">
            <button onclick="toggleAdv()" class="btn adv-btn">⚙️ Advanced</button>
        </div>

        <div class="advanced" id="adv">
            <div class="option">
                <input type="checkbox" id="lower" checked>
                <label for="lower">a-z</label>
            </div>
            <div class="option">
                <input type="checkbox" id="upper" checked>
                <label for="upper">A-Z</label>
            </div>
            <div class="option">
                <input type="checkbox" id="num" checked>
                <label for="num">0-9</label>
            </div>
            <div class="option">
                <input type="checkbox" id="sym" checked>
                <label for="sym">!@#$</label>
            </div>
        </div>

        <div class="password" id="pass">Click Generate</div>
        <button onclick="generate()" class="btn">🎲 Generate</button>
        <button onclick="copy()" class="btn copy-btn">📋 Copy</button>
    </div>

    <div class="notify" id="notify"></div>

    <script>
        function toggleAdv() {
            var adv = document.getElementById('adv');
            adv.style.display = adv.style.display === 'block' ? 'none' : 'block';
        }

        function toggleType() {
            var isPhrase = document.getElementById('phrase').checked;
            document.getElementById('lenSec').style.display = isPhrase ? 'none' : 'block';
            document.getElementById('wordSec').style.display = isPhrase ? 'block' : 'none';
            document.getElementById('advBtn').style.display = isPhrase ? 'none' : 'block';
            document.getElementById('adv').style.display = 'none';
        }

        function generate() {
            var type = document.querySelector('input[name="type"]:checked').value;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                document.getElementById('pass').textContent = xhr.responseText;
                showNotify('Generated!', 'info');
            };

            var data = 'action=generate&type=' + type;

            if (type === 'password') {
                data += '&length=' + document.getElementById('length').value;
                data += '&lower=' + document.getElementById('lower').checked;
                data += '&upper=' + document.getElementById('upper').checked;
                data += '&num=' + document.getElementById('num').checked;
                data += '&sym=' + document.getElementById('sym').checked;
            } else {
                data += '&word_count=' + document.getElementById('wordCount').value;
            }

            xhr.send(data);
        }

        function copy() {
            var text = document.getElementById('pass').textContent;
            if (text !== 'Click Generate') {
                navigator.clipboard.writeText(text);
                showNotify('Copied!', 'success');
            }
        }

        function showNotify(msg, type) {
            var notify = document.getElementById('notify');
            notify.textContent = msg;
            notify.className = 'notify ' + type + ' show';
            setTimeout(function () {
                notify.classList.remove('show')
            }, 2000);
        }
    </script>
</body>
</html>
