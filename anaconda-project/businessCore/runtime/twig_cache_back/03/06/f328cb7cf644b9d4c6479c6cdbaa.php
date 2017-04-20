<?php

/* //product/paymentProcessorTypeAdd.html */
class __TwigTemplate_0306f328cb7cf644b9d4c6479c6cdbaa extends Twig_Template
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
        echo Yii::t("product", "txtAddPP");
        echo "</h1>

";
        // line 3
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "addPpForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 9
        echo "
\t";
        // line 10
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr>
\t\t\t<th>
\t\t\t\tPosition :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 18
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PpSetPp"]) ? $context["PpSetPp"] : null), 1 => "position", 2 => array("size" => "5")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tPayment Processor :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 26
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["PpSetPp"]) ? $context["PpSetPp"] : null), 1 => "idPaymentProcessorType", 2 => (isset($context["listPP"]) ? $context["listPP"] : null), 3 => array("size" => 1)), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 31
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
\t\t\t\t";
        // line 33
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.sendForm( \"addPpForm\", \"SecondDialog\", \"gridViewPP\" )")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 38
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 39
        echo "
";
    }

    public function getTemplateName()
    {
        return "//product/paymentProcessorTypeAdd.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  75 => 39,  73 => 38,  65 => 33,  60 => 31,  52 => 26,  41 => 18,  30 => 10,  27 => 9,  25 => 3,  19 => 1,);
    }
}
