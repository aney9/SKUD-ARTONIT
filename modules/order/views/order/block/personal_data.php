<fieldset style="max-width: 400px;">
    <legend><?php echo __('Персональные данные'); ?></legend>
    <div>
        <label for="surname"><?php echo __('contact.surname'); ?> <span style="color: black;">*</span></label>
        <br />
        <input 
            type="text" 
            size="50" 
            name="surname" 
            id="surname" 
            value="<?php echo htmlspecialchars($guest->surname); ?>" 
            required
            maxlength="50"
            oninvalid="this.setCustomValidity('Пожалуйста, введите фамилию')"
            oninput="this.setCustomValidity('')"
        />
        <br />
        <span class="error" id="error1" style="color: red; display: none;">
            <?php echo __('contact.emptysurname'); ?></span>
    </div>
    <br />
    <div>
        <table align="left">
            <tr>
                <td>
                    <label for="name"><?php echo __('contact.name'); ?> <span style="color: black;">*</span></label>
                    <br />
                    <input 
                        type="text" 
                        size="50" 
                        name="name" 
                        id="name" 
                        value="<?php echo htmlspecialchars($guest->name); ?>" 
                        required
                        maxlength="50"
                        oninvalid="this.setCustomValidity('Пожалуйста, введите имя')"
                        oninput="this.setCustomValidity('')"
                        style="width: 150px"
                    />
                    <br />
                    <span class="error" id="error_name" style="color: red; display: none;">
                        <?php echo __('Имя обязательно и не должно превышать 50 символов'); ?>
                    </span>
                </td>
                <td style="padding-left: 15px">
                    <label for="patronymic"><?php echo __('contact.patronymic'); ?></label>
                    <br />
                    <input 
                        type="text" 
                        size="50" 
                        name="patronymic" 
                        id="patronymic" 
                        value="<?php echo htmlspecialchars($guest->patronymic); ?>" 
                        maxlength="50"
                        style="width: 150px"
                    />
                    <br />
                    <span class="error" id="error_patronymic" style="color: red; display: none;">
                        <?php echo __('Отчество не должно превышать 50 символов'); ?>
                    </span>
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
                    $currentDocType = '';
                    
                    if (!empty($guest->numdoc)) {
                        $parts = explode('@', $guest->numdoc, 2);
                        $doc_data = isset($parts[0]) ? $parts[0] : '';
                        $currentDocType = isset($parts[1]) ? $parts[1] : '';
                        
                        // Затем разделяем серию и номер по #
                        $doc_parts = explode('#', $doc_data);
                        $docnum1 = htmlspecialchars(isset($doc_parts[0]) ? $doc_parts[0] : '');
                        $docnum2 = isset($doc_parts[1]) ? htmlspecialchars($doc_parts[1]) : '';
                    }
                    ?>
                    <input type="text" size="8" name="docnum1" id="docnum1" value="<?php echo $docnum1; ?>" />
                    <input type="text" size="8" name="docnum2" id="docnum2" value="<?php echo $docnum2; ?>" />
                </td>
                <td style="padding-left: 15px">
                    <label for="datedoc"><?php echo __('contact.datedoc'); ?></label>
                    <br />
                    <input type="text" name="datedoc" id="datedoc" value="<?php 
                        if (!is_null($guest->docdate) && $guest->docdate) {
                            try {
                                $date = new DateTime($guest->docdate);
                                echo htmlspecialchars($date->format('d.m.Y'));
                            } catch (Exception $e) {
                                echo date('d.m.Y');
                            }
                        } else {
                            echo date('d.m.Y');
                        }
                    ?>" style="width: 100px;" />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px;">
                    <label><?php echo __('Тип документа'); ?></label>
                    <div style="margin-left: 10px;">
                        <?php foreach (Documents::getDoc() as $id => $doc): ?>
                            <div style="display: inline-block; margin-right: 15px;">
                                <?php echo Form::radio(
                                    'doc_type', 
                                    $id, 
                                    $currentDocType == $id,
                                    array('id' => 'doctype_'.$id)
                                ); ?>
                                <label for="doctype_<?php echo $id; ?>" style="display: inline;"><?php echo htmlspecialchars($doc['docname']); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <br style="clear: both;" />
</fieldset>
* - поля обязательны к заполнению.