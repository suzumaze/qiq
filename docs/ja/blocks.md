# Blocks

ブロックはパーシャルに似ていますが、後で使用するためにインラインでキャプチャされる点が異なります。

一般的に、ブロックはビューテンプレートによってレイアウトテンプレート用の出力をキャプチャするために使用されます。

あるいは、ブロックは子テンプレートによって親テンプレート用の出力をキャプチャするために使用されることもあります（[継承](./inheritance.md)のドキュメントを参照）。

例えば、レイアウトテンプレートファイルでは、ローカルナビゲーション用のブロックを定義し、そのブロックにデフォルトのコンテンツを追加することができます：

```html+php
<html>
<head>
<title>Blocks Example</title>
</head>
<body>
<div id="local-nav">

{{ setBlock ('local-nav') }}
    <p><a href="/foo">Foo</a></p><!-- layout -->
{{= getBlock () ~}}

</div>
</body>
</html>
```

`setBlock()` メソッドは指定されたブロックを開き、`getBlock()` はブロックを閉じて、2つのメソッド呼び出しの間でキャプチャされた出力をエコーします。

次に、ビューファイルでそのブロックを再定義することができます：

```html+php
{{ setBlock ('local-nav') }}
    <p><a href="/bar">Bar</a></p><!-- view, 親より上 -->
    {{ parentBlock() }}
    <p><a href="/baz">Baz</a></p><!-- view, 親より下 -->
{{ endBlock () }}
```

上記の `parentBlock()` の使用に注目してください。このメソッドは親ブロックのコンテンツのプレースホルダーであり、必要に応じてそのコンテンツの前後に追加することができます（`parentBlock()` を呼び出さない場合、`setBlock()` 呼び出しは親ブロックのコンテンツを完全に上書きします）。

最後に、結合されたビューとレイアウトをレンダリングすると...

```php
$template = Template::new(...);
$template->setLayout('layout');
$template->setView('view');
$output = $template();
```

... 次のような出力が生成されます：

```html
<html>
<head>
<title>Blocks Example</title>
</head>
<body>
<div id="local-nav">
    <p><a href="/bar">Bar</a></p><!-- view, 親より上 -->
    <p><a href="/foo">Foo</a></p><!-- layout -->
    <p><a href="/baz">Baz</a></p><!-- view, 親より下 -->
</div>
</body>
</html>
```
