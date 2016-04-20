<?php

namespace sizeg\jqplot;

use Yii;
use yii\web\AssetBundle;

Yii::setAlias('@jqplot', dirname(__FILE__));

/**
 * @author Dmitry Demin <sizemail@gmail.com>
 * @since 1.0.0-a
 */
class JqPlotAsset extends AssetBundle
{
    /** @var string */
    public $sourcePath = '@jqplot/assets';
    
    /** @var array */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $sufix = YII_DEBUG ? '.min' : '';
        
        $this->js[] = 'jquery.jqplot'.$sufix.'.js';
        // <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="excanvas.js"></script><![endif]-->
        $this->css[] = 'jquery.jqplot'.$sufix.'.css';
        
        parent::init();
    }
}
