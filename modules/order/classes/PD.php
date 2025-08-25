<?php defined('SYSPATH') OR die('No direct access allowed.');

class PD {
    private $id_pep;

    public function __construct($id_pep) {
        $this->id_pep = $id_pep;
        Kohana::$log->add(Log::DEBUG, 'PD::construct: Initialized with id_pep=' . $id_pep);
    }

    public function checkSignature($id_pep) {
        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Checking signature for id_pep=' . $id_pep);

        $guest = new Guest2();
        $person = $guest->getPersonDetails($id_pep);

        if (empty($person)) {
            Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: No person data found for id_pep=' . $id_pep);
            return false;
        }

        $settings = $this->getSettings();
        $upload_dir = $settings['upload_dir'] . DIRECTORY_SEPARATOR;
        if (!is_dir($upload_dir) || !is_readable($upload_dir)) {
            Kohana::$log->add(Log::ERROR, 'PD::checkSignature: Upload directory is not accessible: ' . $upload_dir);
            return false;
        }

        $file_safe_name = $this->createFileSafeName($person);
        $filename_utf8 = $id_pep . '_' . $file_safe_name . '.jpg';
        $filename_cp1251 = iconv('UTF-8', 'CP1251//IGNORE', $filename_utf8);
        $filepath_cp1251 = $upload_dir . $filename_cp1251;

        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Checking main file ' . $filepath_cp1251);

        if (file_exists($filepath_cp1251) && is_readable($filepath_cp1251)) {
            Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Signature found at ' . $filepath_cp1251);
            return $filepath_cp1251;
        }

        // Проверяем файлы с числовым суффиксом
        $counter = 1;
        $base_filename_cp1251 = pathinfo($filename_cp1251, PATHINFO_FILENAME);
        $extension = pathinfo($filename_cp1251, PATHINFO_EXTENSION);

        while ($counter <= 100) {
            $numbered_filename_cp1251 = $base_filename_cp1251 . '_' . $counter . '.' . $extension;
            $numbered_filepath_cp1251 = $upload_dir . $numbered_filename_cp1251;

            if (file_exists($numbered_filepath_cp1251) && is_readable($numbered_filepath_cp1251)) {
                Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Signature found at ' . $numbered_filepath_cp1251);
                return $numbered_filepath_cp1251;
            }
            $counter++;
        }

        // Фоллбэк: поиск по паттерну, если ничего не найдено
        $found_by_pattern = $this->searchByPattern($upload_dir, $id_pep, $file_safe_name);
        if ($found_by_pattern) {
            return $found_by_pattern;
        }

        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: No signature found for id_pep=' . $id_pep);
        return false;
    }

    /**
     * Поиск файлов по паттерну в директории (фоллбэк метод)
     */
    private function searchByPattern($upload_dir, $id_pep, $file_safe_name_utf8) {
        Kohana::$log->add(Log::DEBUG, 'PD::searchByPattern: Fallback search in directory');
        
        $files = scandir($upload_dir);
        if ($files === false) {
            return false;
        }
        
        // Конвертируем safe_name в CP1251 для сравнения
        $file_safe_name_cp1251 = iconv('UTF-8', 'CP1251//IGNORE', $file_safe_name_utf8);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || !is_file($upload_dir . $file)) {
                continue;
            }
            
