<?php

/* //product/routerEMV.html */
class __TwigTemplate_b373c6fd4081a2fd3f42da3e237137b0 extends Twig_Template
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
        // line 2
        echo Yii::t("product", "txtRouter");
        echo " \"";
        echo $this->getAttribute($this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "Product"), "ref");
        echo "\"</h1>

";
        // line 4
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "idSub", 1 => $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")), "method");
        echo "

";
        // line 6
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "

";
        // line 8
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewRouter", "dataProvider" => $this->getAttribute((isset($context["Router"]) ? $context["Router"] : null), "search", array(0 => "compteEMV, type"), "method"), "filter" => (isset($context["Router"]) ? $context["Router"] : null), "columns" => array(0 => array("class" => "CButtonColumn", "template" => "{update}    {delete}", "htmlOptions" => array("width" => "100px"), "buttons" => array("update" => array("label" => "Update Url", "url" => "Yii::App()->createUrl( \"/Product/routerEMVShow\", array( \"id\" => \$data->primaryKey ) )", "click" => (("function() { return ThirdDialog.show( \$(this), \"?idSub=" . $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")) . "\" ); }")), "delete" => array("label" => "Delete Url", "url" => "Yii::App()->createUrl( \"/Product/routerEMV\", array( \"idDelete\" => \$data->primaryKey, \"delete\" => true, \"id\" => \$data->Product->SubCampaign->id ) )"))), 1 => array("header" => "compteEMV", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Router"]) ? $context["Router"] : null), 1 => "compteEMV", 2 => array("placeholder" => "search on compteEMV")), "method"), "value" => "\$data->compteEMV"), 2 => array("name" => "type", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Router"]) ? $context["Router"] : null), 1 => "type", 2 => array("placeholder" => "search on type")), "method"), "value" => "\$data->type"), 3 => array("name" => "url", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Router"]) ? $context["Router"] : null), 1 => "url", 2 => array("placeholder" => "search on url")), "method"), "value" => "\$data->url")))), "method");
        // line 52
        echo "
<div style=\"text-align:center\">
\t";
        // line 54
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.close();")), "method");
        echo "
\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
\t";
        // line 56
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => (("ThirdDialog.show( \"/Product/routerEMVShow\", \"?idSub=" . $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")) . "\" )"))), "method");
        echo "
</div>

";
        // line 59
        echo $this->env->getExtension('TwigEsoterExt')->insertRegisteredScript();
    }

    public function getTemplateName()
    {
        return "//product/routerEMV.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  56 => 59,  50 => 56,  45 => 54,  41 => 52,  39 => 8,  34 => 6,  29 => 4,  22 => 2,  19 => 1,);
    }
}
