<?php

/* //ap/promoSite.html */
class __TwigTemplate_55c1a004bc275b6238d2a422bd9fd28f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "
<h1 id=\"apTitre\">";
        // line 2
        echo Yii::t("AP", "txtTitre2");
        echo "</h1>

";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

<div style=\"float:left; margin-left:20px;\">
\t";
        // line 7
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => "promoSiteShow( \"/AP/PromoSiteShow/?partialRender=true\" )", "style" => "font-size:16px;")), "method");
        // line 10
        echo "
</div>

";
        // line 13
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewPS", "dataProvider" => $this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "search", array(0 => false, 1 => 50), "method"), "filter" => (isset($context["PS"]) ? $context["PS"] : null), "columns" => array(0 => array("header" => "Action", "class" => "CButtonColumn", "template" => "{update}    {go}    {delete}", "buttons" => array("update" => array("label" => "Update", "url" => "Yii::App()->createUrl( \"/AP/PromoSiteShow\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return promoSiteShow( \$(this) ); }"), "delete" => array("label" => "Delete", "url" => "Yii::App()->createUrl( \"/AP/promoSite\", array( \"id\" => \$data->primaryKey, \"delete\" => true ) )", "visible" => "Yii::App()->User->checkAccess(\"ADMIN\")"), "go" => array("label" => "Go to Promo Site", "url" => "\$data->getUrl()", "imageUrl" => "../../businessCore/views/images/allerA.png", "click" => "function() { window.open( this ); return false; }"))), 1 => array("header" => "Label", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["PS"]) ? $context["PS"] : null), 1 => "label", 2 => array("placeholder" => "search on label")), "method"), "value" => "\$data->label"), 2 => array("header" => "Description", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["PS"]) ? $context["PS"] : null), 1 => "description", 2 => array("placeholder" => "search on description")), "method"), "value" => "\$data->description")))), "method");
    }

    public function getTemplateName()
    {
        return "//ap/promoSite.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  40 => 13,  35 => 10,  33 => 7,  27 => 4,  22 => 2,  19 => 1,);
    }
}
