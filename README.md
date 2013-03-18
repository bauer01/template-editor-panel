#TemplateEditorPanel

This panel provides lightweight template editor for your applications based on Nette Framework.

##Install

The best way to install is using [Composer](http://getcomposer.org/):

```sh
$ composer require bauer01/TemplateEditorPanel
```

Then register panel in your `config.neon`:
```neon
nette:
	debugger:
		bar:
			- TemplateEditor\Panel
```