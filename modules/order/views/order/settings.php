<div class="container">
    <h2>Список бюро</h2>
    
    <table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" >
        <thead>
            <tr>
                <th>ID</th>
                <th>Название бюро</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buros as $buro): ?>
            <tr>
                <td><?php echo HTML::chars($buro['id']); ?></td>
                <td>
                    <a href="<?php echo URL::site('order/buro_details/'.$buro['id']); ?>" 
                       title="Подробная информация о бюро">
                       <?php echo HTML::chars($buro['name']); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?php echo URL::site('order/addBuro'); ?>" class="btn btn-default">
            Добавить бюро
            </a>


</div>