<?php

/* //en_alisha/porteur.html */
class __TwigTemplate_2f2ed4af0ab7c5be092a50a7891e8840 extends Twig_Template
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
        echo $this->env->getExtension('TwigEsoterExt')->loadCSS(($this->env->getExtension('TwigEsoterExt')->portDir() . "/css/porteur.css"));
        echo "

<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <title>Alisha</title>
    </head>
    <body>
        ";
        // line 10
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "//en_alisha/porteur.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,  70 => 16,  67 => 15,  63 => 9,  60 => 8,  54 => 18,  51 => 17,  49 => 15,  44 => 13,  39 => 10,  31 => 10,  25 => 2,  21 => 1,  350 => 289,  330 => 272,  296 => 241,  285 => 233,  277 => 228,  88 => 42,  65 => 22,  57 => 17,  50 => 13,  43 => 9,  40 => 8,  37 => 8,  30 => 4,  27 => 3,);
    }
}
