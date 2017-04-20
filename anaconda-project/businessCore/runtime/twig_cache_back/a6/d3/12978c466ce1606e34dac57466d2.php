<?php

/* //fr_rinalda/camp_fid_1/abc/abc_ldv.html */
class __TwigTemplate_a6d312978c466ce1606e34dac57466d2 extends Twig_Template
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
        return "//fr_rinalda/camp_fid_1/abc/abc_ldv.html";
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
