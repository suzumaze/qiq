# 一般的なヘルパー

全てのヘルパーは適切なエスケープを自動的に適用します。これは、`{{= ... }}` を使って出力できることを意味します。`{{h ... }}` などを使うと、出力が二重にエスケープされてしまいます。

PHPテンプレートコードでは、`$this` のメソッドとしてヘルパーを呼び出すこともできます。

最後に、これらのヘルパーの多くは、HTMLタグの属性として名前付きパラメータの可変長リストを受け取ります。これは、ヘルパーメソッドのパラメータであるかのように、ほぼすべての属性を追加できることを意味します。パラメータ名のアンダースコアはダッシュに変換されます。例えば、`foo_bar: 'baz'` は、ヘルパーの出力で `foo-bar="baz"` になります。名前付きパラメータとして使用できない属性については、`attr` 配列パラメータを使用してください。

例：

```qiq
{{= anchor (
    'http://qiqphp.com',
    'Qiq Project',
    attr: [                 // (array) オプションのキー・バリュー属性
        'xml:lang' => 'en',
    ],
    id: 'qiq-link',         // (...mixed) オプションの名前付きパラメータ属性
) }}
```

このサンプルコードは以下のHTMLを生成します：

```html
<a href="http://qiqphp.com" xml:lang="en" id="qiq-link">Qiq for PHP</a>
```

## anchor

`<a>` タグのためのヘルパー。

```qiq
{{= anchor (
    'http://qiqphp.com',    // (string) href
    'Qiq Project',          // (string) text
    attr: [],               // (array) オプションのキー・バリュー属性
    id: 'qiq-link',         // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<a href="http://qiqphp.com" id="qiq-link">Qiq for PHP</a>
```

## base

`<base>` タグのためのヘルパー。

```qiq
{{= base (
    '/base'                 // (string) href
) }}
```

```html
<base href="/base" />
```

## dl

`<dt>`/`<dd>` アイテムを持つ `<dl>` タグのためのヘルパー。

```qiq
{{= dl (
    [                       // (array) dtキーとdd値
        'foo' => 'Foo Def',
        'bar' => [
            'Bar Def A',
            'Bar Def B',
            'Bar Def C',
        ],
        'baz' => 'Baz Def',
    ],
    attr: [],               // (array) オプションのキー・バリュー属性
    id: 'test'              // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<dl id="test">
    <dt>foo</dt>
        <dd>Foo Def</dd>
    <dt>bar</dt>
        <dd>Bar Def A</dd>
        <dd>Bar Def B</dd>
        <dd>Bar Def C</dd>
    <dt>baz</dt>
        <dd>Baz Def</dd>
</dl>
```

## image

`<img>` タグのためのヘルパー。

```qiq
{{= image (
    '/images/hello.jpg',    // (string) 画像の href src
    attr: [],               // (array) オプションのキー・バリュー属性
    id: 'image-id'          // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<!-- altが指定されていない場合、画像hrefのベースネームを使用 -->
<img src="/images/hello.jpg" alt="hello" id="image-id" />
```

## items

一連の `<li>` タグのためのヘルパー。

```qiq
{{= items ([                // (array) list items
    'foo',
    'bar',
    'baz'
]) }}
```

```html
<li>foo</li>
<li>bar</li>
<li>baz</li>
```

## link

`<link>` タグのためのヘルパー。

```qiq
{{= link (
    rel: 'prev',
    href: '/path/to/prev',
    attr: [],               // (array) オプションのキー・バリュー属性
    id: 'link-id'           // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<link rel="prev" href="/path/to/prev" id="link-id" />
```

## linkStylesheet

`<link>` スタイルシートタグのためのヘルパー。

```qiq
{{= linkStylesheet (
    '/css/print.css',       // (string) スタイルシートのhref
    attr: [],               // (array) オプションのキー・バリュー属性
    media: 'print'          // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<!-- typeが指定されていない場合、"text/css"を使用 -->
<!-- mediaが指定されていない場合、"screen"を使用 -->
<link rel="stylesheet" href="/css/print.css" type="text/css" media="print" />
```

## meta

`<meta>` タグのためのヘルパー。

一般的な使用法：

```qiq
{{= meta (
    attr: [],               // (array) オプションのキー・バリュー属性
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

`charset`の場合：

```qiq
{{= meta (
    charset: 'utf-8'
) }}
```

```html
<meta charset="utf-8">
```

`http-equiv` の場合：

```qiq
{{= meta (
    http_equiv: 'Location',
    content: '/redirect/to/here'
) }}
```

```html
<meta http-equiv="Location" content="/redirect/to/here">
```

`name` の場合：

```qiq
{{= meta (
    name: 'author',
    content: 'Qiq for PHP'
) }}
```

```html
<meta name="author" content="Qiq for PHP">
```

## ol

`<li>` アイテムを持つ `<ol>` タグのためのヘルパー。

```qiq
{{= ol (
    [                       // (array) list items
        'foo',
        'bar',
        'baz'
    ],
    attr: [],               // (array) オプションのキー・バリュー属性
    id: 'foo-list'          // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<ol id="foo-list">
    <li>foo</li>
    <li>bar</li>
    <li>baz</li>
</ol>
```

## script

`<script>` タグのためのヘルパー。

```qiq
{{= script (
    '/js/functions.js',     // (string) src属性
    attr: [],               // (array) オプションのキー・バリュー属性
    async: true             // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<!-- typeが指定されていない場合、"text/javascript"を使用 -->
<script src="/js/functions.js" type="text/javascript" async></script>
```

## ul

`<li>` アイテムを持つ `<ul>` タグのためのヘルパー。

```qiq
{{= ul (
    [                       // (array) リストアイテム
        'foo',
        'bar',
        'baz'
    ],
    attr: [],               // (array) オプションのキー・バリュー属性
    id: 'foo-list'          // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<ul id="foo-list">
    <li>foo</li>
    <li>bar</li>
    <li>baz</li>
</ul>
```
