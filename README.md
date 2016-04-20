# Yii2 jqPlot widget

This extension provides [jqPlot](http://www.jqplot.com) integration for the [Yii framework 2.0](http://www.yiiframework.com).

jqPlot is a plotting and charting plugin for the jQuery Javascript framework. jqPlot produces beautiful line, bar and pie charts with many features:

* Numerous chart style options.
* Date axes with customizable formatting.
* Up to 9 Y axes.
* Rotated axis text.
* Automatic trend line computation.
* Tooltips and data point highlighting.
* Sensible defaults for ease of use.

> Computation and drawing of lines, axes, shadows even the grid itself is handled by pluggable "renderers". Not only are the plot elements customizable, plugins can expand functionality of the plot too! There are plenty of hooks into the core jqPlot code allowing for custom event handlers, creation of new plot types, adding canvases to the plot, and more!

![Numerous line style options with 6 built in marker styles!](http://www.jqplot.com/images/linestyles2.jpg)
![Horizontal and vertical Bar charts!](http://www.jqplot.com/images/barchart.jpg)
![Shadow control on lines, markers, the grid, everything!](http://www.jqplot.com/images/shadow2.jpg)

![Drag and drop points with auto updating of data!](http://www.jqplot.com/images/dragdrop2.jpg)
![Log Axes with flexible tick marks!](http://www.jqplot.com/images/logaxes2.jpg)
![Trend lines computed automatically!](http://www.jqplot.com/images/trendline2.jpg)


## Installation

Package is available on [Packagist](https://packagist.org/packages/sizeg/yii2-jqplot),
you can install it using [Composer](http://getcomposer.org).

```shell
composer require sizeg/yii2-jqplot
```

## Basic usage

```php
echo JqPlot::widget([
    'data' => [[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]
]);
```

The following example will render a bar chart:

```php
echo JqPlot::widget([
    'data' => [
        [2, 6, 7, 10],
        [7, 5, 3, 2],
        [14, 9, 3, 8],
    ],
    'clientOptions' => [
        'stackSeries' => true,
        'captureRightClick' => true,
        'seriesDefaults'  => [
            'renderer' => new JsExpression("$.jqplot.BarRenderer"),
            'rendererOptions' => [
                'highlightMouseDown' => true,
            ],
            'pointLabels' => [
                'show' => true,
            ],
        ],
        'legend' => [
            'show' => true,
            'location' => 'e',
            'placement' => 'outside',
        ]
    ]
]);
```
