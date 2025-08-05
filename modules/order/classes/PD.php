<?php defined('SYSPATH') OR die('No direct access allowed.');

class PD {
    public function __construct($id_pep) {
        $this->id_pep = $id_pep;
        Kohana::$log->add(Log::DEBUG, 'PD::construct: Initialized with id_pep=' . $id_pep);
    }

    public function checkSignature($id_pep) {
        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Checking signature for id_pep=' . $id_pep);
        
        // Создаём экземпляр модели Guest2
        $guest = new Guest2();
        
        // Получаем данные о человеке
        $person = $guest->getPersonDetails($id_pep);
        
        if (empty($person)) {
            Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: No person data found for id_pep=' . $id_pep);
            return false;
        }

        // Формируем ФИО
        $surname = !empty($person['SURNAME']) ? trim($person['SURNAME']) : 'Unknown';
        $name = !empty($person['NAME']) ? trim($person['NAME']) : 'Unknown';
        $patronymic = !empty($person['PATRONYMIC']) ? trim($person['PATRONYMIC']) : 'Unknown';
        
        // Преобразуем кодировку, если нужно
        if (!mb_check_encoding($surname, 'UTF-8') || !mb_check_encoding($name, 'UTF-8') || !mb_check_encoding($patronymic, 'UTF-8')) {
            $surname = iconv('CP1251', 'UTF-8//IGNORE', $surname);
            $name = iconv('CP1251', 'UTF-8//IGNORE', $name);
            $patronymic = iconv('CP1251', 'UTF-8//IGNORE', $patronymic);
            Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Converted encoding for id_pep=' . $id_pep);
        }

        // Формируем полное имя
        $full_name = trim("$surname $name $patronymic");
        $full_name = preg_replace('/\s+/', ' ', $full_name);
        // Заменяем пробелы на подчеркивания и удаляем недопустимые символы
        $full_name_sanitized = preg_replace('/[^A-Za-z0-9_]/', '', str_replace(' ', '_', $full_name));
        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Sanitized full_name=' . $full_name_sanitized);

        // Определяем путь к папке Downloads
        $upload_dir = getenv('USERPROFILE') . DIRECTORY_SEPARATOR . 'Downloads' . DIRECTORY_SEPARATOR;
        if (!is_dir($upload_dir) || !is_readable($upload_dir)) {
            Kohana::$log->add(Log::ERROR, 'PD::checkSignature: Downloads directory is not accessible: ' . $upload_dir);
            return false;
        }

        // Формируем путь к файлу
        $base_filename = $id_pep . '_' . $full_name_sanitized;
        $filename = $base_filename . '.jpg';
        $filepath = $upload_dir . $filename;
        
        // Проверяем существование и читаемость файла, включая возможные файлы с суффиксом _counter
        $counter = 0;
        do {
            $current_filepath = $counter === 0 ? $filepath : $upload_dir . $base_filename . '_' . $counter . '.jpg';
            if (file_exists($current_filepath) && is_readable($current_filepath)) {
                Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Signature found at ' . $current_filepath);
                return $current_filepath;
            }
            $counter++;
        } while ($counter <= 100); // Ограничиваем проверку до разумного числа попыток
        
        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: No signature found for id_pep=' . $id_pep);
        return false;
    }
}