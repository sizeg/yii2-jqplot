<?php

namespace sizeg\jqplot;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\jui\Widget;
use yii\web\View;

/**
 * JqPlot widget renders charts and graphs for jQuery.
 *
 * For example:
 *
 * ```php
 * echo JqPlot::widget([
 *     'data' => [[1, 2],[3,5.12],[5,13.1],[7,33.6],[9,85.9],[11,219.9]]
 * ]);
 * ```
 * 
 * The following example will render a bar chart:
 * 
 * ```php
 * echo sizeg\jqplot\JqPlot::widget([
 *     'data' => [
 *         [2, 6, 7, 10],
 *         [7, 5, 3, 2],
 *         [14, 9, 3, 8],
 *     ],
 *     'clientOptions' => [
 *         'stackSeries' => true,
 *         'captureRightClick' => true,
 *         'seriesDefaults'  => [
 *             'renderer' => new \yii\web\JsExpression("$.jqplot.BarRenderer"),
 *             'rendererOptions' => [
 *                 'highlightMouseDown' => true,
 *             ],
 *             'pointLabels' => [
 *                 'show' => true,
 *             ],
 *         ],
 *         'legend' => [
 *             'show' => true,
 *             'location' => 'e',
 *             'placement' => 'outside',
 *         ]
 *     ]
 * ]);
 * ```
 * 
 * @see http://www.jqplot.com/
 * @author Dmitry Demin <sizemail@gmail.com>
 * @since 1.0.0-a
 */
class JqPlot extends Widget
{

    /**
     * Pluging name
     */
    const NAME = 'jqplot';

    /**
     * @var array graph data
     */
    public $data = [];

    /**
     * Initializes the widget
     */
    public function init()
    {
        parent::init();
        echo Html::beginTag('div', $this->options) . "\n";
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::endTag('div') . "\n";
        $this->registerJqPlotWidget();
    }

    /**
     * Registers a specific jqPlot jQuery widget asset bundle, initializes it with client options and registers related events
     * @param string $id the ID of the widget. If null, it will use the `id` value of [[options]].
     */
    protected function registerJqPlotWidget()
    {
        JqPlotAsset::register($this->getView());
        $this->registerClientEvents(static::NAME, $this->options['id']);
        $this->registerJqPlotClientOptions($this->options['id']);
    }

    /**
     * Registers a specific jQuery jqPlot widget options
     * @param string $id the ID of the widget
     */
    protected function registerJqPlotClientOptions($id)
    {
        $this->registerRenderersRecursively($this->clientOptions);

        $data = Json::htmlEncode($this->data);
        $options = !empty($this->clientOptions) ? Json::htmlEncode($this->clientOptions) : '{}';
        $js = "jQuery('#" . $id . "')." . static::NAME . "(" . $data . ", " . $options . ")";
        $this->getView()->registerJs($js, View::POS_END);
    }
    
    /**
     * Find renderers and register their JS plugins
     * @param array $data
     */
    public function registerRenderersRecursively($data)
    {
        // Looking for renderers
        foreach ($data as $k => $v) {
            if ($k == 'renderer' || $k == 'tickRenderer') {
                $this->registerRendererJsFile($v);
            } elseif (is_array($v)) {
                $this->registerRenderersRecursively($v);
            }
        }

    }

    /**
     * Registers additional jqPlot JS plugins
     * @param string $renderer plugin jQuery name
     */
    public function registerRendererJsFile($renderer)
    {
        if (strpos($renderer, 'BezierCurveRenderer') !== false) {
            $url = 'plugins/jqplot.BezierCurveRenderer.js';
        } else {
            list($jqPrefix, $jqPlot, $name) = explode('.', $renderer);
            $url = 'plugins/jqplot.' . lcfirst($name) . '.js';
        }
        
        // Additional dependencies
        if ($name == 'CanvasAxisLabelRenderer' || $name == 'CanvasAxisTickRenderer') {
            $additionalUrl = 'plugins/jqplot.canvasTextRenderer.js';
            if (!in_array($additionalUrl, Yii::$app->assetManager->bundles[JqPlotAsset::className()]->js)) {
                Yii::$app->assetManager->bundles[JqPlotAsset::className()]->js[] = $additionalUrl;
            }
        }
        
        if (!in_array($url, Yii::$app->assetManager->bundles[JqPlotAsset::className()]->js)) {
            Yii::$app->assetManager->bundles[JqPlotAsset::className()]->js[] = $url;
        }

        Yii::$app->assetManager->bundles[JqPlotAsset::className()]->js[] = 'plugins/jqplot.pointLabels.js';
    }
}
