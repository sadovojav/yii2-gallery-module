# Yii2 gallery

#### Features:
- Upload image
- Drag image and change position
- Make gallery template
- Use inline widget

![gallery](https://cloud.githubusercontent.com/assets/9282021/10119704/7a006796-64a5-11e5-8b3c-51046cb05f7b.jpg)

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run ```php composer.phar require sadovojav/yii2-gallery-module ""dev-master"```

or add ```"sadovojav/yii2-gallery-module": ""dev-master"``` to the require section of your ```composer.json```

### Migration

yii migrate --migrationPath=@vendor/sadovojav/yii2-gallery-module/migrations

## Config

1. Attach the module in your config file:

```php
'modules' => [
    'gallery' => [
        'class' => 'sadovojav\gallery\Module',
    ],
],
```
- string `basePath` = `@webroot/galleries` - Base path
- integer `queryCacheDuration` = `86400` - Query cache duration
- bool `uniqueName` = `false` - Generate unique name

2. If you want use custom template, you can set path map

```php
'view' => [
	'theme' => [
		'pathMap' => [
			'@sadovojav/gallery/widgets/views' => '@app/web/views/gallery'
		],
		'baseUrl'   => '@web/web'
	],
],
```

## Administration

Galleries manager - **/gallery/gallery/index**

## Using

#### 1. Widget

~~~
<?= \sadovojav\gallery\widgets\Gallery::widget([
    'galleryId' => $model->galleryId
]); ?>
~~~

- integer `galleryId` required - Gallery Id
- bool `caption` = `false` - Show caption in default template
- string `template` = `null` - Your custom widget template


#### 2. Inline Widget

See here -> [https://github.com/sadovojav/yii2-inline-widgets-behavior](https://github.com/sadovojav/yii2-inline-widgets-behavior)