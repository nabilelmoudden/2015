<?php

/* //product/campaignShow.html */
class __TwigTemplate_74dff2c4d3487b3a127774b59e069d72 extends Twig_Template
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
        if (($this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtUpdate");
            echo " \"";
            echo $this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "label");
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
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "CampForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
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
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "label", 2 => array("style" => "width: 99%;")), "method");
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
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "radioButtonList", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "type", 2 => array(1 => 1, 2 => 2)), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tReference :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 38
        if (($this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "id") > 0)) {
            // line 39
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "ref", 2 => array("disabled" => "disabled")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 41
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Camp"]) ? $context["Camp"] : null), 1 => "ref"), "method");
            echo "
\t\t\t\t";
        }
        // line 43
        echo "\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 47
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 50
        if (($this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "id") > 0)) {
            // line 51
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "MainDialog.sendForm( \"CampForm\", \"MainDialog\", \"gridViewCampaign\" )")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 53
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "MainDialog.sendForm( \"CampForm\", \"MainDialog\", \"gridViewCampaign\" )")), "method");
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
        return "//product/campaignShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  122 => 59,  116 => 55,  110 => 53,  104 => 51,  102 => 50,  96 => 47,  90 => 43,  84 => 41,  78 => 39,  76 => 38,  65 => 30,  54 => 22,  43 => 14,  40 => 13,  38 => 7,  35 => 6,  29 => 4,  21 => 2,  19 => 1,);
    }
}
