<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Navigation item template for Twitter Bootstrap main navbar.
 * Render the output inside div.navbar>div.navbar-inner>.container
 *
 * @link http://twitter.github.com/bootstrap/components.html#navbar
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author Ando Roots <ando@sqroot.eu>
 * @since 2.0
 * @package Kohana/Menu
 * @copyright (c) 2012, Ando Roots
 */

?>


	<?php 
		foreach ($menu->get_visible_items() as $item){

		// Is this a with sibling links?
		// есть ли дочки? Если есть, то работаем тут
				
		
		if ($item->has_siblings()){?>

			<li>
				<a id="sidebar_<?php echo $item->url; ?>" href="javascript:" <?php echo 'title ='. $item->tooltip;  ?>><img src="images/<?php echo $item->icon;?>" alt="<?php echo $item->title;?>"><?php echo __($item->title);?> </a>
							
				<ul>
						<?php 
						
						foreach ($item->siblings as $subitem): ?>
							<li>
								<?php 
									
									if($subitem->visible) echo HTML::anchor ($subitem->url, $subitem->title, array('title'=>$subitem->tooltip)); ?>
							</li>
						<?php endforeach ?>
					</ul>
				</li>

			<?php
		} else {
				// No, this is a "normal", single-level menu
				//а если дочек нет, то работаем тут
				?>
				<li>
					<?php echo HTML::anchor ($item->url, HTML::image('images/'.$item->icon, array('alt'=>$item->title)).$item->title, array('title'=>$item->tooltip)); ?>
				</li>

		<?php } 
	} ?>
