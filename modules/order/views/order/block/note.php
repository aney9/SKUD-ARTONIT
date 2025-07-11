<fieldset>
    <legend><?php echo __('passoffices.note'); ?></legend>
    <label for="note"><?php echo __('passoffices.note'); ?></label>
    <br />
    <?php echo Form::textarea('note', htmlspecialchars($guest->note), array('id' => 'note')); ?>
</fieldset>