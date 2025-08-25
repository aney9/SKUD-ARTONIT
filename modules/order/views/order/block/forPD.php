<?php defined('SYSPATH') OR die('No direct access allowed.'); ?>
<fieldset>
    <legend>Наличие ПД</legend>
    <div>
        <?php if ($signature_url): ?>
            <div>
                <p>Согласие есть</p>
                <p>Дата создания согласия: <?php echo date('d.m.Y H:i:s', filemtime($signature_path)); ?></p>
                <?php
                    $display_filename = rawurldecode(basename($signature_url));
                ?>
                <a href="<?php echo $signature_url; ?>" target="_blank"><?php echo HTML::chars($display_filename); ?></a>
            </div>
        <?php else: ?>
            <p>Нет согласия</p>
        <?php endif; ?>
    </div>
</fieldset>