<?php

/* //product/paymentProcessorSetShow.html */
class __TwigTemplate_13f4944b723356d17b632707c946cb3f extends Twig_Template
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
        if (($this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtUpdate");
            echo " \"";
            echo $this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "label");
            echo "\"</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtCreate");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "PpSetForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 13
        echo "
\t";
        // line 14
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr>
\t\t\t<th>
\t\t\t\tLabel :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 22
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PpSet"]) ? $context["PpSet"] : null), 1 => "label", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 27
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 30
        if (($this->getAttribute((isset($context["PpSet"]) ? $context["PpSet"] : null), "id") > 0)) {
            // line 31
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "MainDialog.sendForm(\"PpSetForm\", \"MainDialog\", \"gridViewPpSet\")")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 33
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "MainDialog.sendForm(\"PpSetForm\", \"MainDialog\", \"gridViewPpSet\")")), "method");
            echo "
\t\t\t\t";
        }
        // line 35
        echo "\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 39
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//product/paymentProcessorSetShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  88 => 39,  82 => 35,  76 => 33,  70 => 31,  68 => 30,  62 => 27,  54 => 22,  43 => 14,  40 => 13,  38 => 7,  35 => 6,  29 => 4,  21 => 2,  19 => 1,);
    }
}
