<?php

/* //product/routerEMVShow.html */
class __TwigTemplate_41fee78895113096bb3a26eb529e8eb2 extends Twig_Template
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
        if (($this->getAttribute((isset($context["Router"]) ? $context["Router"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtRoutUpdate");
            echo " \"";
            echo $this->getAttribute((isset($context["Router"]) ? $context["Router"] : null), "compteEMV");
            echo " - ";
            echo $this->getAttribute((isset($context["Router"]) ? $context["Router"] : null), "type");
            echo "\"</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtRoutCreate");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "routerForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 13
        echo "
\t";
        // line 14
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr>
\t\t\t<th>
\t\t\t\tCompte EMV :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 22
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["Router"]) ? $context["Router"] : null), 1 => "compteEMV", 2 => (isset($context["listCompteEmv"]) ? $context["listCompteEmv"] : null), 3 => array("size" => 1)), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tType :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 30
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "listBox", array(0 => (isset($context["Router"]) ? $context["Router"] : null), 1 => "type", 2 => (isset($context["routerType"]) ? $context["routerType"] : null), 3 => array("size" => 1)), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tUrl :
\t\t\t\t<br />
\t\t\t\t<br />
\t\t\t\t<br />
\t\t\t\t<img id=\"infoToolTip\" src=\"";
        // line 39
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/info.png\" title=\"<h2>Token : </h2>";
        echo (isset($context["tokenHelp"]) ? $context["tokenHelp"] : null);
        echo "<br/></h2>\" />
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 42
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textArea", array(0 => (isset($context["Router"]) ? $context["Router"] : null), 1 => "url", 2 => array("style" => "width: 99%; height:200px;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 47
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "ThirdDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 50
        if (($this->getAttribute((isset($context["Router"]) ? $context["Router"] : null), "id") > 0)) {
            // line 51
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "ThirdDialog.sendForm( \"routerForm\", \"ThirdDialog\", \"gridViewRouter\" )")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 53
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "ThirdDialog.sendForm( \"routerForm\", \"ThirdDialog\", \"gridViewRouter\" )")), "method");
            echo "
\t\t\t\t";
        }
        // line 55
        echo "\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 59
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//product/routerEMVShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 59,  115 => 55,  109 => 53,  103 => 51,  101 => 50,  95 => 47,  87 => 42,  79 => 39,  67 => 30,  56 => 22,  45 => 14,  42 => 13,  40 => 7,  37 => 6,  31 => 4,  21 => 2,  19 => 1,);
    }
}
