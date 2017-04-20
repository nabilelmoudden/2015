<?php

/* //product/paymentProcessor.html */
class __TwigTemplate_80d28fb742fe4a2ff2ced3fde77ad4d7 extends Twig_Template
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
        echo Yii::t("product", "txtPP");
        echo " \"";
        echo $this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "label");
        echo "\"</h1>



";
        // line 5
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "



";
        // line 9
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewPP", "dataProvider" => $this->getAttribute((isset($context["PpSetPp"]) ? $context["PpSetPp"] : null), "search", array(0 => "PaymentProcessorType.idSite, position"), "method"), "columns" => array(0 => array("header" => "Action", "class" => "CButtonColumn", "template" => "{update}   {delete}", "buttons" => array("update" => array("label" => "Update Payment Processor Type", "url" => (("Yii::App()->createUrl( \"/Product/PaymentProcessorTypeShow\", array( \"id\" => \$data->PaymentProcessorType->primaryKey, \"idPpSet\" => " . $this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "id")) . " ) )"), "click" => "function() { return SecondDialog.show( \$(this) ); }"), "delete" => array("label" => "Delete", "url" => (("Yii::App()->createUrl( \"/Product/PaymentProcessor\", array( \"id\" => " . $this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "id")) . ", \"delete\" => \$data->PaymentProcessorType->primaryKey ) )"), "visible" => "Yii::App()->User->checkAccess(\"ADMIN\")"))), 1 => "position", 2 => array("header" => "Site", "value" => "\$data->PaymentProcessorType->Site->code"), 3 => array("header" => "Name", "value" => "\$data->PaymentProcessorType->name"), 4 => array("header" => "Type", "value" => "\$data->PaymentProcessorType->getHumanType()"), 5 => array("header" => "Ref", "value" => "\$data->PaymentProcessorType->ref"), 6 => array("header" => "Description", "value" => "\$data->PaymentProcessorType->description")))), "method");
        // line 108
        echo "


<div style=\"text-align:center\">

\t";
        // line 113
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "

\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t";
        // line 117
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => (("SecondDialog.show( \"/Product/PaymentProcessorTypeShow/?idPpSet=" . $this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "id")) . "\" )"))), "method");
        echo "

\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t";
        // line 121
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("product", "addExistPP"), 1 => array("name" => "create", "onClick" => (("SecondDialog.show( \"/Product/PaymentProcessorTypeAdd?id=" . $this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "id")) . "\" )"))), "method");
        echo "

</div>



";
        // line 127
        echo $this->env->getExtension('TwigEsoterExt')->insertRegisteredScript();
    }

    public function getTemplateName()
    {
        return "//product/paymentProcessor.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 127,  59 => 121,  52 => 117,  45 => 113,  38 => 108,  36 => 9,  29 => 5,  19 => 1,);
    }
}
