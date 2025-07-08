<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Sample menu template.
 * Simplest case, supports only single-level menus.
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 * @author Ando Roots <ando@sqroot.eu>
 * @since 3.0
 * @package Kohana/Menu
 * @copyright (c) 2012, Ando Roots
 */
?>
<nav>
	<ul>
		<?php foreach ($menu->get_visible_items() as $item): ?>
			<li class="<?= $item->get_classes() ?>">
				<?= (string) $item ?>
			</li>
		<?php endforeach ?>
	</ul>
</nav>