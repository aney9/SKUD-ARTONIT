<div class="container">
    <h2>Список бюро</h2>
    
    <table class="data tablesorter-blue" width="100%" cellpadding="0" cellspacing="0" id="tablesorter">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название бюро</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($buros as $buro): ?>
            <tr>
                <td><?php echo HTML::chars($buro['id_buro']); ?></td>
                <td>
                    <a href="<?php echo URL::site('order/buro_details/'.$buro['id_buro']); ?>" 
                       title="Подробная информация о бюро">
                       <?php echo HTML::chars($buro['buro_name']); ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>