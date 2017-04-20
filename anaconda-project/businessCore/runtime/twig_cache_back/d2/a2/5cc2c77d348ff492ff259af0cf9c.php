<?php

/* //ap/campaign.html */
class __TwigTemplate_d2a25cc2c77d348ff492ff259af0cf9c extends Twig_Template
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
        echo Yii::t("AP", "txtTitre3");
        echo "</h1>

";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

<div style=\"float:left; margin-left:20px;\">
\t";
        // line 7
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => "campaignShow( \"/AP/campaignShow/?partialRender=true\" )", "style" => "font-size:16px;")), "method");
        // line 10
        echo "
</div>

";
        // line 13
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewAC", "dataProvider" => $this->getAttribute((isset($context["AC"]) ? $context["AC"] : null), "search", array(0 => false, 1 => 50), "method"), "filter" => (isset($context["AC"]) ? $context["AC"] : null), "columns" => array(0 => array("header" => "Action", "htmlOptions" => array("width" => "100px", "align" => "center"), "class" => "CButtonColumn", "template" => "{update}   {view}   {link}   {do}   {delete}", "buttons" => array("update" => array("label" => "Update", "url" => "Yii::App()->createUrl( \"/AP/campaignShow\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return campaignShow( \$(this) ); }"), "view" => array("label" => "View template", "url" => "Yii::App()->getBaseUrl(true).\"/campaignTemplate/\".Yii::App()->params['porteur'].\"/\".\$data->primaryKey.\"/template.html\"", "options" => array("target" => "_blank")), "link" => array("label" => "Links", "url" => "Yii::App()->createUrl( \"/AP/campaignLink\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return campaignLink( \$(this) ); }", "imageUrl" => "../../businessCore/views/images/lien.png"), "do" => array("label" => "Generate", "url" => "Yii::App()->createUrl( \"/AP/campaignGenerate\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return campaignGenerate( \$(this) ); }", "imageUrl" => "../../businessCore/views/images/do.png"), "delete" => array("label" => "Delete", "url" => "Yii::App()->createUrl( \"/AP/campaign\", array( \"id\" => \$data->primaryKey, \"delete\" => true ) )", "visible" => "Yii::App()->User->checkAccess(\"ADMIN\")"))), 1 => array("header" => "Label", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["AC"]) ? $context["AC"] : null), 1 => "label", 2 => array("placeholder" => "search on label")), "method"), "value" => "\$data->label"), 2 => array("header" => "Description", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["AC"]) ? $context["AC"] : null), 1 => "description", 2 => array("placeholder" => "search on description")), "method"), "value" => "\$data->description")))), "method");
    }

    public function getTemplateName()
    {
        return "//ap/campaign.html";
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
