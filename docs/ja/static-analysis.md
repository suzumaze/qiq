# 静的解析

Qiqテンプレートファイルは、PHPStanなどの静的解析ツールで簡単に分析できます。分析を有効にするにはdocblockのみが必要です。このdocblockは、_Template_ メソッド、ヘルパー、および変数を分析ツールが認識できるようにするものです。

## 分析の有効化

分析対象の各テンプレートファイルに、_Qiq\Engine_ と _Helpers_ オブジェクトの交差型を使用して `$this` の型を指定するdocblockを追加します。

また、テンプレートファイルで使用される各変数を必ずドキュメント化してください。

PHPコードでは、docblockは以下のようになるかもしれません...

```
<?php
/**
 * @var \Qiq\Engine&\Qiq\Helper\Html\HtmlHelpers $this
 * @var string $name
 */
?>
Hello <?= $this->h($name) ?>!
```

...一方、Qiq構文では以下のようになるかもしれません：

```qiq
{{ /** @var \Qiq\Engine&\Qiq\Helper\Html\HtmlHelpers $this */ }}
{{ /** @var string $name */ }}

Hello {{h $name }}!
```

### カスタム型付け

この型ヒントが冗長だと感じる場合、静的解析ツールでカスタム疑似型を定義できる場合があります。例えば、PHPStanの設定エントリで以下の型エイリアスを定義できます：

```yaml
parameters:
    typeAliases:
        HtmlTemplate: \Qiq\Engine&\Qiq\Helper\Html\HtmlHelpers
```

そして、テンプレートファイルのdocblockで、`$this` をカスタム型として指定できます：

```qiq
{{ /** @var HtmlTemplate $this */ }}
```

### ミックスイン

あるいは、_Template_ クラス自体を拡張し、適切な _Helpers_ クラスの `@mixin` を指定することもできます。例えば：

```php
namespace Project;

use Qiq\Catalog;
use Qiq\Helper\Html\HtmlHelpers;
use Qiq\Template;

/**
 * @mixin HtmlHelpers
 */
class HtmlTemplate extends Template
{
    public function __construct(
        Catalog $catalog,
        HtmlHelpers $helpers
    ) {
        parent::__construct($catalog, $helpers)
    }
}
```

そして、テンプレートファイルのdocblockで、拡張クラス名を使用できます：

```qiq
{{ /** @var Project\HtmlTemplate $this */ }}
```

## 分析の実行

テンプレートファイルがPHPのみの場合、これで十分です：ソースの場所にあるままで静的解析を実行できます。

ただし、Qiq構文を含むテンプレートファイルの場合、静的解析の前にテンプレートファイルをPHPにコンパイルする必要があります。そのために、テンプレートファイルをレンダリングする _Template_ クラスをインスタンス化し、_Catalog_ 内のすべてのテンプレートファイルを `compileAll()` します：


```php
$cachePath = '/path/to/compiled';

$template = Template::new(
    paths: ...,
    cachePath: $cachePath,
);

$template->getCatalog()->compileAll();
```

その後、コンパイルされたテンプレートファイルの `$cachePath` ディレクトリに対して静的解析を実行できます（分析不可能なQiqコードを含むソーステンプレートファイルに対してではありません）。

上記の `$cachePath` 例を考えると、コンパイルされたテンプレートファイルの静的解析のためのPHPStan設定エントリには、以下のようなエントリが含まれるかもしれません：

```neon
parameters:
    paths:
        - /path/to/compiled/
```

## 分析の問題の解決

静的解析によって明らかになった問題のデバッグと解決は簡単です。

コンパイルされたテンプレートファイルはソーステンプレートファイルのパスを使用して `$cachePath` に保存されるため、どのソーステンプレートファイルに問題があるかを簡単に確認できます。

さらに、コンパイルされたテンプレートコードの行がソーステンプレートコードの行と一致するため、報告される行番号も一致します。

そこから、他のPHPコードと同様にソーステンプレートファイルの問題を解決し、再コンパイルして再分析します。
