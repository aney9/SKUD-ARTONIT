<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Электронная подпись</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .header-text {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
            text-align: justify;
            line-height: 1.5;
        }
        .signature-pad {
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            padding: 15px;
            margin-bottom: 20px;
        }
        .signature-pad--body {
            height: 200px;
            border: 1px solid #eee;
            margin-bottom: 10px;
            background-color: white;
        }
        canvas {
            width: 100%;
            height: 100%;
            background-color: white;
        }
        .signature-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #337ab7;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #286090;
        }
        .btn-clear {
            background: #d9534f;
        }
        .btn-clear:hover {
            background: #c9302c;
        }
        .btn-save {
            background: #5cb85c;
        }
        .btn-save:hover {
            background: #4cae4c;
        }
        .signature-text {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Добавление подписи</h2>
        <div class="header-text">
            Я, <?php echo isset($full_name) ? htmlspecialchars($full_name) : '89'; ?>, подтверждаю, что я предоставляю свое согласие на обработку персональных данных в соответствии с Федеральным законом №152-ФЗ "О персональных данных". Согласие распространяется на сбор, систематизацию, накопление, хранение, уточнение, использование, распространение и иные действия с моими персональными данными в рамках целей, связанных с заключением и исполнением договоров, а также предоставлением услуг. Я осведомлен о праве отозвать согласие в любой момент путем направления письменного уведомления. Данное согласие действует до момента его отзыва или истечения срока, установленного законодательством Российской Федерации.
        </div>
        <div id="signature-pad" class="signature-pad">
            <div class="signature-text">Подпись документа: Согласие на обработку данных</div>
            <div class="signature-pad--body">
                <canvas id="signature-canvas"></canvas>
            </div>
            <div class="signature-actions">
                <button type="button" class="btn btn-clear" id="clear">Очистить</button>
                <button type="button" class="btn btn-save" id="save-jpg">Сохранить JPG</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var canvas = document.getElementById('signature-canvas');
            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                signaturePad.clear();
            }

            window.addEventListener('resize', resizeCanvas);
            resizeCanvas();

            document.getElementById('clear').addEventListener('click', function() {
                signaturePad.clear();
            });

            document.getElementById('save-jpg').addEventListener('click', function() {
                if (signaturePad.isEmpty()) {
                    return;
                }

                var tempCanvas = document.createElement('canvas');
                var tempContext = tempCanvas.getContext('2d');
                var fullName = '<?php echo isset($full_name) ? htmlspecialchars($full_name) : '134';?>';
                var idPep = '<?php echo isset($id_pep) ? htmlspecialchars($id_pep) : "0"; ?>';
                var headerText = 'Я, ' + fullName + ', подтверждаю, что я предоставляю свое согласие на обработку персональных данных в соответствии с Федеральным законом №152-ФЗ "О персональных данных". Согласие распространяется на сбор, систематизацию, накопление, хранение, уточнение, использование, распространение и иные действия с моими персональными данными в рамках целей, связанных с заключением и исполнением договоров, а также предоставлением услуг. Я осведомлен о праве отозвать согласие в любой момент путем направления письменного уведомления. Данное согласие действует до момента его отзыва или истечения срока, установленного законодательством Российской Федерации.';
                var signatureText = 'Подпись документа: Согласие на обработку данных';

                var lines = [];
                var words = headerText.split(' ');
                var currentLine = '';
                var maxWidth = canvas.width - 40;
                tempContext.font = '16px Helvetica';
                tempContext.textAlign = 'left';

                words.forEach(function(word) {
                    var testLine = currentLine + word + ' ';
                    var metrics = tempContext.measureText(testLine);
                    if (metrics.width > maxWidth && currentLine !== '') {
                        lines.push(currentLine.trim());
                        currentLine = word + ' ';
                    } else {
                        currentLine = testLine;
                    }
                });
                if (currentLine !== '') {
                    lines.push(currentLine.trim());
                }

                var lineHeight = 20;
                var headerHeight = lines.length * lineHeight + 10;
                tempCanvas.width = canvas.width;
                tempCanvas.height = canvas.height + headerHeight + 30;

                tempContext.fillStyle = 'white';
                tempContext.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

                tempContext.font = '16px Helvetica';
                tempContext.fillStyle = '#333';
                tempContext.textAlign = 'left';
                for (var i = 0; i < lines.length; i++) {
                    tempContext.fillText(lines[i], 20, 20 + i * lineHeight);
                }

                tempContext.textAlign = 'center';
                tempContext.fillText(signatureText, tempCanvas.width / 2, headerHeight + 20);

                tempContext.drawImage(canvas, 0, headerHeight + 30);

                var dataURL = tempCanvas.toDataURL('image/jpeg', 0.8);

                var link = document.createElement('a');
                var sanitizedFullName = fullName.replace(/[^A-Za-z0-9_]/g, '_').replace(/\s+/g, '_');
                link.download = idPep + '.jpg';
                link.href = dataURL;
                link.click();

                var saveUrl = '/index.php/guest/save_signature/' + idPep;
                fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'signature_data=' + encodeURIComponent(dataURL)
                });
            });
        });
    </script>
</body>
</html>