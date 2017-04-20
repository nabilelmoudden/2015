<?php

/* //product/campaign.html */
class __TwigTemplate_cfbc6861c98380181c0b6ffa0a5d2558 extends Twig_Template
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
        echo Yii::t("product", "txtCampaign");
        echo "</h1>

";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

<div style=\"float:left; margin-left:20px;\">
\t";
        // line 7
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => "MainDialog.show( \"/Product/campaignShow\" )", "style" => "font-size:16px;")), "method");
        // line 10
        echo "
</div>

";
        // line 13
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewCampaign", "dataProvider" => $this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "search", array(0 => "label", 1 => 50), "method"), "filter" => (isset($context["Camp"]) ? $context["Camp"] : null), "columns" => array(0 => array("header" => "Action", "class" => "CButtonColumn", "template" => "{update}   {view}  {delete}", "buttons" => array("update" => array("label" => "Update Campaign", "url" => "Yii::App()->createUrl( \"/Product/campaignShow\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return MainDialog.show( \$(this) ); }"), "view" => array("label" => "View Products", "url" => "Yii::App()->createUrl( \"/Product/subCampaign\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return MainDialog.show( \$(this) ); }"), "delete" => array("label" => "Delete", "url" => "Yii::App()->createUrl( \"/Product/campaign\", array( \"id\" => \$data->primaryKey, \"delete\" => true ) )", "visible" => "Yii::App()->User->checkAccess(\"ADMIN\")"))), 1 => array("header" => "ID", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "id", 2 => array("placeholder" => "search on ID")), "method"), "value" => "\$data->id"), 2 => array("header" => "Label", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "label", 2 => array("placeholder" => "search on label")), "method"), "value" => "\$data->label"), 3 => array("header" => "Type", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "type", 2 => array("placeholder" => "search on type")), "method"), "value" => "\$data->type"), 4 => array("filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "ref", 2 => array("placeholder" => "search on ref")), "method"), "name" => "Ref", "value" => "\$data->ref")))), "method");
        // line 69
        echo "
<div style=\"float:left; margin-left:20px;\">\t

\t";
        // line 72
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "Google analytics"), 1 => array("name" => "Google analytics", "onClick" => "MainDialog.show( \"/Product/googleanalytics\" )", "style" => "font-size:16px;")), "method");
        // line 75
        echo "

</div>

";
    }

    public function getTemplateName()
    {
        return "//product/campaign.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 75,  47 => 72,  42 => 69,  40 => 13,  35 => 10,  33 => 7,  27 => 4,  22 => 2,  19 => 1,);
    }
}
