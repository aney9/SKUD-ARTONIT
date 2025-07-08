<?php defined('SYSPATH') or die('No direct script access.');
/*
11.08.2023
Бухаров А.В.
Этот класс строит дерево с элементами псевдографики.
*/
class Tree
{
	//https://snipp.ru/php/select-option-tree
	
	/*
	массив array должен иметь такую структуру: 
	array(21) (
    1 => array(4) (
        "id" => string(1) "1"
        "title" => string(6) "все"
        "parent" => integer 0
        "busy" => NULL
    )
    101 => array(4) (
        "id" => string(3) "101"
        "title" => string(50) "ATACONS ГЛАВНЫЕ В ПУСКОНАЛАДКЕ"
        "parent" => string(1) "1"
        "busy" => NULL
    )
    102 => array(4) (
        "id" => string(3) "102"
        "title" => string(6) "ER_ER "
        "parent" => string(1) "1"
        "busy" => NULL
    )
	
	$sub - с какого уровня выводить дерево
	*/
	public function array_to_tree($array, $sub = 0)
	{
	   
		$a = array();
		foreach($array as $v) {
			if($sub == $v['parent']) {
				$b = $this->array_to_tree($array, $v['id']);
				if(!empty($b)) {
					$a[$v['id']] = $v;
					$a[$v['id']]['children'] = $b;
				} else {
					$a[$v['id']] = $v;
				}
			}
		}
		
		return $a;
	}
	
	
	public function array_to_tree_2($elements, $parentId = 0)
	{
	   
		$branche = array();
		foreach($elements as $element) {
			if($element['parent'] == $parentId ) {
				$children  = $this->array_to_tree($elements, $element['id']);
				if($children) {
					$element['children'] = $children;
				} else {
					$branch[] = $element;
				}
			}
		}
		
		return $branche;
	}
	
	//валидация массива
	function validateOrganization(array $org) {
    if (!isset($org['id']) || !array_key_exists('parent', $org)) {
        throw new InvalidArgumentException("Organization must have 'id' and 'parent' keys");
    }
    if ($org['parent'] !== null && !is_scalar($org['parent'])) {
        throw new InvalidArgumentException("parent must be scalar or null");
    }
}


	function buildTreeWithReferences(array $elements) {
  $tree = [];
    $references = [];

    // Создаём ссылки на элементы по id
    foreach ($elements as &$item) {
        $references[$item['id']] = &$item;
        $item['children'] = []; // Инициализируем пустые дочерние элементы
    }

    // Строим дерево
    foreach ($elements as &$item) {
        if (isset($item['parent']) && isset($references[$item['parent']])) {
            // Если есть родитель — добавляем элемент к его детям
            $references[$item['parent']]['children'][] = &$item;
        } else {
            // Если parent == NULL или родитель не найден — это корневой элемент
            $tree[] = &$item;
        }
    }

    return $tree;
}
 
 /*
 Подготова html select для вывода
$array - результат работы метода  array_to_tree - упорядоченный массив данных
selected_id - указание на выбранный option
$lelvel 
 */
	public function out_options($array, $selected_id = 0, $level = 0) 
	{
		$level++;
		$out = '';
		foreach ($array as $i => $row) {
			$out .= '<option value="' . $row['id'] . '"';
			if ($row['id'] == $selected_id) {
				$out .= ' selected';
			}
			$out .= '>';
	 
			if ($level > 1) {
				if ($level > 2) {
					$out .= str_repeat('&nbsp;', $level - 1);
				}
				
				$keys = array_keys($array);
				$last_keys = $keys[count($array) - 1];			
				if ($last_keys != $i) {
					$out .= '├';
				} else {
					$out .=  '└';
				}
			}
	 
			$out .= $row['title'] . '</option>';
	 
			if (!empty($row['children'])) {
				$out .= $this->out_options($row['children'], $selected_id, $level);
			}
		}
		return $out;
	}
	
	
}

