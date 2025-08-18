<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>

<h2><?php echo __('Добавление нового бюро'); ?></h2>

<?php if (Session::instance()->get('message')): ?>
    <div><b><?php echo Session::instance()->get('message'); ?></b></div>
    <?php Session::instance()->delete('message'); ?>
    <?php Session::instance()->delete('message_type'); ?>
<?php endif; ?>

<?php
echo Form::open('order/addBuro', array('method' => 'post', 'class' => 'add-buro-form'));
echo '<fieldset>';
echo '<div>';
echo Form::label('name', __('Название бюро:'));
echo '<br>';
echo Form::input('name', '', array(
    'id' => 'name',
    'size' => '50',
    'maxlength' => '50',
    'style' => 'width: 150px',
    'required' => 'required'
));
echo '</div>';

echo '<div>';
echo Form::label('information', __('Адрес:'));
echo '<br>';
echo Form::textarea('information', '', array(
    'id' => 'information',
    'rows' => '3',
    'cols' => '50',
    'style' => 'width: 150px'
));
echo '</div>';

echo '<div>';
echo Form::submit('addburo', __('Добавить'), array(
    'class' => 'btn'
));
echo Form::close();
echo Form::open('order/UpdateBuro', array('class' => 'cancel-form'));
echo Form::hidden('todo', 'cancel');
echo Form::submit('cancel', __('Отмена'), array(
    'class' => 'btn-add'
));
echo Form::close();
echo '</div>';

echo '</fieldset>';
?>