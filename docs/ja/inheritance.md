# 継承

内部の「ビュー」テンプレートを外部の「レイアウト」テンプレートでラップできるのと同様に、あるテンプレートを別のテンプレートで「拡張」することもできます。その違いは微妙ですが重要です。

以下はテンプレート継承の例です。まず、一連のブロックで構成される「親」テンプレート：

```html+php
<!DOCTYPE html>
<html lang="en">
<head>
{{ setBlock ('head_title') }}{{= getBlock () ~}}
{{ setBlock ('head_meta') }}{{= getBlock () ~}}
{{ setBlock ('head_links') }}{{= getBlock () ~}}
{{ setBlock ('head_styles') }}
    <link rel="stylesheet" href="/theme/basic.css" type="text/css" media="screen" />
{{= getBlock () ~}}
{{ setBlock ('head_scripts') }}{{= getBlock () ~}}
</head>
<body>
{{ setBlock ('body_header') }}{{= getBlock () ~}}
{{ setBlock ('body_content') }}{{= getBlock () ~}}
{{ setBlock ('body_footer') }}{{= getBlock () ~}}
</body>
</html>
```

上記のコードは `setBlock()` を通じて一連のブロックを定義し、`getBlock()` を通じて *最終的な* ブロックの内容を表示します。（タグ後の改行を消費する閉じタグ `~}}` の使用に注目してください。これにより出力の空白行が圧縮されます。）

次に、「親」テンプレートを拡張する「子」テンプレートです。「親」テンプレートを `extends()` で拡張し、親ブロックからの内容をオーバーライドまたは修正していることに注目してください：

```html+php
{{ extends ('parent') }}

{{ setBlock ('head_title') }}
    <title>
        My Extended Page
    </title>
{{ endBlock () }}

{{ setBlock ('head_meta') }}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
{{ endBlock () }}

{{ setBlock ('head_styles') }}
{{ parentBlock () }}
    <link rel="stylesheet" href="/theme/custom.css" type="text/css" media="screen" />
{{ endBlock () }}

{{ setBlock ('body_content') }}
    <p>The main content for my extended page.</p>
{{ endBlock () }}
```

最後に、「子」テンプレートをレンダリングすると...

```php
$output = $template('child');
```

.....出力は以下のようになります：

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        My Extended Page
    </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="/theme/basic.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/theme/custom.css" type="text/css" media="screen" />
</head>
<body>
    <p>The main content for my extended page.</p>
</body>
</html>
```

同様のアプローチはビューとレイアウトでも可能です。しかし、その方法では1層の拡張のみが可能です（つまり、ビューからレイアウトへ）。`extends()` を使用すると、任意の数の層が可能になります。

ビューとレイアウトの両方が `extends()` を使用できます。つまり、内部ビューは一連のテンプレートを拡張し、外部レイアウトは異なる一連のテンプレートを拡張することができます。

ブロックはビューとレイアウトの間で共有されます。レイアウトのブロックはビューから参照でき、その逆も可能です。

最後に、テンプレートを拡張する際、`getContent()` が期待通りに機能しない場合があります。ブロック *以外* のコンテンツは `extends()` の連続呼び出しごとに上書きされるため、最後にレンダリングされたテンプレートのコンテンツのみが捕捉されます。そのため、上記の例のように、ビューの「メイン」コンテンツを独自のブロック内に捕捉するのが最善で、`getContent()` を使用するよりも良いでしょう。
