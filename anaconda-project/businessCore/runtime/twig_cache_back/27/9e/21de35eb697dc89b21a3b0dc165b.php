<?php

/* //fr_rinalda/porteur.html */
class __TwigTemplate_279e21de35eb697dc89b21a3b0dc165b extends Twig_Template
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
        <title>Rinalda</title>
    </head>
    <body>
        ";
        // line 9
        echo (isset($context["content"]) ? $context["content"] : null);
        echo "
    </body>
</html>";
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/porteur.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  30 => 9,  26 => 3,  22 => 2,  19 => 1,);
    }
}
