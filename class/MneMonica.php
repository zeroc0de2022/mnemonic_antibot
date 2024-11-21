<?php
declare(strict_types=1);
/*
Date: 20.05.2022
Author: zeroc0de <98693638+zeroc0de2022@users.noreply.github.com>
*/
namespace class;

use RuntimeException;

session_name('SessionID');
session_start();

/**
 * Class MneMonica
 *
 * @package class
 */
class MneMonica
{
    private static int $difficulty = 50;

    /**
     * Проверяет токен на соответствие ранее сгенерированному значению.
     *
     * @param string $hash Токен для проверки.
     * @return bool Результат проверки.
     */
    public static function checkHash(string $hash): bool
    {
        return $hash === self::getSessionValue('decodedToken');
    }

    /**
     * Возвращает сгенерированный токен.
     * @return string
     */
    public static function getHash(): string
    {
        return static::setHash();
    }

    /**
     * Генерирует новый токен и сохраняет его в сессии.
     * @return string
     */
    private static function setHash(): string
    {
        $mnemo = self::generateMnemonic();
        $rand_position = mt_rand(0, self::$difficulty - 1);
        $rand_string = self::generateRandStr(static::$difficulty);

        // Вставляем случайный символ в случайную позицию
        $generated_token = substr_replace($rand_string, $mnemo['name'], $rand_position, 1);
        $decoded_token = html_entity_decode($generated_token);

        // Сохраняем в сессии
        static::setSessionValue('generatedToken', $generated_token);
        static::setSessionValue('decodedToken', $decoded_token);

        return $generated_token;
    }

    /**
     * Генерирует случайное мнемоническое значение.
     *
     * @return array Сгенерированный символ и его декодированное значение.
     * @throws RuntimeException Если не удается сгенерировать мнемоническое значение.
     */
    private static function generateMnemonic(): array
    {
        $entities = [];
        // Создаем массив с мнемоническими сущностями
        foreach (range(9, self::$difficulty * 1000) as $number) {
            $entity = self::numToEntity($number);
            if(is_array($entity)) {
                $entities[] = $entity;
            }
        }
        if (empty($entities)) {
            throw new RuntimeException('Не удалось сгенерировать мнемоническое значение.');
        }
        // Выбираем случайную мнемоническую сущность
        shuffle($entities);
        return array_shift($entities);
    }

    /**
     * Преобразует число в мнемоническую сущность.
     *
     * @param int $number Число.
     * @param string $encoding Кодировка.
     * @return array|int Мнемоническая сущность или 0, если невалидно.
     */
    private static function numToEntity(int $number, string $encoding = 'UTF-8'): array|bool
    {
        // Переводим в мнемоническую сущность
        $entity = "&#$number;";
        $decoded = html_entity_decode($entity, ENT_QUOTES, $encoding);
        $entity_name = htmlentities($decoded, ENT_SUBSTITUTE, $encoding);
        // Проверяем валидность
        if (self::isEntityValid($entity_name)) {
            return ['name' => $entity_name, 'decoded' => $decoded];
        }
        return false;
    }

    /**
     * Проверяет валидность мнемонической сущности.
     *
     * @param string $name Название сущности.
     * @return bool Результат проверки.
     */
    private static function isEntityValid(string $name): bool
    {
        return preg_match('~^&\w+;$~', $name) === 1;
    }

    /**
     * Генерирует случайную строку указанной длины.
     *
     * @param int $length Длина строки.
     * @return string Случайная строка.
     */
    private static function generateRandStr(int $length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
        return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
    }

    /**
     * Получает значение из сессии.
     * @param string $key Ключ.
     * @return mixed Значение из сессии или значение по умолчанию.
     */
    private static function getSessionValue(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Устанавливает значение в сессию.
     *
     * @param string $key Ключ.
     * @param mixed $value Значение.
     * @return void
     */
    private static function setSessionValue(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }
}
