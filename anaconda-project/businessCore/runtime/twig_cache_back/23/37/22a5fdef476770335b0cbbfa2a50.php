<?php

/* //ap/promoSiteShow.html */
class __TwigTemplate_233722a5fdef476770335b0cbbfa2a50 extends Twig_Template
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
        if (($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("AP", "txtUpdatePS");
            echo " \"";
            echo $this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "label");
            echo "\"</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("AP", "txtCreatePS");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "PSForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 13
        echo "
\t";
        // line 14
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tLabel :
\t\t\t</th>
\t\t\t<td colspan=\"2\">
\t\t\t\t";
        // line 22
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["PS"]) ? $context["PS"] : null), 1 => "label", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tDescription :
\t\t\t</th>
\t\t\t<td colspan=\"2\">
\t\t\t\t";
        // line 30
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textArea", array(0 => (isset($context["PS"]) ? $context["PS"] : null), 1 => "description", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tURL :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t<select id=\"promoSiteUrl\" name=\"Business\\PromoSite[url]\">
\t\t\t\t\t";
        // line 39
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "getAvailablePromoSite", array(), "method"));
        foreach ($context['_seq'] as $context["_key"] => $context["url"]) {
            // line 40
            echo "\t\t\t\t\t\t<option ";
            if (($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "url") == (isset($context["url"]) ? $context["url"] : null))) {
                echo "selected";
            }
            echo " value=\"";
            echo (isset($context["url"]) ? $context["url"] : null);
            echo "\">";
            echo (isset($context["url"]) ? $context["url"] : null);
            echo "</option>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['url'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 42
        echo "\t\t\t\t</select>
\t\t\t</td>
\t\t\t<td>
\t\t\t\t<img src=\"";
        // line 45
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/allerA.png\" style=\"margin-top:5px; cursor:pointer;\" onClick=\"window.open( '";
        echo $this->getAttribute((isset($context["DNS"]) ? $context["DNS"] : null), "value");
        echo "/voyance/'+\$('#promoSiteUrl').val(), '', '' );\" />
\t\t\t</td>
\t\t</tr>

\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 51
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 54
        if (($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "id") > 0)) {
            // line 55
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "sendPromoSite()")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 57
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "sendPromoSite()")), "method");
            echo "
\t\t\t\t";
        }
        // line 59
        echo "\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 63
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//ap/promoSiteShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  138 => 63,  132 => 59,  126 => 57,  120 => 55,  118 => 54,  112 => 51,  101 => 45,  96 => 42,  81 => 40,  77 => 39,  65 => 30,  54 => 22,  43 => 14,  40 => 13,  38 => 7,  35 => 6,  29 => 4,  21 => 2,  19 => 1,);
    }
}
