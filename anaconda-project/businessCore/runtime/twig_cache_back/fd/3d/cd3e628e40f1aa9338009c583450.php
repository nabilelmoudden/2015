<?php

/* //product/viewEdition.html */
class __TwigTemplate_fd3dcd3e628e40f1aa9338009c583450 extends Twig_Template
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

<h1 id=\"apTitre\">";
        // line 3
        echo Yii::t("product", "txtViewEdition");
        echo " \"";
        echo $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "view");
        echo "\"</h1>



";
        // line 7
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "



";
        // line 11
        $context["form"] = $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "beginWidget", array(0 => "CActiveForm", 1 => array("id" => "EditorForm", "enableAjaxValidation" => false, "enableClientValidation" => true)), "method");
        // line 23
        echo "


<!--\t<div name=\"editor\" id=\"editor\">&nbsp;</div>-->
    <textarea name=\"editor\" id=\"editor\"></textarea>

\t";
        // line 29
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "id", 1 => $this->getAttribute((isset($context["Ref"]) ? $context["Ref"] : null), "id"), 2 => array("id" => "id")), "method");
        echo "
    ";
        // line 30
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "Type", 1 => (isset($context["Type"]) ? $context["Type"] : null), 2 => array("type" => "type")), "method");
        echo "
<p align=\"center\">";
        // line 31
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "update"), 1 => array("name" => "cancel", "onClick" => "SaveView()")), "method");
        echo "</p>
";
        // line 32
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "endWidget", array(), "method");
        // line 33
        echo "


<script type=\"text/javascript\">

\tloadEditor( ";
        // line 38
        echo (isset($context["Data"]) ? $context["Data"] : null);
        echo " );

</script>";
    }

    public function getTemplateName()
    {
        return "//product/viewEdition.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 38,  63 => 33,  61 => 32,  57 => 31,  53 => 30,  49 => 29,  41 => 23,  39 => 11,  32 => 7,  23 => 3,  19 => 1,);
    }
}
