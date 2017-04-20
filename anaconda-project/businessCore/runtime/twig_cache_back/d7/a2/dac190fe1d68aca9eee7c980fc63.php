<?php

/* //ap/affiliatePlatform.html */
class __TwigTemplate_d7a2dac190fe1d68aca9eee7c980fc63 extends Twig_Template
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
        echo Yii::t("AP", "txtTitre1");
        echo "</h1>

";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

<div style=\"float:left; margin-left:20px;\">
\t";
        // line 7
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => "affiliatePlatformShow( \"/AP/affiliatePlatformShow/?partialRender=true\" )", "style" => "font-size:16px;")), "method");
        // line 10
        echo "
</div>

";
        // line 13
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewAP", "dataProvider" => $this->getAttribute((isset($context["AP"]) ? $context["AP"] : null), "search", array(0 => "label", 1 => 50), "method"), "filter" => (isset($context["AP"]) ? $context["AP"] : null), "columns" => array(0 => array("header" => "Action", "class" => "CButtonColumn", "template" => "{update}  {view}  {manager}   {delete}", "htmlOptions" => array("width" => "80px"), "buttons" => array("update" => array("label" => "Update", "url" => "Yii::App()->createUrl( \"/AP/affiliatePlatformShow\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return affiliatePlatformShow( \$(this) ); }"), "view" => array("label" => "Sub ID", "url" => "Yii::App()->createUrl( \"/AP/affiliatePlatformSubId\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return affiliatePlatformSubId( \$(this) ); }"), "manager" => array("label" => "Manager", "url" => "Yii::App()->createUrl( \"/AP/affiliatePlatformManager\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return affiliatePlatformManager( \$(this) ); }", "imageUrl" => "../../businessCore/views/images/archive.png"), "delete" => array("label" => "Delete", "url" => "Yii::App()->createUrl( \"/AP/affiliatePlatform\", array( \"id\" => \$data->primaryKey, \"delete\" => true ) )", "visible" => "Yii::App()->User->checkAccess(\"ADMIN\")"))), 1 => array("header" => "Label", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["AP"]) ? $context["AP"] : null), 1 => "label", 2 => array("placeholder" => "search on label")), "method"), "value" => "\$data->label"), 2 => array("header" => "Description", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["AP"]) ? $context["AP"] : null), 1 => "description", 2 => array("placeholder" => "search on description")), "method"), "value" => "\$data->description"), 3 => array("filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["AP"]) ? $context["AP"] : null), 1 => "codeSite", 2 => array("placeholder" => "search on site")), "method"), "name" => "Site", "value" => "\$data->Site->code"), 4 => array("header" => "Nb SubId", "value" => "count(\$data->AffiliatePlatformSubId)"), 5 => array("header" => "Manager Strategique", "value" => "( \$data->getActualManager( \\Business\\Manager::TYPE_STRATEGIQUE ) ) ? \$data->getActualManager( \\Business\\Manager::TYPE_STRATEGIQUE )->User->name() : NULL")))), "method");
    }

    public function getTemplateName()
    {
        return "//ap/affiliatePlatform.html";
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
