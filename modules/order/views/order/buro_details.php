<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<script type="text/javascript">
    $(function() {        
        $(".tablesorter").tablesorter({ headers: { 0:{sorter: false}}, widgets: ['zebra']});
    });    
</script>

<div class="onecolumn">
    <div class="header">
        <span>Подробная информация о бюро</span>
    </div>
    <br class="clear"/>
    <div class="content">
        <?php if (!empty($buro)): ?>
            <fieldset>
                <legend>Обновить бюро</legend>
                <?php echo Form::open('order/update_buro/'.$buro['id']); ?>
                    <div style="margin-bottom: 15px;">
                        <label for="name"><strong>Название:</strong></label>
                        <input type="text" id="name" name="name" value="<?php echo HTML::chars($buro['name']); ?>" class="form-control">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="information"><strong>Адрес:</strong></label>
                        <textarea id="information" name="information" rows="3" class="form-control"><?php echo HTML::chars($buro['information']); ?></textarea>
                    </div>
                    <?php echo Form::submit('submit', 'Обновить'); ?>
                <?php echo Form::close(); ?>
            </fieldset>
            <?php echo Form::submit('add', 'Добавить в бюро', [
    'onclick' => "window.location.href = '/order/UpdateBuro/{$buro['id']}'; return false;"
]);?>
                <?php echo Form::close(); ?>
                
                <?php echo Form::open('order/delete_buro/'.$buro['id']); ?>
                    <?php echo Form::submit('delete', 'Удалить бюро', array(
                        'onclick' => 'return confirm(\'Вы уверены, что хотите удалить это бюро? Все связанные пользователи также будут удалены.\');'
                    )); ?>
                <?php echo Form::close(); ?>
            
            <h3>Пользователи</h3>
            <?php if (!empty($users)): ?>
                <?php 
                $users_by_role = array();
                foreach ($users as $user) {
                    $role_id = $user['id_role'];
                    if (!isset($users_by_role[$role_id])) {
                        $users_by_role[$role_id] = array();
                    }
                    $users_by_role[$role_id][] = $user;
                }
                
                foreach ($users_by_role as $role_id => $role_users): 
                    $role_info = isset($roles[$role_id][0]) ? $roles[$role_id][0] : null;
                ?>
                    <h4><?php echo $role_info ? HTML::chars($role_info['name']) : 'Роль #'.$role_id; ?></h4>
                    <table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ФИО</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($role_users as $user): 
                                $person = isset($people[$user['id_pep']][0]) ? $people[$user['id_pep']][0] : null;
                            ?>
                                <tr>
                                    <td>
                                        <?php if ($person): ?>
                                            <?php 
                                            $surname = isset($person['SURNAME']) ? $person['SURNAME'] : '';
                                            $name = isset($person['NAME']) ? $person['NAME'] : '';
                                            $patronymic = isset($person['PATRONYMIC']) ? $person['PATRONYMIC'] : '';
                                            
                                            echo HTML::anchor(
                                                'contacts/setFlag/'.$user['id_pep'],
                                                HTML::chars($surname.' '.$name.' '.$patronymic)
                                            ); 
                                            ?>
                                        <?php else: ?>
                                            Неизвестно
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="margin: 20px 0; text-align: center;">
                    Нет пользователей, связанных с этим бюро.
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="margin: 20px 0; text-align: center;">
                Информация о бюро не найдена.
            </div>
        <?php endif; ?>
    </div>
    <div>
    <?php echo Form::open('order/addAccessBuro/' . $buro['id']); ?>
        <fieldset>
            <legend>Доступные зоны</legend>
            <?php foreach ($access_names as $item): ?>
                <div style="margin-bottom: 10px;">
                    <label>
                        <?php 
                        $checked = in_array($item['ID_ACCESSNAME'], $current_accesses) ? true : false;
                        echo Form::checkbox('access_names[]', $item['ID_ACCESSNAME'], $checked); 
                        ?>
                        <?php echo HTML::chars($item['NAME']); ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </fieldset>
        <?php echo Form::submit('add', 'Добавить доступы'); ?>
    <?php echo Form::close(); ?>
</div>
</div>