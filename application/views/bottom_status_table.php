<script>
function updateAdditionalInfo() {
        var currentTime = new Date();
        var formattedTime = currentTime.toLocaleTimeString();
        // Обновляем текущее время
        document.getElementById("currentTime").textContent = "Время: " + formattedTime;
    }
	
 setInterval(updateAdditionalInfo, 1000);
</script>
<style>
	/*
		это стиль для нижней таблицы состояний
	*/
			#someidentifier {
				position: fixed;
				z-index: 100; 
				bottom: 0; 
				left: 0;
				width: 100%;
				
				  width: 100%;
				opacity: 0.8;
				background: transparent;
			}

			/*
			делает ссылку <a> неактивной
			если указать class="disabled"
			https://stackoverflow.com/questions/43492742/how-to-use-the-disabled-attribute-for-a-a-tag-in-html
			*/
			a.disabled {
				pointer-events: none;
				color: #ccc;
			}

			/*
			Затемняет изображение.
			надо указать class="darken"
			*/
			.lighten {
			 /* filter: brightness(0.6);*/
			  opacity: .3;
			}

			/*
			Кнопка submit становится бледной
			для этого надо указать disabled="disabled"
			https://stackoverflow.com/questions/3759692/css-selector-for-disabled-input-type-submit
			*/
			
			input[type=submit][disabled], input[type=button][disabled],  button[disabled] {
			   opacity: .3;
			}

	</style>			
			<?php
				//печатаю в нижней части строку состояния
				//echo Debug::vars(Session::instance()); //exit;
				$list=array(
						'0'=>'HEX 8 byte',
						'1'=>'001A 10 byte',
						'2'=>'DEC 10 digit',
						'4'=>'ГРЗ A123BC45',
						);
						
				$list2=array(
						'0'=>'as baseFormatRfid',
						'1'=>'001A 10 byte',
						'2'=>'DEC 10 digit',
						'4'=>'ГРЗ A123BC45',
						);
					
				?>
				<div id="someidentifier">
					<table class="data tablesorter-blue">
						<tr style="filter:alpha(opacity=50)" >
							
							<td><?php echo __('template.Auth', array(':auth'=> Auth::instance()->logged_in()? 'True':'False')) ;?></td>
							<td><?php echo __('Формат RFID в БД СКУД <u>:id</u> (:format)',array(':id'=>Kohana::$config->load('system')->get('baseFormatRfid', 0), ':format'=> Arr::get($list, Kohana::$config->load('system')->get('baseFormatRfid', 0), '--')));?></td>
							<td><?php echo __('Формат регистрационного считывателя <u>:id</u> (:format)',array(':id'=>Kohana::$config->load('system')->get('regFormatRfid'), ':format'=> Arr::get($list2, Kohana::$config->load('system')->get('regFormatRfid'))));?></td>
							<td><?php echo __('template.Role', array(':role'=> Arr::get(Session::instance()->get('auth_user_crm'), 'ROLE')));?></td>
							<td><?php echo __('template.DB', array(':db'=> Arr::get(
									Arr::get(
											Kohana::$config->load('database')->fb,
											'connection' 
											),
								'dsn'))) ;?></td>
								<td><?php echo __('PHP: :php', array(':php'=> phpversion())) ;?></td>
							<td><?php 
								//echo __('template.Mode', array(':mode'=> Session::instance()->get('mode')));
								echo '<br>';
							
								//echo 'Удаление: '.ConfigType::howDeletePeople() . (ConfigType::howDeletePeople())? 'Удалять из базы':'Не активен';
								echo (ConfigType::howDeletePeople())? 'Удаление контакта:<br>из базы':'Удаление контакта:<br>не активен';
							?>
							</td>
							<?php
							$mode=array(0=>'Режим регистрации (0) полный',
										1=>'Режим регистрации (1) быстрый');
							
							?>
							
							<td>
								<?php 
									if(isset(Kohana::$config->load('config_newcrm')->use_acl)){
			
										if (Kohana::$config->load('config_newcrm')->use_acl) {
												echo 'acl  TURN';
										} else {
										echo 'acl  OFF';
										
										}
									} 
									
									
								?></td>
							
							
							<td><?php echo __('template.id_pep', array(':id_pep'=> Arr::get(Auth::instance()->get_user(), 'ID_PEP'))) ;?></td>
							
							
							<td><span id="currentTime"></span></td>
						</tr>
					</table>
				</div>