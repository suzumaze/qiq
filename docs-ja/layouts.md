# レイアウト

メインの"inner"ビュー・テンプレートを"outer"レイアウト・テンプレートでラップするには、`setLayout()`を呼び出して2番目のステップのための2番目の"outer"テンプレートを選択します。(レイアウトが設定されていない場合、2番目のステップは実行されません)。

例えば、`browse`というビューテンプレートが既にあるとします。そして、`default`というレイアウトテンプレートを用意して、ビューをラップするとします。`default.php`レイアウトテンプレートは以下のようなものになります。

```html+php
<html>
<head>
    <title>My Site</title>
</head>
<body>
{{= getContent() }}
</body>
</html>
```

そして、Templateオブジェクトにビューテンプレートとレイアウトテンプレートを設定し、呼び出すことができます。

```php
$tpl->setView('browse');
$tpl->setLayout('default');
$output = $tpl();
```

内側のビューテンプレートからの出力は自動的に保持され、Templateオブジェクトの`getContent()`メソッドで利用できるようになります。そして、レイアウトテンプレートは`getContent()`を呼び出して、内側のビューの結果を外側のレイアウトテンプレートに配置します。

> **注:**
>
> または、ビューテンプレートの内部から`setLayout()`を呼び出すことで、ビューロジックの一部としてレイアウトを選択することができます。

ビューテンプレートとレイアウトテンプレートは、同じTemplateオブジェクトの中で実行されます。これはつまり

- すべてのデータ値は、ビューとレイアウトの間で共有されます。ビューに割り当てられたデータ、またはビューによって変更されたデータは、そのままレイアウトで使用されます。

- すべてのヘルパーは、ビューとレイアウトの間で共有されます。この共有状況により、レイアウトが実行される前に、ビューはデータとヘルパーを修正することができます。

- すべてのセクションボディは、ビューとレイアウトの間で共有されます。したがって、ビューテンプレートからキャプチャされたセクションは、レイアウトテンプレートで使用することができます。
