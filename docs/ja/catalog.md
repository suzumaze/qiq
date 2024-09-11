# テンプレートファイルカタログ

Qiqは複数のディレクトリパスからテンプレートファイルを検索します。`Template::new()` に `paths` の配列を渡すことができます...

```php
$template = Template::new(
    paths: [
        '/path/to/custom/templates',
        '/path/to/default/templates',
    ],
);
```

または、_Catalog_ に直接指示することもできます：

```php
$template->getCatalog()->setPaths([
    '/path/to/custom/templates',
    '/path/to/default/templates',
]);
```

_Catalog_ は最初のディレクトリパスから最後のディレクトリパスまでテンプレートファイルを検索します。

```php
/*
まず最初に:  /path/to/custom/templates/foo.php を検索し、
次に: /path/to/default/templates/foo.php を検索します
*/
$output = $template('foo');
```

必要に応じて、_Template_ のインスタンス化後にパスを変更して、_Catalog_ にディレクトリパスを前置または後置することができます：

```php
$template->getCatalog()->prependPath('/higher/precedence/templates');
$template->getCatalog()->appendPath('/lower/precedence/templates');
```

### サブディレクトリ

任意の場所からテンプレートファイルをレンダリングするには、テンプレート名への絶対パスを使用します（先頭のスラッシュは不要です）：

```php
// "foo/bar/baz.php" テンプレートをレンダリングします
$output = $template('foo/bar/baz');
```

あるいは、テンプレートファイル内から、相対パスで他のテンプレートファイルを参照することもできます。`./` は同じディレクトリにあるテンプレートファイルを、`../` は現在のディレクトリの上のディレクトリにあるファイルを示します。

次のようなテンプレートファイル構造があるとします...

```
foo.php
foo/
    bar.php
    bar/
        baz.php
        dib.php
```

... `foo/bar/baz.php` テンプレートファイル内では：

```php
// "foo/bar/dib.php" を参照します
echo $this->render('./dib');

// "foo/bar.php" を参照します
echo $this->render('../bar');

// "foo.php" を参照します
echo $this->render('../../foo');
```

### ファイル名の拡張子

デフォルトでは、_Catalog_ はテンプレートファイル名に `.php` を自動的に追加します。テンプレートファイルが異なる拡張子で終わる場合は、`setExtension()` メソッドを使用して変更します：

```php
$catalog = $template->getCatalog();
$catalog->setExtension('.phtml');
```

または、_Template_ 作成時に拡張子を設定することもできます：

```php
$template = Template::new(
    extension: '.qiq.php'
);
```

### コレクション

メールや管理ページなど、テンプレートのコレクションを識別することが有用な場合があります（他のテンプレートシステムではこれらを "グループ"、"フォルダ"、または "名前空間" と呼ぶことがあります）。
ディレクトリパスをコレクションに関連付けるには、パスの前にコレクション名とコロンを付けます：

```php
$template = Template::new(
    paths: [
        'admin:/path/to/admin/templates',
        'email:/path/to/email/templates',
    ]
);
```

コレクションからテンプレートをレンダリングするには、テンプレート名の前にコレクション名を付けます。

```php
$output = $template('email:notify/subscribed');
```

"main" または "default" のプレフィックスなしテンプレートパスのコレクションと同様に、コレクションパスを設定、追加、前置することができます。