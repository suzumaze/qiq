# アップグレード

## 1.x から 2.x へ

Qiq 1.x から 2.x へのアップグレードは簡単ですが、時間がかかる場合があります。

### Assigned Variables

割り当て変数へのマジック `__get()`、`__set()` などのアクセスが削除されました。

これは、テンプレートファイルが割り当て変数に対して **もはや `$this->var` を使用しない** ことを意味します。

代わりに、`$var` （`$this->` プレフィックスなし）を使用します。これはテンプレートファイルでの[静的解析](./static-analysis.md)をサポートするためです。

割り当て変数を直接変更する必要がある場合は、`&refData()` を使用して割り当てられたデータの配列への参照を取得します。この配列への変更は、次の `render()` 呼び出し時に反映されます。

### セクションとブロック

セクションは完全に削除され、ブロックに置き換えられました。`setSection()`、`preSection()`、`addSection()`、`getSection()` の代わりに、`setBlock()`、`parentBlock()`、`getBlock()` を使用します。詳細については[ブロック](./blocks.md)のドキュメントを参照してください。

### ヘルパー

_HelperLocator_ は完全に削除され、_Helpers_ と _Container_ に置き換えられました。カスタムヘルパーがある場合は、[カスタムヘルパー](./helpers/custom.md)のドキュメントに従って、テンプレートで使用できるようにする必要があります。

タグ関連のヘルパーは、属性に配列を使用しなくなりました。代わりに、名前付きパラメータを使用します。例えば、1.x でのテキストフィールドヘルパーの呼び出しは次のようでした：

```qiq
{{= textField ([
  'name' => 'foo',
  'value' => 'foo text',
  'id' => 'foo-id',
]) }}
```

2.x では、次のように呼び出します。配列とそのキーが名前付きパラメータに置き換えられていることに注意してください：

```qiq
{{= textField (
  name: 'foo',
  value: 'foo text',
  id: 'foo-id',
) }}
```

移行の補助として、スプレッド（`...`）演算子を使用して配列を名前付きパラメータに展開することができます：

```qiq
{{= textField (...[
  'name' => 'foo',
  'value' => 'foo text',
  'id' => 'foo-id',
]) }}
```

さらに：

- すべてのHTMLヘルパーが _Qiq\Helper_ 名前空間から _Qiq\Helper\Html_ 名前空間に移動されました。_Helper_ クラスは _TagHelper_ に名前が変更されました。

- _Escape_ クラスは現在 _Qiq_ 名前空間ではなく、_Qiq\Helper\Html_ 名前空間で定義されています。

- 属性ビルダーは `_raw` 疑似属性を尊重しなくなりました。タグ本文のテキストをエスケープしたくない場合は、手動で構築する必要があります。

- 様々なフォーム関連ヘルパーの `_default` 疑似属性は、名前付きパラメータ `default` に置き換えられました。

- 様々なフォーム関連ヘルパーの `_options` 疑似属性は、名前付きパラメータ `options` に置き換えられました。


### 静的解析

すべてのQiqコードは現在 `strict_types=1` を宣言し、静的解析の対象となっています。通常、これはQiqに送信するコードをより厳密にする必要があることを意味します。

しかし、ほとんどの文字列のようなパラメータは `mixed` として型付けされ、`@param` docblockでタイプを `null|scalar|Stringable`（またはその配列）として示しています。これは、`mixed` 値を `string` にキャストすることが、文字列出力用にエスケープされる際に静的解析ツールによってフラグが立てられるためです。

結果として、Qiqに送信する値を再キャストする必要はほとんどないはずですが、これらの変更には注意してください。

### その他の変更

- _Engine_ インターフェースが導入されました。

- _TemplateCore_ は _Kernel_ に名前が変更され、_Engine_ インターフェースを実装しています。

- `Template::new()` は `Kernel::new()` に移動されました。

- _TemplateLocator_ は _Catalog_ に名前が変更されました。`TemplateLocator::get()` は現在 `Catalog::getCompiled()` です。

- _Compiler_ インターフェースは現在 _Qiq\Compiler_ 名前空間ではなく、_Qiq_ 名前空間で定義されています。

- _Exception_ クラスは現在 _Qiq\Exception_ 名前空間ではなく、_Qiq_ 名前空間で定義されています。

- _HelperNotFound_ 例外は _ObjectNotFound_ に名前が変更され、PSR-11 _NotFoundExceptionInterface_ を実装しています。

- _TemplateNotFound_ 例外は _FileNotFound_ に名前が変更されました。

- _Indent_ クラスは現在、静的メソッドではなくインスタンスメソッドを使用し、_Container_ 内でインスタンスとして共有されています。