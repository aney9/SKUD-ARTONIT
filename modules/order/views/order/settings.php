<div class="container">

    <h2><?php echo __('order.settings.name')?></h2>
    <fieldset>
        <legend><?php echo __('order.settings.nameListBuro')?></legend>
    <table class="data" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buros as $buro) { ?>
            <tr>
                <td><?php echo HTML::chars($buro['id']); ?></td>
                <td>
                    <a href="<?php echo URL::site('order/buro_details/'.$buro['id']); ?>">
                        <?php echo HTML::chars($buro['name']); ?>
                    </a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <?php
echo Form::open('order/addBuro', array('class' => 'add-buro-form'));
echo Form::hidden('todo', 'addburo');
echo Form::submit('addburo', __('order.settings.nameAddBuroBtn'), array(
    'class' => 'btn-add'
));
echo Form::close();
?>
</fieldset>

    <fieldset>
        <legend><?php echo __('order.settings.nameSettingPD')?></legend>
    <?php if (isset($_GET['settings_saved']) && $_GET['settings_saved'] == '1') { ?>
    <div class="message success">
        <?php echo __('order.settings.nameAlertSuccess')?>
    </div>
    <?php } elseif (isset($_GET['settings_error'])) { ?>
    <div class="message error">
        <?php echo __('order.settings.nameAlertError')?> <?php echo HTML::chars($_GET['settings_error']); ?>
    </div>
    <?php } ?>
    
    <form action="<?php echo URL::site('order/save_settings'); ?>" method="post" id="settings-form">
        
        <div class="setting-group">
            <h4><?php echo __('order.settings.namePolicy')?></h4>
            <div class="radio-group">
                <label>
                    <input type="radio" name="require_consent_for_card" value="0" 
                           <?php echo !$require_consent_for_card ? 'checked' : ''; ?>>
                    Выдача карт без согласия
                </label>
                <br>
                <label>
                    <input type="radio" name="require_consent_for_card" value="1" 
                           <?php echo $require_consent_for_card ? 'checked' : ''; ?>>
                    Выдача карт только с согласием
                </label>
            </div>
        </div>
        
        <label for="upload_dir"><?php echo __('order.settings.nameWay')?></label><br>
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <input type="text" id="upload_dir" name="upload_dir" value="<?php echo HTML::chars($upload_dir); ?>" style="width: 70%; margin-right: 10px;" readonly>
            <input type="button" id="browse-folder" class="btn-add" value="<?php echo __('order.settings.nameBrowseFolderBtn')?>">
        </div>
        
        <label for="consent_text"><?php echo __('order.settings.nameText')?></label><br>
        <textarea id="consent_text" name="consent_text" style="width: 100%; height: 150px;"><?php echo HTML::chars($consent_text); ?></textarea><br>
        
        <input type="submit" value="<?php echo __('order.settings.nameSaveSettingsBtn')?>" class="btn-add">
    </form>

    <div id="folder-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Выбор папки для сохранения подписей</h4>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div id="current-path" style="margin-bottom: 15px; padding: 10px; background: #f0f0f0; border-radius: 4px;">
                    Текущая папка: <span id="current-path-text"><?php echo HTML::chars(dirname($_SERVER['SCRIPT_FILENAME'])); ?></span>
                </div>
                <div id="folder-list" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                </div>
                <div style="margin-top: 15px;">
                    <input type="text" id="new-folder-name" placeholder="Имя новой папки" style="width: 70%; margin-right: 10px;">
                    <button type="button" id="create-folder" class="btn-add">Создать папку</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="select-current-folder" class="btn-add">Выбрать текущую папку</button>
                <button type="button" class="btn-cancel" onclick="document.getElementById('folder-modal').style.display='none'">Отмена</button>
            </div>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?php echo __('order.settings.namePeopleAndAccess')?></legend>
    <table class="data" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th rowspan="2">ID</th>
                <th rowspan="2">ФИО</th>
                <th rowspan="2">Логин</th>
                <th rowspan="2">Организация</th>
                <th colspan="<?php echo count($buros); ?>">Бюро пропусков</th>
            </tr>
            <tr>
                <?php foreach ($buros as $buro) { ?>
                <th><?php echo HTML::chars($buro['name']); ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($people as $person) { ?>
            <tr>
                <td><?php echo HTML::chars($person['ID_PEP']); ?></td>
                <td>
                    <?php 
                    $surname = isset($person['SURNAME']) ? $person['SURNAME'] : '';
                    $name = isset($person['NAME']) ? $person['NAME'] : '';
                    $patronymic = isset($person['PATRONYMIC']) ? $person['PATRONYMIC'] : '';
                    $fio = trim($surname.' '.$name.' '.$patronymic);
                    echo HTML::anchor(
                        'contacts/setFlag/'.$person['ID_PEP'],
                        HTML::chars($fio)
                    ); 
                    ?>
                </td>
                <td>
                    <?php
                        $login = isset($person['LOGIN']) ? $person['LOGIN'] : '';
                        echo HTML::chars($login);
                    ?>
                </td>
                <td>
                    <?php 
                    $orgName = '-';
                    if (isset($person['ORG_NAME'])) {
                        $orgName = $person['ORG_NAME'];
                    } elseif (isset($person['organization']['NAME'])) {
                        $orgName = $person['organization']['NAME'];
                    }
                    echo HTML::chars($orgName); 
                    ?>
                </td>
                <?php foreach ($buros as $buro) { ?>
                <td>
                    <?php 
                    $role = '-';
                    if (isset($person['buros'][$buro['id']])) {
                        $roleName = isset($person['buros'][$buro['id']]['role_name']) ? $person['buros'][$buro['id']]['role_name'] : '-';
                        $roleId = isset($person['buros'][$buro['id']]['role_id']) ? $person['buros'][$buro['id']]['role_id'] : '';
                        
                        $role = '<a href="#" onclick="document.getElementById(\'form_'.$person['ID_PEP'].'_'.$buro['id'].'\').submit(); return false;" class="role-link">'.HTML::chars($roleName).'</a>';
                        $role .= '<form id="form_'.$person['ID_PEP'].'_'.$buro['id'].'" method="GET" action="'.URL::site('order/UpdateBuro/'.$buro['id']).'" style="display:none;">
                            <input type="hidden" name="user_id" value="'.$person['ID_PEP'].'">
                            <input type="hidden" name="role_id" value="'.$roleId.'">
                        </form>';
                    }
                    echo $role; 
                    ?>
                </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</fieldset>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .data {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .data th, .data td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    .data th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: center;
    }
    .role-link {
        color: #337ab7;
        text-decoration: none;
    }
    .role-link:hover {
        text-decoration: underline;
    }
    .data tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .data tr:hover {
        background-color: #f5f5f5;
    }
    .btn-add {
        display: inline-block;
        padding: 6px 12px;
        margin: 5px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        background: #5cb85c;
        color: white;
        border: none;
        cursor: pointer;
    }
    .btn-add:hover {
        background: #4cae4c;
    }
    .btn-cancel {
        display: inline-block;
        padding: 6px 12px;
        margin: 5px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        background: #d9534f;
        color: white;
        border: none;
        cursor: pointer;
    }
    .btn-cancel:hover {
        background: #c9302c;
    }
    h2, h3 {
        color: #333;
        margin: 20px 0 15px;
    }
    a {
        color: #337ab7;
        text-decoration: none;
    }
    
    .message {
        padding: 10px;
        margin: 10px 0;
        border-radius: 4px;
    }
    .message.success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .message.error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Стили для новых элементов */
    /* .setting-group {
        margin: 20px 0;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f9f9f9;
    } */
    
    .radio-group label {
        display: block;
        margin: 8px 0;
        font-weight: normal;
    }
    
    .radio-group input[type="radio"] {
        margin-right: 8px;
    }
    
    /* Стили для модального окна */
    .modal {
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        border: 1px solid #888;
        width: 60%;
        max-width: 600px;
        border-radius: 4px;
    }
    .modal-header {
        padding: 15px;
        background-color: #f0f0f0;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-body {
        padding: 15px;
    }
    .modal-footer {
        padding: 15px;
        background-color: #f0f0f0;
        border-top: 1px solid #ddd;
        text-align: right;
    }
    .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: black;
    }
    .folder-item {
        padding: 8px;
        margin: 2px 0;
        cursor: pointer;
        border-radius: 3px;
        display: flex;
        align-items: center;
        border: 1px solid transparent;
    }
    .folder-item:hover {
        background-color: #e7e7e7;
        border: 1px solid #ccc;
    }
    .folder-item.parent {
        font-weight: bold;
        color: #666;
    }
    .folder-item.drives {
        font-weight: bold;
        color: #337ab7;
        background-color: #f0f8ff;
    }
    .folder-item.drive {
        color: #8b4513;
        font-weight: bold;
    }
    .folder-icon {
        margin-right: 8px;
        font-size: 16px;
        min-width: 20px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const browseButton = document.getElementById('browse-folder');
    const modal = document.getElementById('folder-modal');
    const closeButton = document.querySelector('.close');
    const selectButton = document.getElementById('select-current-folder');
    const createFolderButton = document.getElementById('create-folder');
    const uploadDirInput = document.getElementById('upload_dir');
    const currentPathText = document.getElementById('current-path-text');
    const folderList = document.getElementById('folder-list');
    const newFolderInput = document.getElementById('new-folder-name');
    
    // Проверяем, что все необходимые элементы найдены
    if (!browseButton || !modal || !closeButton || !selectButton || !createFolderButton || !uploadDirInput || !currentPathText || !folderList || !newFolderInput) {
        console.error('Один или несколько необходимых элементов не найдены в DOM');
        return;
    }
    
    let currentPath = '<?php echo addslashes(dirname($_SERVER['SCRIPT_FILENAME'])); ?>';
    let selectedPath = currentPath;
    
    browseButton.addEventListener('click', function() {
        modal.style.display = 'block';
        loadFolders(currentPath);
    });
    
    closeButton.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    selectButton.addEventListener('click', function() {
        uploadDirInput.value = selectedPath;
        modal.style.display = 'none';
    });
    
    createFolderButton.addEventListener('click', function() {
        const folderName = newFolderInput.value.trim();
        if (folderName) {
            createFolder(selectedPath, folderName);
        } else {
            alert('Введите имя папки');
        }
    });
    
    function loadDrives() {
        console.log('Начало загрузки списка дисков');
        
        const formData = new FormData();
        formData.append('show_drives', 'true');
        
        fetch('<?php echo URL::site("order/browse_folders"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('HTTP статус:', response.status);
            return response.text(); // Сначала получаем как текст
        })
        .then(text => {
            console.log('Сырой ответ сервера:', text);
            try {
                const data = JSON.parse(text);
                console.log('Распарсенный JSON:', data);
                if (data.status === 'success') {
                    console.log('Успешно получены данные:', data.folders);
                    displayFolders(data.folders, data.current_path);
                } else {
                    alert('Ошибка загрузки дисков: ' + data.message);
                    console.error('Ошибка сервера:', data);
                }
            } catch (jsonError) {
                console.error('Ошибка парсинга JSON:', jsonError);
                console.error('Текст ответа:', text);
                alert('Сервер вернул некорректные данные. Проверьте консоль браузера.');
            }
        })
        .catch(error => {
            console.error('Ошибка запроса:', error);
            alert('Ошибка при загрузке дисков. Проверьте консоль браузера для подробностей.');
        });
    }
    
    function loadFolders(path) {
        console.log('Загрузка папок для пути:', path);
        
        const formData = new FormData();
        formData.append('path', path);
        
        fetch('<?php echo URL::site("order/browse_folders"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('HTTP статус:', response.status);
            return response.text(); // Сначала получаем как текст
        })
        .then(text => {
            console.log('Сырой ответ сервера:', text);
            try {
                const data = JSON.parse(text);
                console.log('Распарсенный JSON:', data);
                if (data.status === 'success') {
                    displayFolders(data.folders, data.current_path);
                } else {
                    alert('Ошибка загрузки папок: ' + data.message);
                    console.error('Ошибка сервера:', data);
                }
            } catch (jsonError) {
                console.error('Ошибка парсинга JSON:', jsonError);
                console.error('Текст ответа:', text);
                alert('Сервер вернул некорректные данные. Проверьте консоль браузера.');
            }
        })
        .catch(error => {
            console.error('Ошибка запроса:', error);
            alert('Ошибка при загрузке папок. Проверьте консоль браузера для подробностей.');
        });
    }
    
    function displayFolders(folders, current) {
        selectedPath = current;
        currentPath = current;
        
        // Обновляем отображение текущего пути
        if (currentPathText) {
            if (current === 'Диски') {
                currentPathText.textContent = 'Выбор диска';
            } else {
                currentPathText.textContent = current;
            }
        } else {
            console.error('Элемент current-path-text не найден в DOM');
        }
        
        let html = '';
        
        if (folders && folders.length > 0) {
            folders.forEach(folder => {
                let iconClass = 'folder-item';
                let icon = '📁';
                let displayName = folder.name;
                
                if (folder.type === 'parent') {
                    icon = '↰';
                    displayName = '.. (Вверх)';
                    iconClass += ' parent';
                } else if (folder.type === 'drives') {
                    icon = '💾';
                    displayName = 'Выбрать диск';
                    iconClass += ' drives';
                } else if (folder.type === 'drive') {
                    icon = '💿';
                    iconClass += ' drive';
                }
                
                html += '<div class="' + iconClass + '" data-path="' + escapeHtml(folder.path) + '" data-type="' + folder.type + '">' +
                       '<span class="folder-icon">' + icon + '</span>' +
                       '<span>' + escapeHtml(displayName) + '</span>' +
                       '</div>';
            });
        } else {
            html = '<div style="padding: 20px; text-align: center; color: #666;">В этой папке нет подпапок</div>';
        }
        
        folderList.innerHTML = html;
        
        // Добавляем обработчики событий для элементов папок
        document.querySelectorAll('.folder-item[data-path]').forEach(item => {
            item.addEventListener('click', function() {
                const path = this.dataset.path;
                const type = this.dataset.type;
                
                if (type === 'drives') {
                    // Показываем список дисков
                    loadDrives();
                } else if (path) {
                    loadFolders(path);
                }
            });
        });
    }
    
    function createFolder(parentPath, folderName) {
        const formData = new FormData();
        formData.append('parent_path', parentPath);
        formData.append('folder_name', folderName);
        
        fetch('<?php echo URL::site("order/create_folder"); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                newFolderInput.value = '';
                loadFolders(parentPath);
                alert('Папка создана успешно');
            } else {
                alert('Ошибка создания папки: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка запроса:', error);
            alert('Ошибка при создании папки');
        });
    }
    
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }
});
</script>