            // Проверяем, начинается ли файл с ID пользователя
            if (strpos($file, $id_pep . '_') === 0) {
                Kohana::$log->add(Log::DEBUG, 'PD::searchByPattern: Found potential match: ' . $file);
                
                // Разбиваем имя файла на части (в CP1251)
                $name_parts_cp1251 = explode('_', pathinfo($file, PATHINFO_FILENAME));
                $found_parts = 0;
                
                // Разбиваем safe_name на части
                $safe_parts_cp1251 = explode('_', $file_safe_name_cp1251);
                
                foreach ($safe_parts_cp1251 as $part) {
                    if (strlen($part) >= 3) {
                        foreach ($name_parts_cp1251 as $file_part) {
                            if (strpos($file_part, $part) !== false) {
                                $found_parts++;
                                break;
                            }
                        }
                    }
                }
                
                // Если найдено достаточно частей имени, считаем файл подходящим
                if ($found_parts >= 2) {
                    $full_path = $upload_dir . $file;
                    Kohana::$log->add(Log::DEBUG, 'PD::searchByPattern: Match found by pattern: ' . $full_path);
                    return $full_path;
                }
            }
        }
        
        return false;
    }

    public function getSignatureUrl($id_pep) {
        $signaturePath = $this->checkSignature($id_pep);
        if ($signaturePath) {
            $settings = $this->getSettings();
            $upload_dir = $settings['upload_dir'];
            
            $relativePath = str_replace(DOCROOT, '', $signaturePath);
            $relativePath = str_replace('\\', '/', $relativePath);  // Для Windows
            
            $filename_cp1251 = basename($signaturePath);
            $filename_utf8 = iconv('CP1251', 'UTF-8//IGNORE', $filename_cp1251);
            
            $relativePath_utf8 = dirname($relativePath) . '/' . $filename_utf8;
            
            $pathParts = explode('/', $relativePath_utf8);
            $encodedParts = array_map('rawurlencode', $pathParts);
            $encodedPath = implode('/', $encodedParts);
            
            $url = URL::base() . ltrim($encodedPath, '/');
            
            Kohana::$log->add(Log::DEBUG, 'PD::getSignatureUrl: Generated URL: ' . $url);
            
            return $url;
        }
        return false;
    }

    public function deleteSignature($id_pep) {
        $signaturePath = $this->checkSignature($id_pep);
        if ($signaturePath && file_exists($signaturePath)) {
            if (unlink($signaturePath)) {
                Kohana::$log->add(Log::INFO, 'PD::deleteSignature: Signature deleted for id_pep=' . $id_pep . ' at ' . $signaturePath);
                return true;
            } else {
                Kohana::$log->add(Log::ERROR, 'PD::deleteSignature: Failed to delete signature for id_pep=' . $id_pep . ' at ' . $signaturePath);
                return false;
            }
        }
        Kohana::$log->add(Log::DEBUG, 'PD::deleteSignature: No signature to delete for id_pep=' . $id_pep);
        return false;
    }

    public function createSignatureLink($id_pep) {
        $settings = $this->getSettings();
        return URL::site('order/PersonalData/' . $id_pep);
    }

    /**
     * Получает полное имя из данных персоны с правильной конвертацией кодировки
     */
    private function getFullName($person) {
        $surname = !empty($person['SURNAME']) ? trim($person['SURNAME']) : 'Unknown';
        $name = !empty($person['NAME']) ? trim($person['NAME']) : 'Unknown';
        $patronymic = !empty($person['PATRONYMIC']) ? trim($person['PATRONYMIC']) : 'Unknown';

        if (!mb_check_encoding($surname, 'UTF-8')) {
            $surname = iconv('CP1251', 'UTF-8//IGNORE', $surname);
        }
        if (!mb_check_encoding($name, 'UTF-8')) {
            $name = iconv('CP1251', 'UTF-8//IGNORE', $name);
        }
        if (!mb_check_encoding($patronymic, 'UTF-8')) {
            $patronymic = iconv('CP1251', 'UTF-8//IGNORE', $patronymic);
        }

        $full_name = trim($surname . '_' . $name . '_' . $patronymic);
        $full_name = preg_replace('/\s+/', '_', $full_name);
        
        Kohana::$log->add(Log::DEBUG, 'PD::getFullName: Full name (UTF-8): ' . $full_name);
        
        return $full_name;
    }

    /**
     * Создает безопасное имя файла для файловой системы
     * Принимает массив данных персоны
     */
    private function createFileSafeName($person) {
        $full_name = $this->getFullName($person);
        
        Kohana::$log->add(Log::DEBUG, 'PD::createFileSafeName: Original name: ' . $full_name);
        
        $safe_name = preg_replace('/[\/:*?"<>|]/u', '', $full_name);  
        $safe_name = preg_replace('/_+/', '_', $safe_name);
        $safe_name = trim($safe_name, '_');
        
        if (empty($safe_name) || mb_strlen($safe_name, 'UTF-8') < 3) {
            Kohana::$log->add(Log::DEBUG, 'PD::createFileSafeName: Safe name too short, using fallback');
            $safe_name = 'Unknown_' . substr(md5($full_name), 0, 8);
        }
        
        Kohana::$log->add(Log::DEBUG, 'PD::createFileSafeName: Final safe name: ' . $safe_name);
        
        return $safe_name;
    }

    /**
     * Создает имя файла для сохранения подписи
     * Принимает массив данных персоны
     * Возвращает имя в UTF-8
     */
    public function generateFileName($id_pep, $person) {
        $file_safe_name = $this->createFileSafeName($person);
        return $id_pep . '_' . $file_safe_name . '.jpg';
    }

    private function getSettings() {
        $file = DOCROOT . 'settings.json';
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $settings = json_decode($json, true);
            if ($settings === null) {
                $settings = $this->getDefaultSettings();
                file_put_contents($file, json_encode($settings, JSON_UNESCAPED_UNICODE));
            }
        } else {
            $settings = $this->getDefaultSettings();
            file_put_contents($file, json_encode($settings, JSON_UNESCAPED_UNICODE));
        }
        return $settings;
    }

    private function getDefaultSettings() {
        return array(
            'upload_dir' => DOCROOT . 'downloads',
            'consent_text' => 'Я, {full_name}, подтверждаю, что я предоставляю свое согласие на обработку персональных данных в соответствии с Федеральным законом №152-ФЗ "О персональных данных". Согласие распространяется на сбор, систематизацию, накопление, хранение, уточнение, использование, распространение и иные действия с моими персональными данными в рамках целей, связанных с заключением и исполнением договоров, а также предоставлением услуг. Я знаю о праве отозвать согласие в любой момент путем направления письменного уведомления. Данное согласие действует до момента его отзыва или истечения срока, установленного законодательством Российской Федерации.'
        );
    }
}