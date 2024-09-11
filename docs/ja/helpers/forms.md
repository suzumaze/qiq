# フォームヘルパー

すべてのヘルパーは適切なエスケープを自動的に適用します。つまり、`{{= ... }}` を使用して出力できます。`{{h ... }}` などを使用すると、出力が二重にエスケープされてしまいます。

また、PHPテンプレートコードでは、`$this` のメソッドとしてヘルパーを呼び出すこともできます。

最後に、これらのヘルパーの多くは、HTML タグの属性として名前付きパラメータの可変長リストを受け取ります。つまり、ヘルパーメソッドのパラメータであるかのように、ほぼすべての属性を追加できます。パラメータ名のアンダースコアはダッシュに変換されます。例えば、foo_bar: 'baz' は、ヘルパーの出力で foo-bar="baz" になります。名前付きパラメータとして使用できない属性については、`attr` 配列パラメータを使用してください。

## Form Tag

フォームを開くには次のようにします:

```qiq
{{= form (
    action: '/hello',
    attr: [],               // (array) オプションのキー・バリュー属性
    id: 'form-id'           // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<!-- デフォルトは method="post" action="" enctype="multipart/form-data" -->
<form method="post" action="/hello" enctype="multipart/form-data" id="form-id">
```
フォームを閉じるには単に `</form>` を使用します。

## Input Tags

### checkboxField

```qiq
{{= checkboxField (
    name: 'flag',
    value: 'foo',
    checked: true,
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="checkbox" name="flag" value="foo" checked />
```

### checkboxFields

`checkboxFields` ヘルパーは、一度に1つ以上のチェックボックスに使用でき、`checkboxField` ヘルパーよりも機能が豊富です：

- `options` 配列は、フィールドの一部として1つ以上のチェックボックスを指定し、チェック時の各値と対応するラベルを定義します。
- `options` に複数の要素がある場合、フィールド名の末尾に自動的に `[]`が追加され、配列になります。
- `value` 属性は `options` と照合され、正しいチェックボックスが自動的に `checked` になります。
- `default` パラメータがnull以外の場合、チェックボックスがチェックされていない時にその値の隠しフィールドが生成されます。

```qiq
{{= checkboxFields (
    name: 'flags',
    value: 'bar',
    default: '',
    options: [
        'foo' => 'Foo Flag',
        'bar' => 'Bar Flag',
        'baz' => 'Baz Flag',
    ],
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="hidden" name="flags" value="" />
<label><input type="checkbox" name="flags[]" value="foo" /> Foo Flag</label>
<label><input type="checkbox" name="flags[]" value="bar" checked /> Bar Flag</label>
<label><input type="checkbox" name="flags[]" value="baz" /> Baz Flag</label>
```

### colorField

```qiq
{{= colorField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="color" name="foo" value="bar" />
```

### dateField

```qiq
{{= dateField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="date" name="foo" value="bar" />
```

### datetimeField

```qiq
{{= datetimeField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="datetime" name="foo" value="bar" />
```

### datetimeLocalField

```qiq
{{= datetimeLocalField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="datetime-local" name="foo" value="bar" />
```

### emailField

```qiq
{{= emailField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="email" name="foo" value="bar" />
```

### fileField

```qiq
{{= fileField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="file" name="foo" value="bar" />
```

### hiddenField

```qiq
{{= hiddenField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="hidden" name="foo" value="bar" />
```

### inputField

必要な `type` を指定する。

```qiq
{{= inputField (
    type: 'text',
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="text" name="foo" value="bar" />
```

### monthField

```qiq
{{= monthField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="month" name="foo" value="bar" />
```

### numberField

```qiq
{{= numberField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="number" name="foo" value="bar" />
```

### passwordField

```qiq
{{= passwordField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="password" name="foo" value="bar" />
```

### radioField

```qiq
{{= radioField (
    name: 'foo',
    value: 'baz',
    checked: true,
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="radio" name="foo" value="baz" checked />
```

### radioFields


`radioFields` ヘルパーは `radioField` ヘルパーよりも機能が豊富です：

