<?php
/**
 * Author: dungang
 * Date: 2017/4/9
 * Time: 14:53
 */

namespace dungang\barbajs;


use yii\base\Widget;
use yii\helpers\Html;
use yii\web\Response;

class BarbaWidget extends Widget
{
    public $barbaId = 'barba-wrapper';

    public $namespace = 'detail';

    public $prefetch = false;

    public $options = [];

    public function init() {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        if ($this->requiresBarba()) {
            ob_start();
            ob_implicit_flush(false);
            $view = $this->getView();
            $view->clear();
            $view->beginPage();
            $view->head();
            $view->beginBody();
            if ($view->title !== null) {
                echo Html::tag('title', Html::encode($view->title));
            }
        } else {
        }
        $this->options['class'] = isset($this->options['class'])
            ? 'barba-container ' . $this->options['class']
            : 'barba-container';
        $this->options['data-namespace'] = $this->namespace;
        echo '<div id="'.$this->barbaId.'">'.Html::beginTag('div', $this->options);
    }
    /**
     * @inheritdoc
     */
    public function run() {

        echo '</div></div>';
        $this->registerClientScript();

        if ($this->requiresBarba()) {
            $view = $this->getView();
            $view->endBody();

            // Do not re-send css files as it may override the css files that were loaded after them.
            // This is a temporary fix for https://github.com/yiisoft/yii2/issues/2310
            // It should be removed once pjax supports loading only missing css files
            $view->cssFiles = null;

            $view->endPage(true);

            $content = ob_get_clean();

            // only need the content enclosed within this widget
            $response = \Yii::$app->getResponse();
            $response->clearOutputBuffers();
            $response->setStatusCode(200);
            $response->format = Response::FORMAT_HTML;
            $response->content = $content;
            $response->send();

            \Yii::$app->end();
        }
    }

    protected function registerClientScript() {
        $view = $this->getView();
        BarbaAsset::register($view);
        $view->registerJs("Barba.Pjax.start();");
        if($this->prefetch) $view->registerJs("Barba.Prefetch.init();");
    }

    protected function requiresBarba()
    {
        $headers = \Yii::$app->getRequest()->getHeaders();
        return $headers->get('x-barba');
    }
}