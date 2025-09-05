<fieldset>
    <legend><?php echo __('passoffices.note'); ?></legend>
    <div>
        <label for="note"><?php echo __('passoffices.note'); ?></label>
        <br />
        <?php echo Form::textarea('note', htmlspecialchars($guest->note), array(
            'id' => 'note',
            'style' => 'width: 80%; max-width: 600px; resize: none;'
        )); ?>
    </div>
</fieldset>