# カスタムヘルパー

_Template_ オブジェクトにカスタムの _Helpers_ オブジェクトを提供することができます。このカスタム _Helpers_ オブジェクトに、好きなヘルパーメソッドを追加できます。あなたのヘルパーメソッドは、その _Template_ インスタンスによって処理されるテンプレートファイル内で利用可能になります。

## _Helpers_ クラスの作成

最も簡単な方法は、_HtmlHelpers_ を拡張して新しいクラスにメソッドを追加することです。

例えば、以下のカスタム _Helpers_ クラスは、文字列をROT-13で暗号化し、適切にエスケープするメソッドを追加しています：

```php
<?php
namespace Project\Template\Helper;

use Qiq\Helper\Html\HtmlHelpers;

class CustomHelpers extends HtmlHelpers
{
    public function rot13(string $str) : string
    {
        return $this->h(str_rot13($str));
    }
}
```

あるいは、ベースの _Helpers_ オブジェクトを拡張し、_HtmlHelperMethods_ トレイトを使用することもできます：

```php
<?php
namespace Project\Template\Helper;

use Qiq\Helper\Html\HtmlHelperMethods;
use Qiq\Helpers;

class CustomHelpers extends Helpers
{
    use HtmlHelperMethods;

    public function rot13(string $str) : string
    {
        return $this->h(str_rot13($str));
    }
}
```

最後に、HTMLを全く使用しない場合は、単に _Helpers_ クラスを拡張するだけで構いません。

```php
<?php
namespace Project\Template\Helper;

use Qiq\Helpers;

class CustomHelpers extends Helpers
{
    public function rot13(string $str) : string
    {
        return str_rot13($str);
    }
}
```

## _Helpers_ の使用

カスタム _Helpers_ クラスを作成したら、そのインスタンスを使用して _Template_ を作成します：

```php
use Project\Template\Helper\CustomHelpers;
use Qiq\Template;

$template = Template::new(
    paths: ...,
    helpers: new CustomHelpers(),
);
```

これで、テンプレートファイル内でカスタムヘルパーメソッドを使用できます。

プレーンなPHPで使用する場合 ... 

```html+php
<?= $this->rot13('Uryyb Jbeyq!'); ?>
```
... もしくは、Qiq構文で使用する場合：

```html+php
{{= rot13 ('Uryyb Jbeyq!') }}
```

どちらの方法でも、出力は "Hello World!" となります。

## ヘルパークラス

必要に応じて、ヘルパーのロジックをクラスに配置し、オートワイヤリングされる _Qiq\Container_（後述）からそのクラスのインスタンスを `$this->get()` を使用して取得することができます。

例えば、ROT-13のロジックをクラスに配置した場合...

```php
<?php
namespace Project\Template\Helper;

use Qiq\Helper\Html\Escape;

class Rot13
{
    public function __construct(protected Escape $escape)
    {
    }

    public function __invoke(string $str): string
    {
        return $this->escape->h(str_rot13($str));
    }
}
```

... このようにして、カスタム _Helpers_ オブジェクト内からそのクラスのインスタンスを `get()` で取得し、好きなように使用できます

```php
<?php
namespace Project\Template\Helper;

use Project\Template\Helper\Rot13;
use Qiq\Helper\Html\HtmlHelpers;

class CustomHelpers extends HelperHelpers
{
    public function rot13(string $str) : string
    {
        return $this->get(Rot13::class)->__invoke($str);
    }
}
```

## _Helpers_ コンテナ

_Helpers_ クラスはオートワイヤリングされる _Qiq\Container_ オブジェクトを使用します。カスタムヘルパーメソッド内で、`$this->get()` を使用して _Qiq\Container_ からオブジェクトを取得できます。
_Qiq\Container_ を設定するには、クラスコンストラクタのパラメータ名と値の配列を使用してインスタンス化し、それを使用して Helpers を作成します。例えば、Escape のエンコーディングを UTF-8 以外に変更する場合：

```php
use Project\Template\Helper\CustomHelpers;
use Qiq\Container;
use Qiq\Helper\Html\Escape;
use Qiq\Template;

$container = new Container([
    Escape::class => [
        'encoding' => 'EUC-JP'
    ],
]);

$template = Template::new(
    paths: ...,
    helpers: new CustomHelpers($container)
);
```

_Qiq\Container_ は比較的低機能です。必要に応じて、_Qiq\Container_ を任意の [PSR-11](https://www.php-fig.org/psr/psr-11/) _ContainerInterface_ インスタンスに置き換えることができます

```php
use Project\Template\Helper\CustomHelpers;
use Project\Psr11Container;
use Qiq\Template;

$psr11container = new Psr11Container();

$template = Template::new(
    paths: ...,
    helpers: new CustomHelpers($psr11container)
);
```
