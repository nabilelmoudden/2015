<?php

/* //ap/campaignGenerate.html */
class __TwigTemplate_ed1ed022b25a35b9f29747eb37f697d0 extends Twig_Template
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
        echo "<h1 id=\"apTitre\">";
        echo Yii::t("AP", "txtGenerateAC");
        echo " \"";
        echo $this->getAttribute((isset($context["AC"]) ? $context["AC"] : null), "label");
        echo "\"</h1>

";
        // line 3
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "generatedUrl", 1 => (isset($context["generatedUrl"]) ? $context["generatedUrl"] : null)), "method");
        echo "
";
        // line 4
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "idCampaign", 1 => $this->getAttribute((isset($context["AC"]) ? $context["AC"] : null), "id")), "method");
        echo "

";
        // line 6
        echo Yii::t("AP", "txtHelpGenAC");
        echo "

";
        // line 8
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "ACGForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 14
        echo "
\t";
        // line 15
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<div style=\"height:600px; overflow-y:auto;\">
\t\t";
        // line 18
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => rand(), "dataProvider" => $this->getAttribute((isset($context["AP"]) ? $context["AP"] : null), "search", array(), "method"), "selectableRows" => 3, "enablePagination" => true, "columns" => array(0 => array("class" => "CCheckBoxColumn", "id" => "selAF", "checkBoxHtmlOptions" => array("name" => "selAF[]")), 1 => "label", 2 => "description", 3 => array("htmlOptions" => array("width" => "80px", "align" => "center"), "class" => "CButtonColumn", "template" => "{view}      {w3c}", "header" => "Generated", "buttons" => array("view" => array("label" => "View", "url" => array(0 => (isset($context["AC"]) ? $context["AC"] : null), 1 => "isGenerated"), "options" => array("target" => "_blank"), "visible" => array(0 => (isset($context["AC"]) ? $context["AC"] : null), 1 => "isGenerated"), "click" => "function() { return showGeneratedCampaign( \$(this) ); }"), "w3c" => array("label" => "Validate", "imageUrl" => "../../images/W3C.gif", "url" => "Yii::App()->createUrl( \"/AP/campaignValidate\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "options" => array("target" => "_blank"), "visible" => array(0 => (isset($context["AC"]) ? $context["AC"] : null), 1 => "isGenerated"), "click" => "function() { return validateGeneratedCampaign( \$(this) ); }")))))), "method");
        // line 63
        echo "\t</div>

\t<div style=\"text-align:center;\">
\t\t";
        // line 66
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "
\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
\t\t";
        // line 68
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "generate"), 1 => array("name" => "generate", "onClick" => "sendCampaignGenerate();")), "method");
        echo "
\t</div>

";
        // line 71
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 72
        echo "
<!-- !!!!! Insere le code JS generé par PHP !!!!! -->
<!-- !!!!! Seulement pour le 1er affichage, sinon les events onClick seront multiplié par le nombre d'affichage !!!!! -->
";
        // line 75
        echo $this->env->getExtension('TwigEsoterExt')->insertRegisteredScript();
        echo "

";
    }

    public function getTemplateName()
    {
        return "//ap/campaignGenerate.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  77 => 75,  72 => 72,  70 => 71,  64 => 68,  59 => 66,  54 => 63,  52 => 18,  46 => 15,  43 => 14,  41 => 8,  36 => 6,  31 => 4,  27 => 3,  19 => 1,);
    }
}
