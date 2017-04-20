<?php

/* //product/paymentProcessorTypeShow.html */
class __TwigTemplate_92b22135bb436c106413ffeaf3d3b0f5 extends Twig_Template
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
        if (($this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtUpdatePP");
            echo " \"";
            echo $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "name");
            echo "\"</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtCreatePP");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "PPForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 13
        echo "
\t";
        // line 14
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr>
\t\t\t<th>
\t\t\t\tPosition :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 22
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PpSetPp"]) ? $context["PpSetPp"] : null), 1 => "position", 2 => array("size" => "5")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tName :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 30
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PP"]) ? $context["PP"] : null), 1 => "name", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tSite :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 38
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["PP"]) ? $context["PP"] : null), 1 => "idSite", 2 => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "listData", array(0 => $this->getAttribute((isset($context["Site"]) ? $context["Site"] : null), "findAll", array(), "method"), 1 => "id", 2 => "code"), "method"), 3 => array("size" => 1)), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tType :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 46
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["PP"]) ? $context["PP"] : null), 1 => "type", 2 => array(0 => "Free", 1 => "CB", 2 => "Cheque", 3 => "Boletos"), 3 => array("size" => 1)), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tClassName :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 54
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PP"]) ? $context["PP"] : null), 1 => "className", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tRef :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 62
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PP"]) ? $context["PP"] : null), 1 => "ref", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tDescription :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 70
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PP"]) ? $context["PP"] : null), 1 => "description", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th valign=\"top\">
\t\t\t\tParameters :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t<table>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Prefix :</th>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
        // line 82
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "Business\\PaymentProcessorType[param][prefix]", 1 => $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "getParam", array(0 => "prefix"), "method")), "method");
        echo "
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Mercant ID :</th>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
        // line 88
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "Business\\PaymentProcessorType[param][mercantId]", 1 => $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "getParam", array(0 => "mercantId"), "method")), "method");
        echo "
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Sub Mercant ID :</th>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
        // line 94
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "Business\\PaymentProcessorType[param][subMercantId]", 1 => $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "getParam", array(0 => "subMercantId"), "method")), "method");
        echo "
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Data Integrity Code :</th>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
        // line 100
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "Business\\PaymentProcessorType[param][dataIntegrityCode]", 1 => $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "getParam", array(0 => "dataIntegrityCode"), "method")), "method");
        echo "
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Payment Processor Label :</th>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
        // line 106
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "Business\\PaymentProcessorType[param][labelPaymentProcessorPage]", 1 => $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "getParam", array(0 => "labelPaymentProcessorPage"), "method")), "method");
        echo "
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<th>Submitter :</th>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t";
        // line 112
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "Business\\PaymentProcessorType[param][submitter]", 1 => $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "getParam", array(0 => "submitter"), "method")), "method");
        echo "
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t</table>
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 120
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 123
        if (($this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "id") > 0)) {
            // line 124
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.sendForm( \"PPForm\", \"SecondDialog\", \"gridViewPP\" )")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 126
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.sendForm( \"PPForm\", \"SecondDialog\", \"gridViewPP\" )")), "method");
            echo "
\t\t\t\t";
        }
        // line 128
        echo "\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 132
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//product/paymentProcessorTypeShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  217 => 132,  211 => 128,  205 => 126,  199 => 124,  197 => 123,  191 => 120,  180 => 112,  171 => 106,  162 => 100,  153 => 94,  144 => 88,  135 => 82,  120 => 70,  109 => 62,  98 => 54,  87 => 46,  76 => 38,  65 => 30,  54 => 22,  43 => 14,  40 => 13,  38 => 7,  35 => 6,  29 => 4,  21 => 2,  19 => 1,);
    }
}
