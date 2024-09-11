# テンプレート構文

QiqテンプレートはネイティブのPHPテンプレートですが、簡潔なエスケープとヘルパーの使用のために、オプションで `{{ ... }}` 構文を使用できます。

## エスケープと出力

Qiqは、開始タグが認識されるエスケープ文字で始まらない限り、自身では何も出力しません：

- `{{ ... }}` は単独では何も出力しません
- `{{= ... }}` はエスケープされていない生の出力をエコーします
- `{{h ... }}` はHTML内容用にエスケープされた出力をエコーします
- `{{a ... }}` はHTML属性用にエスケープされた出力をエコーします
- `{{u ... }}` はURL用にエスケープされた出力をエコーします
- `{{c ... }}` はCSS用にエスケープされた出力をエコーします
- `{{j ... }}` はJavaScript用にエスケープされた出力をエコーします

`{{a ... }}` タグは、キーを属性ラベルとして、値を属性値として使用する配列を出力する追加機能を提供します。複数の属性値はスペースで区切られます。以下のQiqコードは...


```qiq
<span {{a ['id' => 'foo', 'class' => ['bar', 'baz', 'dib']] }}>Text</span>
```

...は以下のようにレンダリングされます：

```html
<span id="foo" class="bar baz dib">Text</span>
```

ほぼすべての変数、リテラル、関数、メソッド、式、または定数（マジック定数 `__DIR__`、`__FILE__`、`__LINE__` を含む）を出力できます。

```qiq
{{h $foo }}
{{h "foo" }}
{{h 1 + 2 }}
{{h __FILE__ }}
{{h PHP_EOL }}
{{h $person->firstName() }}
{{h time() }}
```

二重の中括弧を文字通りに埋め込み、Qiqタグとして解釈されないようにするには、中括弧の間にバックスラッシュを置きます。以下のQiqコードは...


```qiq
{{ /* this is qiq code */ }}

{\{ this is not qiq code }\}
```

...は以下のPHPコードにコンパイルされます：

```html+php
<?php /* this is qiq code */ ?>

{{ this is not qiq code }}
```

## 制御構造

すべての制御構造は、利用可能な場合は[制御構造に関する別の構文](https://php.net/control-structures.alternative-syntax)を使用して、`{{ ... }}` Qiqタグ内でPHPと全く同じように記述されます。


例えば、このQiqコードは...

```qiq
{{ foreach ($foo as $bar => $baz): }}
    {{ if ($baz === 0): }}
        {{= "First element!" }}
    {{ else: }}
        {{= "Not the first element." }}
    {{ endif }}
{{ endforeach }}
```
...は以下のPHPコードと同じです：

```html+php
<?php foreach ($foo as $bar => $baz): ?>
    <?php if ($bar === 0): ?>
        <?= "First element!" ?><?= PHP_EOL ?>
    <?php else: ?>
        <?= "Not the first element." ?><?= PHP_EOL ?>
    <?php endif ?>
<?php endforeach ?>
```

## ヘルパー

Qiqタグ内の、PHPが潜在的な関数呼び出しとして認識するコードは、Qiqテンプレートヘルパーメソッドとして扱われます。したがって、以下のQiq構文は...


```qiq
{{= label ("Street Address", for: 'street') }}
{{= textField (
    name: 'street',
    value: $street,
) }}
```

...は、Qiqヘルパーを使用した以下のPHPコードと同等です：

```php
<?= $this->label("Street Address", for: 'street') ?>
<?= $this->textField(
    name: 'street',
    value: $street,
) ?>
```

Qiqコード内では、`$this->` プレフィックスの有無にかかわらず、どこでもヘルパーメソッド名を使用できます。以下の例では全て、`anchor()` ヘルパーの結果が変数に割り当てられています：


```qiq
{{ $a_com = anchor ('http://example.com') }}
{{ $a_net = $this->anchor ('http://example.net') }}
<?php $a_org = $this->anchor('http://example.org') ?>
```

## 未定義のヘルパー

ヘルパーメソッド名が定義されていない場合、_Helpers_ クラスは代わりにPHP関数として呼び出します。例えば、`time` ヘルパーメソッドが定義されていない場合、以下は [`time`](https://php.net/time) PHP関数を呼び出します：


```qiq
{{h time () }}
```

ただし、これは[静的解析](./static-analysis.md)チェックを通過しない可能性があります。静的解析の結果を改善するには、呼び出しの前にバックスラッシュを付けてPHP関数を明示的に示します：


```qiq
{{h \time () }}
```

あるいは、PHP関数をオーバーライドする[カスタムヘルパーメソッド](./helpers/custom.md)を作成することもできます。

## その他のPHPコード

Qiqは `{{ ... }}` タグ内の他のすべてのコードを通常のPHPコードとして扱います。例えば、このQiq構文は...

```qiq
{{ $title = "Prefix: " . $title . " (Suffix)" }}
<title>{{h $title}}</title>
```

..は、Qiqヘルパーを使用した以下のPHPコードと同等です：

```html+php
<?php $title = "Prefix: " . $title . " (Suffix)" ?>
<title><?= $this->h($title) ?></title>
```

## 空白文字

Qiqは出力の空白文字を制御し、コンパイルされたテンプレートコードをソーステンプレートと同じ行に保ち、出力が適切にフォーマットされるようにするために、いくつかの工夫をしています。

### 改行

Qiqはタグの周りの改行を直感的に処理します：

- エコーしないQiqタグは、通常のPHPと同様に、閉じタグの直後の単一の改行を **消費** します。

- エコーするQiqタグは、生であれエスケープされたものであれ、閉じタグの直後の単一の改行を **尊重** します。

例えば、このQiqコードは...

```qiq
{{ if ($condition): }}
{{= "foo" }}
{{ endif; }}
```

...は以下のPHPコードにコンパイルされます：

```html+php
<?php if ($condition): ?>
<?= "foo" ?><?= PHP_EOL ?>
<?php endif ?>
```

エコーしないQiqは、開始タグにチルダを使用することで、単一の **先頭の** 改行をエコーするようにできます。このQiqコードは...


```qiq
{{~ foreach ($foo as $bar): }}
...
{{~ endforeach }}
```

...は以下のPHPコードにコンパイルされます：

```html+php
<?= PHP_EOL ?><?php foreach ($foo as $bar): ?>
...
<?= PHP_EOL ?><?php endforeach ?>
```

これは特にループ出力コードで有用で、ループの最初と最後の改行を尊重したい場合に使用します。

エコーするQiqは、閉じタグにチルダを使用することで、単一の後続の改行を消費するようにできます。このQiqコードは...


```qiq
{{h $foo ~}}
```

...は以下のPHPコードにコンパイルされます：

```html+php
<?= $this->h($foo) ?>
```

閉じタグのチルダは、エコーしないQiqコードには影響しません。

### インデント

エコーするQiqタグは、開始Qiqタグの前の先頭の空白に基づいて、ヘルパーの現在のインデントを自動的に設定します。

このQiqコードは...

```qiq
<ul>
    {{= items (['foo', 'bar', 'baz']) }}
</ul>
```

...は以下のPHPコードにコンパイルされます：

```qiq
<ul>
    <?php $this->setIndent("    ") ?><?= $this->items(['foo', 'bar', 'baz']) ?>
</ul>
```
