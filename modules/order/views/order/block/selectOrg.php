<div>
    <select name="org_selector" id="org_selector"
            required 
            style="color: #000; background: #fff; padding: 5px; width: 300px; font-family: Arial, sans-serif;">
        <option value="" disabled selected>Выберите организацию</option>
        <?php foreach ($org as $item): ?>
            <?php 
                $name = !empty($item['NAME']) 
                    ? mb_convert_encoding($item['NAME'], 'UTF-8', 'Windows-1251') 
                    : 'Организация #' . $item['ID_ORG'];
                
                $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            ?>
            <option value="<?= $item['ID_ORG'] ?>"><?= $name ?></option>
        <?php endforeach; ?>
    </select>
</div>

<script>
$(document).ready(function () {
    $("#org_selector").select2({
        placeholder: "Выберите организацию",
        allowClear: false,
        language: "ru",
        width: '300px',
        theme: 'default'
    });
});
</script>