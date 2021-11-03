# Яндекс.Турбо страницы (API)

Этот пакет позволяет выгружать RSS Feed через API.

## Установка
Установить можете с помощью composer

```
composer require c7v/yii-yandex-turbo-pages-api
```

либо указать в composer.json

```
"c7v/yii-yandex-turbo-pages-api": "dev-main"
```

## Примеры использования:
Класс Turbo наследован от yii\base\Component, можно использовать как компанен Yii:

```php
[
    ...
    'components' => [
        ...
        'turbo' => [
            'class' => 'yii\rbac\DbManager',
            'token' => 'AQAAAAAZC7gVAAd6mP8Mc6eКeEn4tMqZL2NsZ21',
            'host' => 'https:temj.ru:443',
        ],
        ...
    ],
    ...
]
```
#### Получения ID пользователя через компонент:
```php
    Yii::$app->turbo->getIdUser();
```

### Пример запроса статуса выгрузки:
```php
use c7v\yii_yandex_turbo_pages_api\webmaster\Turbo;

$turbo = new Turbo([
    'token' => 'AQAAAAAZC7gVAAd6mP8Mc6eКeEn4tMqZL2NsZ21',
    'host' => 'https:temj.ru:443'
]);

/** @var integer $user_id */
$user_id = $turbo->getIdUser();

/** @var array $status */
$status = $turbo->getStatus($user_id, '813b6cc0-3c23-11ec-b72e-4356c923ca0e');
```

### Пример получения URL для выгрузки RSS feed:
```php
use c7v\yii_yandex_turbo_pages_api\webmaster\Turbo;

$turbo = new Turbo([
    'token' => 'AQAAAAAZC7gVAAd6mP8Mc6eКeEn4tMqZL2NsZ21',
    'host' => 'https:temj.ru:443'
]);

/** @var integer $user_id */
$user_id = $turbo->getIdUser();

/** @var array $dataUpload */
$dataUpload = $turbo->getUploadAddress($user_id); // Возаращает время жизни и URL.
```

### Пример загрузки RSS feed:

```php
use c7v\yii_yandex_turbo_pages_api\webmaster\Turbo;

$turbo = new Turbo([
    'token' => 'AQAAAAAZC7gVAAd6mP8Mc6eКeEn4tMqZL2NsZ21',
    'host' => 'https:temj.ru:443'
]);

/** @var integer $user_id */
$user_id = $turbo->getIdUser();

/** @var array $dataUpload */
$dataUpload = $turbo->getUploadAddress($user_id); // Возаращает время жизни и URL.

/** @var array $task_id */
$task_id = $turbo->uploadFile($dataUpload['upload_address'], Yii::getAlias('@app/web/turbo.rss'))
```

### Пример получения статуса:

```php
use c7v\yii_yandex_turbo_pages_api\webmaster\Turbo;

$turbo = new Turbo([
    'token' => 'AQAAAAAZC7gVAAd6mP8Mc6eКeEn4tMqZL2NsZ21',
    'host' => 'https:temj.ru:443'
]);

/** @var integer $user_id */
$user_id = $turbo->getIdUser();

$status = $turbo->getStatus($user_id, 'Is id task');
```
#### Результат проверки статуса:
```php
Array
(
    [mode] => PRODUCTION
    [load_status] => OK
    [turbo_pages] => Array
        (
            [0] => Array
                (
                    [link] => https://temj.ru/a/test
                    [preview] => https://yandex.ru/turbo?text=https%3A%2F%2Ftemj.ru%2Fa%2Ftest&from=webmaster&ncrnd=-4122400180298628800
                    [title] => Ресторан «Полезный завтрак»
                )

        )

    [errors] => Array
        (
        )

    [stats] => Array
        (
            [pages_count] => 1
            [errors_count] => 0
            [warnings_count] => 0
        )

)
```