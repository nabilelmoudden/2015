<?php

/* //product/subCampaignReflationShow.html */
class __TwigTemplate_873c7f817b84036f8d94bba619c31e53 extends Twig_Template
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
        if (($this->getAttribute((isset($context["Ref"]) ? $context["Ref"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtUpdateRef");
            echo "</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "txtCreateRef");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "RefForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 13
        echo "
\t";
        // line 14
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "Business\\SubCampaignReflation[idSubCampaign]", 1 => (isset($context["idSubCamp"]) ? $context["idSubCamp"] : null)), "method");
        echo "

\t";
        // line 16
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr>
\t\t\t<th>
\t\t\t\tNumber :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 24
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "number", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tOffset Price Step :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 32
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "offsetPriceStep", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tView :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 40
        if (($this->getAttribute((isset($context["Ref"]) ? $context["Ref"] : null), "id") > 0)) {
            // line 41
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "view", 2 => array("style" => "width: 90%;", "disabled" => "disabled")), "method");
            echo ".html
\t\t\t\t";
        } else {
            // line 43
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "view", 2 => array("style" => "width: 90%;")), "method");
            echo ".html
\t\t\t\t";
        }
        // line 45
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>
\t\t\t\tTemplate Product :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 53
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "templateProd", 2 => array("style" => "width: 90%;")), "method");
        echo ".html
\t\t\t</td>
\t\t</tr>

\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 59
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "ThirdDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 62
        if (($this->getAttribute((isset($context["Ref"]) ? $context["Ref"] : null), "id") > 0)) {
            // line 63
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "ThirdDialog.sendForm( \"RefForm\", \"ThirdDialog\", \"gridViewReflation\" )")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 65
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "ThirdDialog.sendForm( \"RefForm\", \"ThirdDialog\", \"gridViewReflation\" )")), "method");
            echo "
\t\t\t\t";
        }
        // line 67
        echo "\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 71
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//product/subCampaignReflationShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 71,  132 => 67,  126 => 65,  120 => 63,  118 => 62,  112 => 59,  103 => 53,  93 => 45,  87 => 43,  81 => 41,  79 => 40,  68 => 32,  57 => 24,  46 => 16,  41 => 14,  38 => 13,  36 => 7,  33 => 6,  27 => 4,  21 => 2,  19 => 1,);
    }
}
