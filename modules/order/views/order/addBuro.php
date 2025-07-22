<h2>Добавление нового бюро</h2>

<?php if (Session::instance()->get('message')): ?>
    <div><b><?php echo Session::instance()->get('message'); ?></b></div>
    <?php Session::instance()->delete('message'); ?>
    <?php Session::instance()->delete('message_type'); ?>
<?php endif; ?>

<form action="<?php echo URL::site('order/addBuro'); ?>" method="post">
    <div>
        <label for="name">Название бюро:</label><br>
        <input type="text" id="name" name="name" required>
    </div>
    
    <div>
        <label for="information">Адрес:</label><br>
        <textarea id="information" name="information" rows="3"></textarea>
    </div>
    
    <div>
        <button type="submit">Добавить</button>
        <a href="<?php echo URL::site('order/UpdateBuro'); ?>">Отмена</a>
    </div>
</form>