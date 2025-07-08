<fieldset>
    <legend><?php echo __('Персональные данные'); ?></legend>
    <div>
        <label for="surname"><?php echo __('contact.surname'); ?></label>
        <br />
        <input type="text" size="50" name="surname" id="surname" value="<?php echo iconv('CP1251', 'UTF-8', $guest->surname); ?>" />
        <br />
        <span class="error" id="error1" style="color: red; display: none;"><?php echo __('contact.emptysurname'); ?></span>
    </div>
    <br />
    <div>
        <table align="left">
            <tr>
                <td>
                    <label for="name"><?php echo __('contact.name'); ?></label>
                    <br />
                    <input type="text" size="50" name="name" id="name" value="<?php echo iconv('CP1251', 'UTF-8', $guest->name); ?>" style="width: 150px" />
                </td>
                <td style="padding-left: 15px">
                    <label for="patronymic"><?php echo __('contact.patronymic'); ?></label>
                    <br />
                    <input type="text" size="50" name="patronymic" id="patronymic" value="<?php echo iconv('CP1251', 'UTF-8', $guest->patronymic); ?>" style="width: 150px" />
                </td>
            </tr>
        </table>
    </div>
    <div>
        <table align="left">
            <tr>
                <td>
                    <label for="numdoc"><?php echo __('contact.numdoc'); ?></label>
                    <br />
                    <?php
                    $docnum1 = '';
                    $docnum2 = '';
                    if (strpos($guest->numdoc, '#') !== false) {
                        $doc_parts = explode('#', $guest->numdoc);
                        $docnum1 = iconv('CP1251', 'UTF-8', $doc_parts[0]);
                        $docnum2 = isset($doc_parts[1]) ? iconv('CP1251', 'UTF-8', $doc_parts[1]) : '';
                    } else {
                        $docnum1 = iconv('CP1251', 'UTF-8', $guest->numdoc);
                    }
                    ?>
                    <input type="text" size="8" name="docnum1" id="docnum1" value="<?php echo $docnum1; ?>" />
                    <input type="text" size="8" name="docnum2" id="docnum2" value="<?php echo $docnum2; ?>" />
                </td>
                <td style="padding-left: 15px">
                    <label for="datedoc"><?php echo __('contact.datedoc'); ?></label>
                    <br />
                    <input type="text" name="datedoc" id="datedoc" value="<?php 
                        if (!is_null($guest->docdate)) {
                            echo date('d.m.Y', strtotime($guest->docdate));
                        } else {
                            echo date('d.m.Y');
                        } ?>" style="width: 100px;" />
                    <br />
                    <span class="error" id="error31" style="color: red; display: none;"><?php echo __('contact.emptydatedoc'); ?></span>
                    <span class="error" id="error32" style="color: red; display: none;"><?php echo __('contact.wrongdatedoc'); ?></span>
                </td>
            </tr>
        </table>
    </div>
    <br style="clear: both;" />
</fieldset>