<?php

/* //product/menu.html */
class __TwigTemplate_449e5717305c42f73ba9043172e02f29 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("backoffice.html");

        $this->blocks = array(
            'menu' => array($this, 'block_menu'),
            'container' => array($this, 'block_container'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "backoffice.html";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_menu($context, array $blocks = array())
    {
        // line 4
        echo "<!-- mainmenu -->
<div class=\"divMenu\">
\t";
        // line 6
        echo $this->env->getExtension('TwigEsoterExt')->menuWithPorteur(array(0 => array("label" => "Campaign", "url" => array(0 => "/Product/campaign"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))), 1 => array("label" => "Payment Processor", "url" => array(0 => "/Product/paymentProcessorSet"), "visible" => (!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest")))));
        // line 11
        echo "

\t";
        // line 13
        if ((!$this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "user"), "isGuest"))) {
            // line 14
            echo "\t\t<img src=\"";
            echo $this->env->getExtension('TwigEsoterExt')->portDir();
            echo "/images/80.jpg\" />
        <script type=\"text/javascript\" src=\"/dev_yii/businessCore/views/js/ckeditor/ckeditor.js\"></script>
\t";
        }
        // line 17
        echo "</div>
";
    }

    // line 20
    public function block_container($context, array $blocks = array())
    {
        // line 21
        echo "<div class=\"container\">
\t";
        // line 22
        $this->displayBlock('content', $context, $blocks);
        // line 25
        echo "</div>
";
    }

    // line 22
    public function block_content($context, array $blocks = array())
    {
        // line 23
        echo "\t";
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
\t";
    }

    public function getTemplateName()
    {
        return "//product/menu.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  73 => 23,  70 => 22,  65 => 25,  63 => 22,  60 => 21,  57 => 20,  52 => 17,  45 => 14,  43 => 13,  39 => 11,  37 => 6,  33 => 4,  30 => 3,);
    }
}
