<?php

/* //fr_rinalda/Strate_Centrale/tyCheck.html */
class __TwigTemplate_61a4b27186d7f022475f5b3f2cf3eab1 extends Twig_Template
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

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
\tThank you check

";
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/Strate_Centrale/tyCheck.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  29 => 4,  26 => 3,);
    }
}
