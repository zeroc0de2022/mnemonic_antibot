# Mnemonic Form Protection

[Русский](https://github.com/zeroc0de2022/mnemonic_antibot/blob/main/README.md)

The `MneMonica` class provides a simple way to protect forms from automated bots by leveraging mnemonic symbols.
The difference occurs when bots see numeric entity codes (e.g. `&#65;`) in the source code, while humans see and send their decoded characters (e.g. `A`) in the browser.
Bots cannot interpret the character the same way browsers do, which provides a lightweight and effective defense mechanism against bots (that do not use browser engines).

Let me try to explain how the class works, how to integrate it into your forms, and how it protects against simple bots.
---

## **How It Works**

1. **Token Generation**:  
   When a form is displayed, the `MneMonica` class generates a "mnemonic token" containing both visible characters for humans and encoded values for bots. The visible character is
   embedded into a random position in the token, making it harder for bots to guess.

2. **Token Validation**:  
   After the form is submitted, the entered token is compared with the token stored in the session. If the token matches, the user is identified as human; otherwise, they are
   flagged as a bot.

3. **Protection Concept**:
    - Humans see the decoded symbol and can input the visible value.
    - Bots see only the numeric entity code, which they cannot reliably decode into the correct character.

---

## **Usage Instructions**

### **File Structure**

```
project/
│
├── class/
│   └── MneMonica.php  // Contains the MneMonica class.
├── index.php          // Entry point demonstrating form protection.
└── README.md          // This documentation file.
```

### **1. Include the MneMonica Class**

Add the following code to the beginning of your script:

```php
use class\MneMonica;

require __DIR__ . '/class/MneMonica.php';
```

### **2. Generate Token for Form**

Before rendering the form, call `MneMonica::getHash()` to generate a new token on page load and store it in the session.

```php
MneMonica::getHash();
```

You can retrieve the generated token using `MneMonica::getHash()` to populate the form field:

```php
<input type="hidden" name="token" value="<?= MneMonica::getHash() ?>" />
```

### **3. Validate Form Submission**

When the form is submitted, retrieve the user-provided token (`$_POST['token']`) and validate it using `MneMonica::checkHash()`:

```php
if (isset($_POST['submit'], $_POST['token'])) {
    echo (MneMonica::checkHash($_POST['token']))
        ? '<h1>Human</h1>'
        : '<h1>Bot</h1>';
}
```
---

## **Key Methods in the MneMonica Class**

### **1. `MneMonica::getHash()`**
- Retrieves the currently generated token for use in the form.

### **2. `MneMonica::checkHash($token)`**
- Validates the submitted token against the one stored in the session.
- Returns `true` if the tokens match, otherwise `false`.

---

## **Conclusion**

The `MneMonica` class is a simple yet effective tool for securing forms against bots. Its approach leverages a unique combination of randomized tokens and encoding challenges to
distinguish humans from automated scripts while maintaining an intuitive user experience.
