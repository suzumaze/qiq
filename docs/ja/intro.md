# 入門

## インストール方法

QiqはComposerから [qiq/qiq](https://packagist.org/packages/qiq/qiq) としてインストールします。

```
composer require qiq/qiq ^2.0
```

## はじめに

まず、テンプレートファイルは`/path/to/templates/hello.php`に保存されているとします。

```html+php
Hello, {{h $name }}. That was Qiq!

And this is PHP, <?= $this->h($name) ?>.
```

次は`hello` テンプレートを使って出力を生成するためのプレゼンテーションコードです。

```php
use Qiq\Template;

$template = Template::new('/path/to/templates');
$template->setView('hello');
$template->setData([
    'name' => 'World'
]);
echo $template();
```

これだけです。
