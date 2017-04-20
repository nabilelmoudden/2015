<?php

/* //fr_rinalda/Strate_Centrale/fr_rin_vp/vplml.html */
class __TwigTemplate_2fa979f63ca2ffd51d36f41371ff692f extends Twig_Template
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

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "VPLML

";
        // line 7
        echo (isset($context["BDC"]) ? $context["BDC"] : null);
        echo "
";
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/Strate_Centrale/fr_rin_vp/vplml.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  33 => 7,  29 => 5,  26 => 4,);
    }
}
