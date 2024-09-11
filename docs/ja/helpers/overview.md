# 概要

ヘルパーは、出力を生成するクラスメソッドです。PHPテンプレートコードでは `$this` のメソッドとして、Qiq構文を使用する場合はヘルパー名だけで呼び出すことができます。

PHP構文：

```html+php
<?= $this->anchor('http://qiqphp.com', 'Qiq for PHP') ?>
```

Qiq構文：

```qiq
{{= anchor ('http://qiqphp.com', 'Qiq for PHP') }}
```

両方とも次のHTMLを生成します：

```html
<a href="http://qiqphp.com">Qiq for PHP</a>
```

Qiqには、[一般的な用途](./general.md)や[フォームの構築](./forms.md)のための包括的なヘルパーセットが付属しています。また、[カスタムヘルパー](./custom.md)を作成することもできます。

さらに、テンプレートファイルから任意のパブリックまたはプロテクテッドな _Template_ メソッドを呼び出すことができます（これはテンプレートファイルが _Template_ オブジェクト「内部」で実行されるためです）。特に、任意のテンプレート内からレイアウトを設定したり、他のテンプレートをレンダリングしたりすることができます：

```qiq
{{ setLayout ('seasonal-layout') }}

{{= render ('some/other/template') }}
```
