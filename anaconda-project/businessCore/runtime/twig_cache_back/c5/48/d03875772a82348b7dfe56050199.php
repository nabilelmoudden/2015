<?php

/* /contentManager/index.html */
class __TwigTemplate_c548d03875772a82348b7dfe56050199 extends Twig_Template
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
";
        // line 2
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "contentManager-form")), "method");
        // line 5
        echo "
";
        // line 6
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

<fieldset style=\"float:left; width:28%;\" >
\t<legend>Listes des Vues</legend>

\t<div id=\"templateList\">
\t\t";
        // line 12
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["tempList"]) ? $context["tempList"] : null));
        foreach ($context['_seq'] as $context["name"] => $context["dir"]) {
            // line 13
            echo "\t\t\t<label>";
            echo (isset($context["name"]) ? $context["name"] : null);
            echo "</label>
\t\t\t<ul>
\t\t\t\t";
            // line 15
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["dir"]) ? $context["dir"] : null));
            foreach ($context['_seq'] as $context["fileName"] => $context["file"]) {
                if (is_string((isset($context["file"]) ? $context["file"] : null))) {
                    // line 16
                    echo "\t\t\t\t\t<li file=\"";
                    echo (isset($context["file"]) ? $context["file"] : null);
                    echo "\">";
                    echo (isset($context["fileName"]) ? $context["fileName"] : null);
                    echo "</li>
\t\t\t\t";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['fileName'], $context['file'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 18
            echo "\t\t\t</ul>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['name'], $context['dir'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 20
        echo "\t</div>

</fieldset>

<fieldset style=\"float:right; width:68%;\" >
\t<legend>Edition de la vue : <span id=\"currentEdition\"></span></legend>

\t<div name=\"editor\" id=\"editor\">&nbsp;</div>

\t<div style=\"text-align:center;\">
\t\t";
        // line 30
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "submitButton", array(0 => "Enregistrer"), "method");
        echo "
\t</div>
</fieldset>

";
        // line 34
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
    }

    public function getTemplateName()
    {
        return "/contentManager/index.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  89 => 34,  82 => 30,  70 => 20,  63 => 18,  51 => 16,  46 => 15,  40 => 13,  36 => 12,  27 => 6,  24 => 5,  22 => 2,  19 => 1,);
    }
}
