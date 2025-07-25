<div class="container">
    <h2>Настройки бюро пропусков</h2>
    
    <!-- Таблица списка бюро -->
    <h3>Список бюро</h3>
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
    
    <a href="<?php echo URL::site('order/addBuro'); ?>" class="btn-add">Добавить бюро</a>

    <!-- Таблица сотрудников и их бюро -->
    <h3 style="margin-top: 30px;">Сотрудники и их доступы</h3>
    <table class="data" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th rowspan="2">ID</th>
                <th rowspan="2">ФИО</th>
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
    }
    h2, h3 {
        color: #333;
        margin: 20px 0 15px;
    }
    a {
        color: #337ab7;
        text-decoration: none;
    }
</style>