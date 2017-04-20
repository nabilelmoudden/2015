<?php

/* //product/paymentProcessorSet.html */
class __TwigTemplate_9a9e1bef1bf1565059be3d188a2a750e extends Twig_Template
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
        echo Yii::t("product", "txtPpSet");
        echo "</h1>

";
        // line 4
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

<div style=\"float:left; margin-left:20px;\">
\t";
        // line 7
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => "MainDialog.show( \"/Product/paymentProcessorSetShow\" )", "style" => "font-size:16px;")), "method");
        // line 10
        echo "
</div>

";
        // line 13
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewPpSet", "dataProvider" => $this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "search", array(0 => "label", 1 => 50), "method"), "filter" => (isset($context["PpSet"]) ? $context["PpSet"] : null), "columns" => array(0 => array("header" => "Action", "class" => "CButtonColumn", "template" => "{update}   {view}  {delete}", "buttons" => array("update" => array("label" => "Update Payment Processor Set", "url" => "Yii::App()->createUrl( \"/Product/paymentProcessorSetShow\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return MainDialog.show( \$(this) ); }"), "view" => array("label" => "View Payment Processor Set", "url" => "Yii::App()->createUrl( \"/Product/paymentProcessor\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return MainDialog.show( \$(this) ); }"), "delete" => array("label" => "Delete", "url" => "Yii::App()->createUrl( \"/Product/paymentProcessorSet\", array( \"id\" => \$data->primaryKey, \"delete\" => true ) )", "visible" => "Yii::App()->User->checkAccess(\"ADMIN\")"))), 1 => array("header" => "ID", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["PpSet"]) ? $context["PpSet"] : null), 1 => "id", 2 => array("placeholder" => "search on ID")), "method"), "value" => "\$data->id"), 2 => array("header" => "Label", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["PpSet"]) ? $context["PpSet"] : null), 1 => "label", 2 => array("placeholder" => "search on label")), "method"), "value" => "\$data->label")))), "method");
        // line 59
        echo "
";
    }

    public function getTemplateName()
    {
        return "//product/paymentProcessorSet.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 59,  40 => 13,  35 => 10,  33 => 7,  27 => 4,  22 => 2,  19 => 1,);
    }
}
