document.addEventListener('DOMContentLoaded', () => {
    console.log('app.js loaded');

    // Проверка наличия SignaturePad
    if (typeof SignaturePad === 'undefined') {
        console.error('SignaturePad is not defined. Check if signature_pad.umd.min.js is loaded correctly.');
        return;
    }

    const wrapper = document.getElementById('signature-pad');
    const canvas = document.getElementById('signature-canvas');
    const clearButton = document.getElementById('clear');
    const saveJPGButton = document.getElementById('save-jpg');

    // Проверка наличия всех элементов
    if (!wrapper || !canvas || !clearButton || !saveJPGButton) {
        console.error('One or more required DOM elements are missing:', {
            wrapper: !!wrapper,
            canvas: !!canvas,
            clearButton: !!clearButton,
            saveJPGButton: !!saveJPGButton
        });
        return;
    }

    // Инициализация SignaturePad
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255, 255, 255)',
        penColor: 'rgb(0, 0, 0)',
        onBegin: () => console.log('Drawing started'),
        onEnd: () => console.log('Drawing ended')
    });

    // Проверка инициализации canvas
    console.log('SignaturePad initialized on canvas:', canvas);

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
        // Восстанавливаем данные подписи после ресайза
        const data = signaturePad.toData();
        signaturePad.clear();
        signaturePad.fromData(data);
        console.log('Canvas resized:', canvas.width, canvas.height);
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    clearButton.addEventListener('click', () => {
        signaturePad.clear();
        console.log('Canvas cleared');
    });

    saveJPGButton.addEventListener('click', () => {
        if (signaturePad.isEmpty()) {
            alert('Пожалуйста, сначала добавьте подпись.');
            console.log('Save attempted but canvas is empty');
            return;
        }

        const tempCanvas = document.createElement('canvas');
        const tempContext = tempCanvas.getContext('2d');
        const fullName = '<?php echo isset($full_name) ? htmlspecialchars($full_name) : '134'; ?>';
        const idPep = '<?php echo isset($id_pep) ? htmlspecialchars($id_pep) : "0"; ?>';
        const headerText = 'Я, ' + fullName + ', подтверждаю, что я предоставляю свое согласие на обработку персональных данных в соответствии с Федеральным законом №152-ФЗ "О персональных данных". Согласие распространяется на сбор, систематизацию, накопление, хранение, уточнение, использование, распространение и иные действия с моими персональными данными в рамках целей, связанных с заключением и исполнением договоров, а также предоставлением услуг. Я осведомлен о праве отозвать согласие в любой момент путем направления письменного уведомления. Данное согласие действует до момента его отзыва или истечения срока, установленного законодательством Российской Федерации.';
        const signatureText = 'Подпись документа: Согласие на обработку данных';

        const lines = [];
        let currentLine = '';
        const words = headerText.split(' ');
        const maxWidth = canvas.width - 40;
        tempContext.font = '16px Helvetica';
        tempContext.textAlign = 'left';

        words.forEach(word => {
            const testLine = currentLine + word + ' ';
            const metrics = tempContext.measureText(testLine);
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

        const lineHeight = 20;
        const headerHeight = lines.length * lineHeight + 10;
        tempCanvas.width = canvas.width;
        tempCanvas.height = canvas.height + headerHeight + 30;

        tempContext.fillStyle = 'white';
        tempContext.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

        tempContext.font = '16px Helvetica';
        tempContext.fillStyle = '#333';
        tempContext.textAlign = 'left';
        lines.forEach((line, i) => {
            tempContext.fillText(line, 20, 20 + i * lineHeight);
        });

        tempContext.textAlign = 'center';
        tempContext.fillText(signatureText, tempCanvas.width / 2, headerHeight + 20);

        tempContext.drawImage(canvas, 0, headerHeight + 30);

        const dataURL = tempCanvas.toDataURL('image/jpeg', 0.8);
        console.log('JPG generated, downloading...');

        const link = document.createElement('a');
        const sanitizedFullName = fullName.replace(/[^A-Za-z0-9_]/g, '_').replace(/\s+/g, '_');
        link.download = idPep + '.jpg';
        link.href = dataURL;
        link.click();

        const saveUrl = '/index.php/guest/save_signature/' + idPep;
        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'signature_data=' + encodeURIComponent(dataURL)
        }).then(response => {
            console.log('Signature saved to server:', response.status);
        }).catch(error => {
            console.error('Error saving signature to server:', error);
        });
    });

    function download(dataURL, filename) {
        const blob = dataURLToBlob(dataURL);
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        console.log('File downloaded:', filename);
    }

    function dataURLToBlob(dataURL) {
        const parts = dataURL.split(';base64,');
        const contentType = parts[0].split(':')[1];
        const raw = window.atob(parts[1]);
        const rawLength = raw.length;
        const uInt8Array = new Uint8Array(rawLength);
        for (let i = 0; i < rawLength; ++i) {
            uInt8Array[i] = raw.charCodeAt(i);
        }
        return new Blob([uInt8Array], { type: contentType });
    }
});