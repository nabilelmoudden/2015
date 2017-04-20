<?php

/* //fr_rinalda/nml/nml/mnlr3.html */
class __TwigTemplate_269247ab2eac092f3d96433560cea3f6 extends Twig_Template
{
    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->env->getExtension('TwigEsoterExt')->getProductTemplate());
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/nml/nml/mnlr3.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array ();
    }
}
