<?php

/* //product/pricingGrid.html */
class __TwigTemplate_0ab4958c7666ea780b2abe5a1baf70f7 extends Twig_Template
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
        echo "<h1 id=\"apTitre\">";
        echo Yii::t("product", "txtPricingGrid");
        echo " \"";
        echo $this->getAttribute($this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "Product"), "ref");
        echo "\"</h1>



";
        // line 5
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "



<div style=\"position:relative; height:700px;\">

\t";
        // line 11
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.jui.CJuiTabs", 1 => array("id" => "tabPricingGrid", "tabs" => (isset($context["tab"]) ? $context["tab"] : null))), "method");
        // line 22
        echo "
</div>



";
        // line 27
        echo $this->env->getExtension('TwigEsoterExt')->insertRegisteredScript();
    }

    public function getTemplateName()
    {
        return "//product/pricingGrid.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 27,  40 => 22,  38 => 11,  29 => 5,  19 => 1,);
    }
}
