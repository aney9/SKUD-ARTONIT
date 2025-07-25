<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<div class="onecolumn">
    <div class="header">
        <span><?php echo $from_table ? 'Изменение доступа сотрудника' : 'Добавление доступа сотрудника'; ?></span>
    </div>
    <br class="clear"/>
    <div class="content">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo HTML::chars($error); ?></div>
        <?php endif; ?>
        
        <?php echo Form::open(); ?>
            <input type="hidden" name="buro_id" value="<?php echo HTML::chars($id_buro); ?>">
            
            <fieldset>
                <div class="form-group">
                    <label>Сотрудник</label>
                    <?php if ($from_table): ?>
                        <input type="text" class="form-control" value="<?php 
                            $surname = isset($current_user['SURNAME']) ? $current_user['SURNAME'] : '';
                            $name = isset($current_user['NAME']) ? $current_user['NAME'] : '';
                            $patronymic = isset($current_user['PATRONYMIC']) ? $current_user['PATRONYMIC'] : '';
                            echo HTML::chars(trim($surname.' '.$name.' '.$patronymic));
                        ?>" readonly disabled>
                        <input type="hidden" name="user_id" value="<?php echo HTML::chars($current_user['ID_PEP']); ?>">
                    <?php else: ?>
                        <select class="form-control" name="user_id" required>
                            <option value="">Выберите сотрудника</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo HTML::chars($user['ID_PEP']); ?>">
                                    <?php echo HTML::chars($user['SURNAME'].' '.$user['NAME'].' '.$user['PATRONYMIC']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="comboboxRole">Роль</label>
                    <select class="form-control" id="comboboxRole" name="role_id" required>
                        <option value="">Выберите роль</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?php echo HTML::chars($role['id']); ?>" 
                                <?php echo (isset($current_role_id) && (int)$current_role_id === (int)$role['id']) ? 'selected' : ''; ?>>
                                <?php echo HTML::chars($role['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <?php echo Form::submit('submit', $from_table ? 'Изменить' : 'Добавить'); ?>
            </fieldset>
        <?php echo Form::close(); ?>
        
        <?php echo Form::open('order/buro_details/'.$id_buro); ?>
            <?php echo Form::submit('back', 'Назад к бюро'); ?>
        <?php echo Form::close(); ?>
    </div>
</div>