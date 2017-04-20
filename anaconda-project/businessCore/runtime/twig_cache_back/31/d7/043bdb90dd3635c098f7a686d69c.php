<?php

/* //product/getPricingGrid.html */
class __TwigTemplate_31d7043bdb90dd3635c098f7a686d69c extends Twig_Template
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
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => ("PGForm_" . (isset($context["idSite"]) ? $context["idSite"] : null)), "enableAjaxValidation" => true, "enableClientValidation" => true, "action" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "normalizeUrl", array(0 => "PricingGrid"), "method"))), "method");
        // line 14
        echo "


";
        // line 17
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "id", 1 => $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")), "method");
        echo "

";
        // line 19
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "idSite", 1 => (isset($context["idSite"]) ? $context["idSite"] : null)), "method");
        echo "



";
        // line 23
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "



<div class=\"grid-view\" style=\"position:relative; height:100%;\">



\t";
        // line 31
        if (((isset($context["nbRelance"]) ? $context["nbRelance"] : null) > 0)) {
            // line 32
            echo "
\t\t<table class=\"items\" width=\"100%\" style=\"cursor:pointer;\">

\t\t\t<tr>

\t\t\t\t<th colspan=\"2\" style=\"border: 1px black solid;\">&nbsp;</th>

\t\t\t\t<th colspan=\"10\" style=\"border: 1px black solid;\">Price Step</th>

\t\t\t</tr>



\t\t\t<tr>

\t\t\t\t<th style=\"border-bottom: 1px black solid; border-left: 1px black solid; border-right: 1px black solid;\">Batch Selling</th>

\t\t\t\t<th style=\"border-bottom: 1px black solid; border-right: 1px black solid;\">Pricing Grid</th>

\t\t\t\t";
            // line 51
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["nbRelance"]) ? $context["nbRelance"] : null)));
            foreach ($context['_seq'] as $context["_key"] => $context["k"]) {
                // line 52
                echo "
\t\t\t\t\t<th width=\"";
                // line 53
                echo (90 / (isset($context["nbRelance"]) ? $context["nbRelance"] : null));
                echo "%\" style=\"border-bottom: 1px black solid; border-right: 1px black solid;\">";
                echo (isset($context["k"]) ? $context["k"] : null);
                echo "</th>

\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['k'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 56
            echo "
\t\t\t</tr>



\t\t\t";
            // line 61
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["nbBS"]) ? $context["nbBS"] : null)));
            foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                // line 62
                echo "
\t\t\t\t";
                // line 63
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["nbGP"]) ? $context["nbGP"] : null)));
                foreach ($context['_seq'] as $context["_key"] => $context["j"]) {
                    // line 64
                    echo "
\t\t\t\t\t<tr>

\t\t\t\t\t\t";
                    // line 67
                    if (((isset($context["j"]) ? $context["j"] : null) == 1)) {
                        // line 68
                        echo "
\t\t\t\t\t\t\t<td rowspan=\"";
                        // line 69
                        echo (isset($context["nbBS"]) ? $context["nbBS"] : null);
                        echo "\" style=\"text-align:center; border-left: 1px black solid; border-bottom: 1px black solid; border-right: 1px black solid;\">";
                        echo (isset($context["i"]) ? $context["i"] : null);
                        echo "</td>

\t\t\t\t\t\t";
                    }
                    // line 72
                    echo "
\t\t\t\t\t\t<td style=\"text-align:center; border-bottom: 1px black solid; border-right: 1px black solid;\">";
                    // line 73
                    echo (isset($context["j"]) ? $context["j"] : null);
                    echo "</td>

\t\t\t\t\t\t";
                    // line 75
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable(range(1, (isset($context["nbRelance"]) ? $context["nbRelance"] : null)));
                    foreach ($context['_seq'] as $context["_key"] => $context["k"]) {
                        // line 76
                        echo "
\t\t\t\t\t\t\t<td style=\"text-align:center; font-weight:bold; border-bottom: 1px black solid; border-right: 1px black solid;\">

\t\t\t\t\t\t\t\t";
                        // line 79
                        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "textField", array(0 => (((((("GP[" . (isset($context["i"]) ? $context["i"] : null)) . "][") . (isset($context["k"]) ? $context["k"] : null)) . "][") . (isset($context["j"]) ? $context["j"] : null)) . "]"), 1 => $this->env->getExtension('TwigEsoterExt')->getPrice($this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id"), (isset($context["i"]) ? $context["i"] : null), (isset($context["k"]) ? $context["k"] : null), (isset($context["j"]) ? $context["j"] : null), (isset($context["idSite"]) ? $context["idSite"] : null), false), 2 => array("size" => 10)), "method");
                        echo "

\t\t\t\t\t\t\t</td>

\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['k'], $context['_parent'], $context['loop']);
                    $context = array_merge($_parent, array_intersect_key($context, $_parent));
                    // line 84
                    echo "
\t\t\t\t\t</tr>

\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['j'], $context['_parent'], $context['loop']);
                $context = array_merge($_parent, array_intersect_key($context, $_parent));
                // line 88
                echo "
\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 90
            echo "
\t\t</table>

\t";
        }
        // line 94
        echo "


\t<div style=\"text-align:center; padding-top:30px;\">

\t\t";
        // line 99
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.close();")), "method");
        echo "



\t\t";
        // line 103
        if (((isset($context["nbRelance"]) ? $context["nbRelance"] : null) > 0)) {
            // line 104
            echo "
\t\t\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t\t\t";
            // line 107
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "update", "onClick" => (("sendPricingGrid( " . (isset($context["idSite"]) ? $context["idSite"] : null)) . ");"))), "method");
            echo "

\t\t";
        }
        // line 110
        echo "
\t</div>



</div>



";
        // line 119
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "//product/getPricingGrid.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  214 => 119,  203 => 110,  197 => 107,  192 => 104,  190 => 103,  183 => 99,  176 => 94,  170 => 90,  163 => 88,  154 => 84,  143 => 79,  138 => 76,  134 => 75,  129 => 73,  126 => 72,  118 => 69,  115 => 68,  113 => 67,  108 => 64,  104 => 63,  101 => 62,  97 => 61,  90 => 56,  79 => 53,  76 => 52,  72 => 51,  51 => 32,  49 => 31,  38 => 23,  31 => 19,  26 => 17,  21 => 14,  19 => 1,);
    }
}
