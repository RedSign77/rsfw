<?php

/**
 * Class DefaultMain
 */
class Default_Main extends View
{

    private $blockHeader = "header";
    private $blockFooter = "footer";

    public function __construct()
    {
        $this->addJs('js/default.js');
        $this->addCss('css/style.css');
    }

    public function addJs($jsPath)
    {
        $this->javascripts[] = $jsPath;
    }

    public function addCss($cssPath)
    {
        $this->css[] = $cssPath;
    }

    public function _beforeShow()
    {
        $this->addBlock($this->blockHeader, array(
            'site_author' => $this->author,
            'site_keywords' => $this->keywords,
            'site_description' => $this->description,
            'javascripts' => $this->getJavascripts(),
            'stylesheets' => $this->getCSS(),
        ));
    }

    public function _afterShow()
    {
        $this->addBlock($this->blockFooter);
    }


} 