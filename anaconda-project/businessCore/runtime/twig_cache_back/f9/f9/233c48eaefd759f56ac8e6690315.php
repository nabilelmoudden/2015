<?php

/* //ap/HSBounce.html */
class __TwigTemplate_f9f9233c48eaefd759f56ac8e6690315 extends Twig_Template
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
        echo Yii::t("AP", "txtTitre5");
        echo "</h1>

";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

";
        // line 6
        if ((isset($context["Emv"]) ? $context["Emv"] : null)) {
            // line 7
            echo "\t<div style=\"text-align: center;\">
\t\t";
            // line 8
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => "Exporter les Hard Bounce", 1 => array("onclick" => "exportHSBounce( 1 );")), "method");
            echo "
\t\t&nbsp;&nbsp;&nbsp;&nbsp;
\t\t";
            // line 10
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => "Exporter les Soft Bounce", 1 => array("onclick" => "exportHSBounce( 2 );")), "method");
            echo "
\t\t&nbsp;&nbsp;&nbsp;&nbsp;
\t\t";
            // line 12
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => "Exporter les Desabonnés", 1 => array("onclick" => "exportHSBounce( 3 );")), "method");
            echo "
\t\t&nbsp;&nbsp;&nbsp;&nbsp;
\t\t";
            // line 14
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => "Exporter les Hard/Soft Bounce et Desabonnés", 1 => array("onclick" => "exportHSBounce();")), "method");
            echo "
\t</div>

\t";
            // line 17
            $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewEmv", "dataProvider" => $this->getAttribute((isset($context["Emv"]) ? $context["Emv"] : null), "search", array(), "method"), "filter" => (isset($context["Emv"]) ? $context["Emv"] : null), "columns" => array(0 => array("header" => "Email", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Emv"]) ? $context["Emv"] : null), 1 => "EMAIL", 2 => array("placeholder" => "search on email")), "method"), "value" => "\$data->EMAIL"), 1 => array("header" => "Source", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Emv"]) ? $context["Emv"] : null), 1 => "SOURCE", 2 => array("placeholder" => "search on source")), "method"), "value" => "\$data->SOURCE"), 2 => array("header" => "HBQ Reason", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Emv"]) ? $context["Emv"] : null), 1 => "HBQ_REASON", 2 => array("placeholder" => "search on HBQ reason")), "method"), "value" => "\$data->HBQ_REASON"), 3 => array("header" => "Date Join", "value" => "\$data->DATEJOIN", "filter" => $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.jui.CJuiDatePicker", 1 => array("name" => "DATEJOIN", "model" => (isset($context["Emv"]) ? $context["Emv"] : null), "attribute" => "DATEJOIN", "options" => array("showOn" => "focus", "dateFormat" => "yy-mm-dd", "changeYear" => "true", "changeMonth" => "true"), "htmlOptions" => array("class" => "datePicker", "placeholder" => "search on DATEJOIN")), 2 => true), "method")), 4 => array("header" => "Date Unjoin", "value" => "\$data->DATEUNJOIN", "filter" => $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.jui.CJuiDatePicker", 1 => array("name" => "DATEUNJOIN", "model" => (isset($context["Emv"]) ? $context["Emv"] : null), "attribute" => "DATEUNJOIN", "options" => array("showOn" => "focus", "dateFormat" => "yy-mm-dd", "changeYear" => "true", "changeMonth" => "true"), "htmlOptions" => array("class" => "datePicker", "placeholder" => "search on DATEUNJOIN")), 2 => true), "method")), 5 => array("name" => "type", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Emv"]) ? $context["Emv"] : null), 1 => "type", 2 => array("placeholder" => "search on type")), "method"), "value" => "( \$data->type == Business\\EmvExport::TYPE_HB ) ? \"Hard (1)\" : ( \$data->type == Business\\EmvExport::TYPE_SB ? \"Soft (2)\" : \"Desabonne (3)\" )")), "afterAjaxUpdate" => "reinstallDatePicker")), "method");
        }
    }

    public function getTemplateName()
    {
        return "//ap/HSBounce.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  58 => 17,  52 => 14,  47 => 12,  42 => 10,  37 => 8,  34 => 7,  32 => 6,  27 => 4,  22 => 2,  19 => 1,);
    }
}