- `options` パラメータは、フィールドの一部として1つ以上のラジオボタンを指定し、チェック時の値と対応するラベルを定義します。
- `value` パラメータは `options` と照合され、正しいラジオボタンが自動的に `checked` になります。

```qiq
{{= radioFields (
    name: 'foo',
    value: 'baz',
    options: [
        'bar' => 'Bar Label',
        'baz' => 'Baz Label,
        'dib' => 'Dib Label',
    ),
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<label><input type="radio" name="foo" value="bar" /> Bar Label</label>
<label><input type="radio" name="foo" value="baz" checked /> Baz Label</label>
<label><input type="radio" name="foo" value="dib" /> Dib Label</label>
```

### rangeField

```qiq
{{= rangeField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="range" name="foo" value="bar" />
```

### searchField

```qiq
{{= searchField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="search" name="foo" value="bar" />
```

### select

`options` パラメータを使用して `<option>` タグを記述します。
`placeholder` パラメータは、オプションが選択されていない場合のプレースホルダーラベルとして扱われます。`default` パラメータがnull以外の場合、そのプレースホルダーの値を指定します。
複数選択を設定するには `multiple: true` を使用します。これにより、名前にまだ `[]` が付いていない場合は自動的に追加されます。

```qiq
{{= select (
    name: 'foo',
    value: 'dib',
    placeholder: 'Please pick one',
    default: '',
    options: [
        'bar' => 'Bar Label',
        'baz' => 'Baz Label',
        'dib' => 'Dib Label',
        'zim' => 'Zim Label',
    ],
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<select name="foo">
    <option value="" disabled>Please pick one</option>
    <option value="bar">Bar Label</option>
    <option value="baz">Baz Label</option>
    <option value="dib" selected>Dib Label</option>
    <option value="zim">Zim Label</option>
</select>
```

このヘルパーはオプショングループもサポートしています。`options` 配列の値自体が配列の場合、その要素のキーが `<optgroup>` のラベルとして使用され、値の配列がそのグループ内のオプションになります。

```qiq
{{= select (
    name: 'foo',
    value: 'bar',
    options: => [
        'Group A' => [
            'bar' => 'Bar Label',
            'baz' => 'Baz Label',
        ],
        'Group B' => [
            'dib' => 'Dib Label',
            'zim' => 'Zim Label',
        ],
    ],
) }}
```

```html
<select name="foo">
    <optgroup label="Group A">
        <option value="bar">Bar Label</option>
        <option value="baz">Baz Label</option>
    </optgroup>
    <optgroup label="Group B">
        <option value="dib" selected>Dib Label</option>
        <option value="zim">Zim Label</option>
    </optgroup>
</select>
```

### telField

```qiq
{{= telField(
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="tel" name="foo" value="bar" />
```

### textField

```qiq
{{= textField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="text" name="foo" value="bar" />
```

### textarea

```qiq
{{= textarea (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<textarea name="foo">bar</textarea>
```

### timeField

```qiq
{{= timeField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="time" name="foo" value="bar" />
```

### urlField

```qiq
{{= urlField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="url" name="foo" value="bar" />
```

### weekField

```qiq
{{= weekField (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="week" name="foo" value="bar" />
```

## Button Tags

各種ボタンタグのヘルパーです。

### button

```qiq
{{= button (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="button" name="foo" value="bar" />
```

### imageButton

```qiq
{{= imageButton (
    name: 'foo',
    src: '/images/map.png',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="image" name="foo" src="/images/map.png" />
```

### submitButton

```qiq
{{= submitButton (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="submit" name="foo" value="bar" />
```

### resetButton

```qiq
{{= resetButton (
    name: 'foo',
    value: 'bar',
    attr: [],               // (array) オプションのキー・バリュー属性:
    ...                     // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<input type="reset" name="foo" value="bar" />
```

## Label Tag

`<label>` タグのためのヘルパー。

```qiq
{{= label (
    'Label For Field',      // (string) label text
    attr: [],               // (array) オプションのキー・バリュー属性:
    for: 'field'            // (...mixed) オプションの名前付きパラメータ属性
) }}
```

```html
<label for="field">Label For Field</label>
```
