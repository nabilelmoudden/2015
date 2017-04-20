<?php

/* //product/ajaxBdcFields.html */
class __TwigTemplate_716d5d3e79c4a15d91a319b119d74a0b extends Twig_Template
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
<table width=\"100%\">
\t<tr>
\t\t<td>&nbsp;</td>
\t\t<td align=\"center\">Global</td>
\t\t";
        // line 6
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["lPP"]) ? $context["lPP"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["PP"]) {
            // line 7
            echo "\t\t\t<td align=\"center\">";
            echo $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "name");
            echo " ";
            echo strtoupper($this->getAttribute($this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "Site"), "code"));
            echo "</td>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['PP'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 9
        echo "\t</tr>

\t";
        // line 11
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["availBdcFields"]) ? $context["availBdcFields"] : null));
        foreach ($context['_seq'] as $context["name"] => $context["field"]) {
            // line 12
            echo "\t\t<tr>
\t\t\t<td>";
            // line 13
            echo ucfirst((isset($context["name"]) ? $context["name"] : null));
            echo " :</td>
\t\t\t<td align=\"center\">
\t\t\t\t";
            // line 15
            echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "checkbox", array(0 => "bdcFields[global][]", 1 => $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "isBdcFields", array(0 => "global", 1 => (isset($context["name"]) ? $context["name"] : null)), "method"), 2 => array("value" => (isset($context["name"]) ? $context["name"] : null))), "method");
            echo "
\t\t\t</td>
\t\t\t";
            // line 17
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["lPP"]) ? $context["lPP"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["PP"]) {
                // line 18
                echo "\t\t\t\t<td align=\"center\">
\t\t\t\t\t";
                // line 19
                echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "checkbox", array(0 => (("bdcFields[" . $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "id")) . "][]"), 1 => $this->getAttribute((isset($context["Prod"]) ? $context["Prod"] : null), "isBdcFields", array(0 => $this->getAttribute((isset($context["PP"]) ? $context["PP"] : null), "id"), 1 => (isset($context["name"]) ? $context["name"] : null)), "method"), 2 => array("value" => (isset($context["name"]) ? $context["name"] : null))), "method");
                echo "
\t\t\t\t</td>
\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['PP'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 22
            echo "\t\t</tr>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 24
        echo "
</table>";
    }

    public function getTemplateName()
    {
        return "//product/ajaxBdcFields.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 24,  78 => 22,  69 => 19,  66 => 18,  62 => 17,  57 => 15,  52 => 13,  49 => 12,  45 => 11,  41 => 9,  30 => 7,  26 => 6,  19 => 1,);
    }
}
