<div id="header">
    <div id="logo"><img src="images/logo2.png" alt="logo"></div>
    
    <div id="account_container">
        <div id="account_info">
            <img src="images/icon_online.png" alt="Online" class="mid_align">
            <?php 
                $huser = Session::instance()->get('auth_user_crm');
                $userAdmin = new Contact(Arr::get($huser, 'ID_PEP'));
                
                echo __('welcome') . ', <strong><i>' . iconv('CP1251', 'UTF-8', 
                    $userAdmin->name. ' '
                    .$userAdmin->patronymic. ' '
                    .$userAdmin->surname)
                    . '</i></strong>';
            ?> 
        </div>
        
        <?php 
        $buro = new Buro();
        $buroRoles = $buro->getIdBuroForUser(Arr::get($huser, 'ID_PEP'));
        
        if (!empty($buroRoles)): ?>
        <div id="buro_roles_info">
            <?php 
            $buroInfo = [];
            foreach ($buroRoles as $item) {
                $buroInfo[] = $item['buro_name'] . '-' . $buro->getRoleById($item['id_role'])[0]['name'];
            }
            echo implode(', ', $buroInfo);
            ?>
        </div>
        <?php endif; ?>
    </div>

    <div id="search">
        <?php 
        echo  __('system.version'). ConfigType::getCityCrmVer().' ';
        if(Arr::get(Session::instance()->get('auth_user_crm'), 'ID_PEP') == 1) echo HTML::anchor('settings/mainManual', HTML::image('images/shortcut/setting.png', array('width'=>20, 'title'=>'Настройка')));
        echo ' | '. HTML::anchor('guide', __('Справка'));
        echo ' | '. HTML::anchor('logout', __('logout'));
        ?>
    </div>
</div>