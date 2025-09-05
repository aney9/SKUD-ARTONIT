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
        $upload_dir = $this->normalizePath($settings['upload_dir']);
        
        if (!is_dir($upload_dir) || !is_readable($upload_dir)) {
            Kohana::$log->add(Log::ERROR, 'PD::checkSignature: Upload directory is not accessible: ' . $upload_dir);
            return false;
        }
        if (!is_writable($upload_dir)) {
            Kohana::$log->add(Log::ERROR, 'PD::checkSignature: Upload directory is not writable: ' . $upload_dir);
        }

        $file_safe_name = $this->createFileSafeName($person);
        $filename_utf8 = $id_pep . '_' . $file_safe_name . '.jpg';
        
        // Используем правильную кодировку в зависимости от системы
        $filename_system = $this->convertToSystemEncoding($filename_utf8);
        $filepath_system = $upload_dir . DIRECTORY_SEPARATOR . $filename_system;

        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Checking main file ' . $filepath_system);

        if (file_exists($filepath_system) && is_readable($filepath_system)) {
            Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Signature found at ' . $filepath_system);
            return $filepath_system;
        }

        // Проверяем файлы с числовым суффиксом
        $counter = 1;
        $base_filename_system = pathinfo($filename_system, PATHINFO_FILENAME);
        $extension = pathinfo($filename_system, PATHINFO_EXTENSION);

        while ($counter <= 100) {
            $numbered_filename_system = $base_filename_system . '_' . $counter . '.' . $extension;
            $numbered_filepath_system = $upload_dir . DIRECTORY_SEPARATOR . $numbered_filename_system;

            if (file_exists($numbered_filepath_system) && is_readable($numbered_filepath_system)) {
                Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: Signature found at ' . $numbered_filepath_system);
                return $numbered_filepath_system;
            }
            $counter++;
        }

        // Фоллбэк: поиск по паттерну
        $found_by_pattern = $this->searchByPattern($upload_dir, $id_pep, $file_safe_name);
        if ($found_by_pattern) {
            return $found_by_pattern;
        }

        Kohana::$log->add(Log::DEBUG, 'PD::checkSignature: No signature found for id_pep=' . $id_pep);
        return false;
    }

    private function searchByPattern($upload_dir, $id_pep, $file_safe_name_utf8) {
        Kohana::$log->add(Log::DEBUG, 'PD::searchByPattern: Fallback search in directory');
        
        $files = $this->scanDirectorySafe($upload_dir);
        if ($files === false) {
            Kohana::$log->add(Log::ERROR, 'PD::searchByPattern: Failed to scan directory: ' . $upload_dir);
            return false;
        }
        
        $file_safe_name_system = $this->convertToSystemEncoding($file_safe_name_utf8);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || !is_file($upload_dir . DIRECTORY_SEPARATOR . $file)) {
                continue;
            }
            
            // Проверяем, начинается ли файл с ID
            if (strpos($file, $id_pep . '_') === 0) {
                Kohana::$log->add(Log::DEBUG, 'PD::searchByPattern: Found potential match: ' . $file);
                
                $name_parts_system = explode('_', pathinfo($file, PATHINFO_FILENAME));
                $found_parts = 0;
                
                $safe_parts_system = explode('_', $file_safe_name_system);
                
                foreach ($safe_parts_system as $part) {
                    if (strlen($part) >= 3) {
                        foreach ($name_parts_system as $file_part) {
                            if (strpos($file_part, $part) !== false) {
                                $found_parts++;
                                break;
                            }
                        }
                    }
                }
                
                if ($found_parts >= 2) {
                    $full_path = $upload_dir . DIRECTORY_SEPARATOR . $file;
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
            $upload_dir = $this->normalizePath($settings['upload_dir']);
            
            // Получаем относительный путь от DOCROOT
            $docroot = $this->normalizePath(DOCROOT);
            $relativePath = str_replace($docroot, '', $signaturePath);
            $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
            $relativePath = ltrim($relativePath, '/');
            
            $filename_system = basename($signaturePath);
            $filename_utf8 = $this->convertFromSystemEncoding($filename_system);
            
            $relativePath_utf8 = dirname($relativePath) . '/' . $filename_utf8;
            
            $pathParts = explode('/', $relativePath_utf8);
            $encodedParts = array_map('rawurlencode', $pathParts);
            $encodedPath = implode('/', $encodedParts);
            
            $url = URL::base() . $encodedPath;
            
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
        return URL::site('order/PersonalData/' . $id_pep);
    }

    private function getFullName($person) {
        $surname = !empty($person['SURNAME']) ? trim($person['SURNAME']) : 'Unknown';
        $name = !empty($person['NAME']) ? trim($person['NAME']) : 'Unknown';
        $patronymic = !empty($person['PATRONYMIC']) ? trim($person['PATRONYMIC']) : 'Unknown';

        if (!mb_check_encoding($surname, 'UTF-8')) {
            $surname = $this->convertFromSystemEncoding($surname);
        }
        if (!mb_check_encoding($name, 'UTF-8')) {
            $name = $this->convertFromSystemEncoding($name);
        }
        if (!mb_check_encoding($patronymic, 'UTF-8')) {
            $patronymic = $this->convertFromSystemEncoding($patronymic);
        }

        $full_name = trim($surname . '_' . $name . '_' . $patronymic);
        $full_name = preg_replace('/\s+/', '_', $full_name);
        
        Kohana::$log->add(Log::DEBUG, 'PD::getFullName: Full name (UTF-8): ' . $full_name);
        
        return $full_name;
    }

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

    // Новые вспомогательные методы для правильной работы с путями и кодировками

    /**
     * Нормализует путь для корректной работы на разных ОС и дисках
     */
    private function normalizePath($path) {
        if (DIRECTORY_SEPARATOR === '\\') {
            // Windows
            $path = str_replace('/', '\\', $path);
            $path = rtrim($path, '\\');
            // Не убираем завершающий слэш для корня диска
            if (preg_match('/^[A-Za-z]:$/', $path)) {
                $path .= '\\';
            }
        } else {
            // Unix/Linux
            $path = str_replace('\\', '/', $path);
            $path = rtrim($path, '/');
            if (empty($path)) {
                $path = '/';
            }
        }
        return $path;
    }

    /**
     * Конвертирует строку в кодировку файловой системы
     */
    private function convertToSystemEncoding($string) {
        if (DIRECTORY_SEPARATOR === '\\') {
            // Windows использует CP1251 для кириллицы в файловой системе
            if (function_exists('iconv')) {
                return iconv('UTF-8', 'CP1251//IGNORE', $string);
            } elseif (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($string, 'CP1251', 'UTF-8');
            }
        }
        // Unix/Linux обычно использует UTF-8
        return $string;
    }

    /**
     * Конвертирует строку из кодировки файловой системы в UTF-8
     */
    private function convertFromSystemEncoding($string) {
        if (DIRECTORY_SEPARATOR === '\\') {
            // Windows использует CP1251 для кириллицы в файловой системе
            if (!mb_check_encoding($string, 'UTF-8')) {
                if (function_exists('iconv')) {
                    return iconv('CP1251', 'UTF-8//IGNORE', $string);
                } elseif (function_exists('mb_convert_encoding')) {
                    return mb_convert_encoding($string, 'UTF-8', 'CP1251');
                }
            }
        }
        return $string;
    }

    /**
     * Безопасное сканирование директории с обработкой кодировок
     */
    private function scanDirectorySafe($directory) {
        $files = @scandir($directory);
        if ($files === false) {
            return false;
        }

        // Для Windows конвертируем имена файлов в UTF-8
        if (DIRECTORY_SEPARATOR === '\\') {
            $converted_files = array();
            foreach ($files as $file) {
                $converted_files[] = $this->convertFromSystemEncoding($file);
            }
            return $converted_files;
        }

        return $files;
    }

    /**
     * Проверяет доступность пути с учетом разных дисков
     */
    private function isPathAccessible($path) {
        $normalized_path = $this->normalizePath($path);
        
        // Для Windows проверяем доступность диска
        if (DIRECTORY_SEPARATOR === '\\') {
            $drive = substr($normalized_path, 0, 2);
            if (preg_match('/^[A-Za-z]:$/', $drive)) {
                if (!is_dir($drive . '\\')) {
                    return false;
                }
            }
        }

        return is_dir($normalized_path) && is_readable($normalized_path);
    }
}