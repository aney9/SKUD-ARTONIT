<?php if (!empty($buro_list) && ($user->id_role == 1 || $user->id_role == 2 || $user->id_role == 3)): ?>
<div class="buro-selection" style="margin-bottom: 15px;">
    <h3><?php echo __('Выбор бюро'); ?></h3>
    <fieldset>
        <legend><?php echo __('Выберите бюро'); ?></legend>
        <?php foreach ($buro_list as $buro_item): ?>
            <label style="display: block; margin: 5px 0; padding: 5px; background: #f5f5f5; border-radius: 4px;">
                <input 
                    type="radio" 
                    name="selected_buro" 
                    value="<?php echo $buro_item['id_buro']; ?>"
                    <?php echo (empty($_POST['selected_buro']) && $buro_item === reset($buro_list)) ? 'required' : ''; ?>
                >
                <?php echo htmlspecialchars($buro_item['buro_name']); ?>
            </label>
        <?php endforeach; ?>
    </fieldset>
</div>
<?php endif; ?>