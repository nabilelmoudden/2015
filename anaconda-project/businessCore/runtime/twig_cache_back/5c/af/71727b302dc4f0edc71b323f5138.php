<?php

/* //fr_rinalda/tkt/rin_tkt/apldv.html */
class __TwigTemplate_5caf71727b302dc4f0edc71b323f5138 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->env->getExtension('TwigEsoterExt')->getProductTemplate());
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 1
    public function block_content($context, array $blocks = array())
    {
        echo "<br />
";
        // line 2
        echo (isset($context["BDC"]) ? $context["BDC"] : null);
        echo " ";
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/tkt/rin_tkt/apldv.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  31 => 2,  26 => 1,);
    }
}
