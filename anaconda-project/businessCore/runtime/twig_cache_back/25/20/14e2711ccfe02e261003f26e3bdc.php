<?php

/* //ap/configPorteur.html */
class __TwigTemplate_252014e2711ccfe02e261003f26e3bdc extends Twig_Template
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
        echo "
<h1 id=\"apTitre\">";
        // line 2
        echo Yii::t("AP", "txtTitre4");
        echo "</h1>

";
        // line 4
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "APForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 10
        echo "
\t";
        // line 11
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

\t<table width=\"90%\">
\t\t<tr>
\t\t\t<th width=\"30%\">";
        // line 15
        echo Yii::t("AP", "txtDNS");
        echo " :</th>
\t\t\t<td>
\t\t\t\t";
        // line 17
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "confDNS", 1 => $this->getAttribute((isset($context["ConfDNS"]) ? $context["ConfDNS"] : null), "value"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th>";
        // line 21
        echo Yii::t("AP", "txtDefPS");
        echo " :</th>
\t\t\t<td>
\t\t\t\t<table width=\"30%\">
\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t<input type=\"radio\" name=\"splitTest\" ";
        // line 26
        if ((twig_length_filter($this->env, (isset($context["PS"]) ? $context["PS"] : null)) <= 1)) {
            echo " checked ";
        }
        echo " value=\"0\" onClick=\"toggleSplitTest( this.value );\">Sans split test
\t\t\t\t\t\t</td>
\t\t\t\t\t\t<td id=\"tdNoSplitTest\" style=\"display:";
        // line 28
        if ((twig_length_filter($this->env, (isset($context["PS"]) ? $context["PS"] : null)) <= 1)) {
            echo " table-cell ";
        } else {
            echo "none";
        }
        echo ";\">
\t\t\t\t\t\t\t<select name=\"idPromoSite\">
\t\t\t\t\t\t\t\t";
        // line 30
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lPromoSite"]) ? $context["lPromoSite"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["PromoSite"]) {
            // line 31
            echo "\t\t\t\t\t\t\t\t\t<option ";
            if (($this->getAttribute($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), 0, array(), "array"), "idPromoSite") == $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "label");
            echo "</option>
\t\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['PromoSite'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 33
        echo "\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t\t<tr>
\t\t\t\t\t\t<td>
\t\t\t\t\t\t\t<input type=\"radio\" name=\"splitTest\" ";
        // line 38
        if ((twig_length_filter($this->env, (isset($context["PS"]) ? $context["PS"] : null)) > 1)) {
            echo " checked ";
        }
        echo " value=\"1\" onClick=\"toggleSplitTest( this.value );\">Avec split test
\t\t\t\t\t\t</td>
\t\t\t\t\t\t<td id=\"tdWithSplitTest\" style=\"display:";
        // line 40
        if ((twig_length_filter($this->env, (isset($context["PS"]) ? $context["PS"] : null)) > 1)) {
            echo " table-cell ";
        } else {
            echo "none";
        }
        echo ";\">
\t\t\t\t\t\t\t<select name=\"idPromoSiteSplit1\">
\t\t\t\t\t\t\t\t";
        // line 42
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lPromoSite"]) ? $context["lPromoSite"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["PromoSite"]) {
            // line 43
            echo "\t\t\t\t\t\t\t\t\t<option ";
            if (($this->getAttribute($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), 0, array(), "array"), "idPromoSite") == $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "label");
            echo "</option>
\t\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['PromoSite'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 45
        echo "\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t\t<br />
\t\t\t\t\t\t\t<select name=\"idPromoSiteSplit2\">
\t\t\t\t\t\t\t\t";
        // line 48
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lPromoSite"]) ? $context["lPromoSite"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["PromoSite"]) {
            // line 49
            echo "\t\t\t\t\t\t\t\t\t<option ";
            if (($this->getAttribute($this->getAttribute((isset($context["PS"]) ? $context["PS"] : null), 1, array(), "array"), "idPromoSite") == $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id"))) {
                echo "selected";
            }
            echo " value=\"";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "id");
            echo "\">";
            echo $this->getAttribute((isset($context["PromoSite"]) ? $context["PromoSite"] : null), "label");
            echo "</option>
\t\t\t\t\t\t\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['PromoSite'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 51
        echo "\t\t\t\t\t\t\t</select>
\t\t\t\t\t\t</td>
\t\t\t\t\t</tr>
\t\t\t\t</table>
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th width=\"30%\">";
        // line 58
        echo Yii::t("AP", "txtPageError");
        echo " :</th>
\t\t\t<td>
\t\t\t\t";
        // line 60
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "confError", 1 => $this->getAttribute((isset($context["ConfError"]) ? $context["ConfError"] : null), "value"), 2 => array("style" => "width: 99%;")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t\t<tr>
\t\t\t<th width=\"30%\">Url Modification Sub ID :</th>
\t\t\t<td>
\t\t\t\t";
        // line 66
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => "urlModifSubID", 1 => $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "createAbsoluteUrl", array(0 => "/AP/subID"), "method"), 2 => array("style" => "width: 99%;", "readonly" => "readonly", "onClick" => "this.select();")), "method");
        echo "
\t\t\t</td>
\t\t</tr>

\t\t<tr>
\t\t\t<td colspan=\"2\" align=\"center\">
\t\t\t\t";
        // line 72
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "submitButton", array(0 => Yii::t("common", "update"), 1 => array("name" => "update")), "method");
        echo "
\t\t\t</td>
\t\t</tr>
\t</table>

";
        // line 77
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 78
        echo "
<script type=\"text/javascript\">
\tfunction toggleSplitTest( val )
\t{
\t\t\$('#tdNoSplitTest').css( 'display', 'none' );
\t\t\$('#tdWithSplitTest').css( 'display', 'none' );

\t\tif( val == 0 )
\t\t\t\$('#tdNoSplitTest').css( 'display', 'table-cell' );
\t\telse
\t\t\t\$('#tdWithSplitTest').css( 'display', 'table-cell' );
\t}
</script>";
    }

    public function getTemplateName()
    {
        return "//ap/configPorteur.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  202 => 78,  200 => 77,  192 => 72,  183 => 66,  174 => 60,  169 => 58,  160 => 51,  145 => 49,  141 => 48,  136 => 45,  121 => 43,  117 => 42,  108 => 40,  101 => 38,  94 => 33,  79 => 31,  75 => 30,  66 => 28,  59 => 26,  51 => 21,  44 => 17,  39 => 15,  32 => 11,  29 => 10,  27 => 4,  22 => 2,  19 => 1,);
    }
}
