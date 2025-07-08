<fieldset>
    <legend><?php echo __('passoffices.note'); ?></legend>
    <label for="note"><?php echo __('passoffices.note'); ?></label>
    <br />
    <?php echo Form::textarea('note', iconv('CP1251', 'UTF-8', $guest->note), array('id' => 'note')); ?>
</fieldset>