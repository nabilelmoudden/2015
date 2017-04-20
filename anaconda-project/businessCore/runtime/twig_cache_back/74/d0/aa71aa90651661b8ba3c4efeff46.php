<?php

/* //FormationTest/TestView.html */
class __TwigTemplate_74d0aa71aa90651661b8ba3c4efeff46 extends Twig_Template
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
        echo "<h1>
    TEST FORMATION VIEW
</h1>
  <p style=\"color:blue; font-weight:bold;\">
        Mail : ";
        // line 5
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "ContextFormation"), "getFormationMail", array(), "method");
        echo "
        <br>
        PRODUCT : ";
        // line 7
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "ContextFormation"), "getFormationProduct", array(), "method");
        echo "
       
        ";
        // line 9
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "firstName");
        echo "
    </p>
    ";
    }

    public function getTemplateName()
    {
        return "//FormationTest/TestView.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  35 => 9,  30 => 7,  25 => 5,  19 => 1,);
    }
}
