<div class="container">
    <h2>Подробная информация о бюро</h2>
    
    <div class="buro-details">
        <p><strong>ID:</strong> <?php echo HTML::chars($buro['id_buro']); ?></p>
        <p><strong>Название:</strong> <?php echo HTML::chars($buro['buro_name']); ?></p>
        <p><strong>Адрес:</strong> <?php echo HTML::chars($buro['description']); ?></p>
        
        <a href="<?php echo URL::site('order/settings'); ?>" class="btn btn-default">
            Вернуться к списку
        </a>
    </div>
</div>

<style>
    .buro-details {
        background: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        margin-top: 20px;
    }
    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-top: 10px;
        background: #337ab7;
        color: white;
        text-decoration: none;
        border-radius: 4px;
    }
    .btn:hover {
        background: #286090;
    }
</style>