<?php

/* //ap/campaignShow.html */
class __TwigTemplate_b265e0be24b18a5d1d838abe7267849e extends Twig_Template
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
        if (($this->getAttribute((isset($context["AC"]) ? $context["AC"] : null), "id") > 0)) {
            // line 2
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("AP", "txtUpdateAC");
            echo " \"";
            echo $this->getAttribute((isset($context["AC"]) ? $context["AC"] : null), "label");
            echo "\"</h1>
";
        } else {
            // line 4
            echo "\t<h1 id=\"apTitre\">";
            echo Yii::t("AP", "txtCreateAC");
            echo "</h1>
";
        }
        // line 6
        echo "
";
        // line 7
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "ACForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 13
        echo "
\t";
        // line 14
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t";
        // line 16
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "Business\\AffiliateCampaign[template]", 1 => null, 2 => array("id" => "template")), "method");
        echo "

\t<table width=\"100%\" border=\"0\">
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tLabel :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 24
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textField", array(0 => (isset($context["AC"]) ? $context["AC"] : null), 1 => "label", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tDescription :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t";
        // line 32
        echo $this->getAttribute((isset($context["form"]) ? $context["form"] : null), "textArea", array(0 => (isset($context["AC"]) ? $context["AC"] : null), 1 => "description", 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th style=\"color:#9de81c;\">
\t\t\t\t<img class=\"infoToolTip\" src=\"";
        // line 37
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help.png\" title=\"<img src='";
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/help-SP.jpg'>\" />
\t\t\t\tSite Promo :
\t\t\t</th>
\t\t\t<td>
\t\t\t\t<select name=\"Business\\PromoSite[id]\">
\t\t\t\t\t<option value=\"0\">Default</option>
\t\t\t\t\t";
        // line 43
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lPromoSite"]) ? $context["lPromoSite"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["PromoSite"]) {
            // line 44
            echo "\t\t\t\t\t\t<option ";
            if (($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), "id") == $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "label");
            echo "</option>
\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['PromoSite'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 46
        echo "\t\t\t\t</select>
\t\t\t</td>
\t\t</tr>
\t\t<tr valign=\"top\">
\t\t\t<th>
\t\t\t\tTemplate :
\t\t\t\t<br />
\t\t\t\t<br />
\t\t\t\t<br />
\t\t\t\t<br />
\t\t\t\t<img id=\"infoToolTip\" src=\"";
        // line 56
        echo $this->env->getExtension('TwigEsoterExt')->adminViewDir();
        echo "/images/info.png\" title=\"<h2>Var :</h2>{\$LINK} : Lien complet vers le site de promo<br />{\$CAMPAGNE} : ID de la campagne<br />{\$PLATFORM} : ID de la platform\" />
\t\t\t</th>
\t\t\t<td>
\t\t\t\t<textarea name=\"editor\" id=\"editor\" style=\"height:500px;\">";
        // line 59
        echo (isset($context["template"]) ? $context["template"] : null);
        echo "</textarea>
\t\t\t</td>
\t\t</tr>

\t\t<tr>
\t\t\t<td colspan=\"4\" style=\"text-align:center;\">
\t\t\t\t";
        // line 65
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "
\t\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t\t";
        // line 68
        if (($this->getAttribute((isset($context["AC"]) ? $context["AC"] : null), "id") > 0)) {
            // line 69
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "sendAffiliateCampaign()")), "method");
            echo "
\t\t\t\t";
        } else {
            // line 71
            echo "\t\t\t\t\t";
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "cancel", "onClick" => "sendAffiliateCampaign()")), "method");
            echo "
\t\t\t\t";
        }
        // line 73
        echo "\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 77
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//ap/campaignShow.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  161 => 77,  155 => 73,  149 => 71,  143 => 69,  141 => 68,  135 => 65,  126 => 59,  120 => 56,  108 => 46,  93 => 44,  89 => 43,  78 => 37,  70 => 32,  59 => 24,  48 => 16,  43 => 14,  40 => 13,  38 => 7,  35 => 6,  29 => 4,  21 => 2,  19 => 1,);
    }
}
