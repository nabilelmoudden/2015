<?php

/* //product/googleAnalytics.html */
class __TwigTemplate_9adbe49ba446ae2a05587615b2794db4 extends Twig_Template
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
        if (($this->getAttribute((isset($context["GoogleAnalytics"]) ? $context["GoogleAnalytics"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "AnalyticsUpdate");
            echo "</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("product", "AnalyticsCreate");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "GoogleAnalyticsForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 14
        echo "
";
        // line 15
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr>
\t\t\t<th>
\t\t\t\tCode Google Analytics :
\t\t\t</th>
\t\t\t<td>

\t\t\t\t";
        // line 24
        if (($this->getAttribute((isset($context["GoogleAnalytics"]) ? $context["GoogleAnalytics"] : null), "id") > 0)) {
            // line 25
            echo "
\t\t\t\t\t";
            // line 26
            echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["GoogleAnalytics"]) ? $context["GoogleAnalytics"] : null), 1 => "code", 2 => array("disabled" => "disabled")), "method");
            echo "

\t\t\t\t";
        } else {
            // line 29
            echo "
\t\t\t\t\t";
            // line 30
            echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["GoogleAnalytics"]) ? $context["GoogleAnalytics"] : null), 1 => "code"), "method");
            echo "

\t\t\t\t";
        }
        // line 33
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t
\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 43
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//product/googleAnalytics.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 43,  73 => 33,  67 => 30,  64 => 29,  58 => 26,  55 => 25,  53 => 24,  41 => 15,  38 => 14,  36 => 7,  33 => 6,  27 => 4,  21 => 2,  19 => 1,);
    }
}
