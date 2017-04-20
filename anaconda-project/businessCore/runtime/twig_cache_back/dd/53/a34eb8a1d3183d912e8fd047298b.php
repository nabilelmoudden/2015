<?php

/* //fr_rinalda/formation/formation_ref/frmldv.html */
class __TwigTemplate_dd53a34eb8a1d3183d912e8fd047298b extends Twig_Template
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


    <h1>TEST FORMATION VIEW</h1>
  <p style=\"color:blue; font-weight:bold;\">
        Mail : ";
        // line 6
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "ContextFormation"), "getFormationMail", array(), "method");
        echo "
        <br>
        PRODUCT : ";
        // line 8
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "ContextFormation"), "getFormationProduct", array(), "method");
        echo "
        <br>
        COmpagn : ";
        // line 10
        echo $this->getAttribute($this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "ContextFormation"), "getCampaign", array(), "method"), "label");
        echo "
    </p>
    

";
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/formation/formation_ref/frmldv.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  36 => 10,  31 => 8,  26 => 6,  19 => 1,);
    }
}
