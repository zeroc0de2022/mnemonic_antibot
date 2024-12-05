# Мнемоническая защита форм от спам-ботов

[English](https://github.com/zeroc0de2022/mnemonic_antibot/blob/main/README-en.md)


Класс `MneMonica` предоставляет простой способ защиты форм от автоматизированных ботов, используя мнемонические символы.
Отличие возникает, когда боты видят числовые коды сущностей (например, `&#65;`) в исходном коде, а люди видят их декодированные символы (например, `A`) в браузере.
Боты не могут интерпретировать символ так, как это делают браузеры, что обеспечивает легкий и эффективный механизм защиты от ботов (не использующих браузерные движки).

Давайте разберемся, как работает класс, как интегрировать его в ваши формы и как он защищает от простых ботов.
---

## **Как это работает**

1. **Генерация токена**:

При отображении формы класс `MneMonica` генерирует «мнемонический токен», содержащий как видимые символы для людей, так и закодированные значения для ботов.
Видимый символ встраивается в случайную позицию токена, что затрудняет его угадывание ботами.

2. **Проверка токена**:
   После отправки формы введенный токен сравнивается с токеном, сохраненным в сеансе. Если токен совпадает, пользователь идентифицируется как человек; иначе он помечается как бот.

3. **Концепция защиты**:
- Люди видят декодированный символ и могут ввести видимое значение.
- Боты видят только числовой код сущности, который они не могут надежно декодировать в правильный символ.

---

## **Инструкции по использованию**

### **Структура файла**

```
project/
│
├── class/
│ └── MneMonica.php // Содержит класс MneMonica.
├── index.php // Точка входа, демонстрирующая защиту формы.
└── README.md // Этот файл документации.
```

### **1. Включите класс MneMonica**

Добавьте следующий код в начало вашего скрипта:

```php
use class\MneMonica;

require_once __DIR__ . '/class/MneMonica.php';
```

### **2. Сгенерировать токен для формы**

Перед отображением формы вызовите `MneMonica::getHash()`, чтобы сгенерировать новый токен при загрузке страницы и сохранить его в сессии.

```php
MneMonica::getHash();
```

Вы можете получить сгенерированный токен с помощью `MneMonica::getHash()` для заполнения поля формы:

```php
<input type="hidden" name="token" value="<?= MneMonica::getHash() ?>" />
```

### **3. Проверка отправки формы**

Когда форма отправлена, извлеките предоставленный пользователем токен (`$_POST['token']`) и проверьте его с помощью `MneMonica::checkHash()`:

```php
if (isset($_POST['submit'], $_POST['token'])) {
   echo (MneMonica::checkHash($_POST['token']))
      ? '<h1>Человек</h1>'
      : '<h1>Бот</h1>';
}
```
---

## **Ключевые методы в классе мнемоники**


### **1. `MneMonica::getHash()`**
- Извлекает текущий сгенерированный токен для использования в форме.

### **2. `MneMonica::checkHash($token)`**
- Проверяет отправленный токен на соответствие токену, сохраненному в сеансе.
- Возвращает `true`, если токены совпадают, в противном случае `false`.

---

## **Вывод**

Класс `MneMonica` — это простой, но эффективный инструмент для защиты форм от ботов. Его подход использует уникальное сочетание рандомизированных токенов и проблем кодирования, чтобы отличать людей от автоматизированных скриптов, сохраняя при этом интуитивно понятный пользовательский интерфейс.
