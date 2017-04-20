<?php

/* //fr_rinalda/testview/teste.html */
class __TwigTemplate_41dfe58808342698464b7fab8baa73f9 extends Twig_Template
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
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "lastName");
        echo "
  ";
        // line 3
        echo $this->getAttribute((isset($context["Invoice"]) ? $context["Invoice"] : null), "id");
        echo "
  
";
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/testview/teste.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  26 => 3,  22 => 2,  19 => 1,);
    }
}
