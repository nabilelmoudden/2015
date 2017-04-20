<?php

/* //product/subCampaign.html */
class __TwigTemplate_6b721dc54aeccca6b3503e9c2b6eee8e extends Twig_Template
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
        echo Yii::t("product", "txtSubCampaign");
        echo " \"";
        echo $this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "label");
        echo "\"</h1>



";
        // line 7
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "idCampaign", 1 => $this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "id")), "method");
        echo "



";
        // line 11
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "



";
        // line 15
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewSubCamp", "dataProvider" => $this->getAttribute((isset($context["Search"]) ? $context["Search"] : null), "search", array(0 => "position"), "method"), "filter" => (isset($context["Search"]) ? $context["Search"] : null), "columns" => array(0 => array("class" => "CButtonColumn", "template" => "{update}    {reflation}    {gp}   {router}   {delete}", "htmlOptions" => array("width" => "100px"), "buttons" => array("update" => array("label" => "Update Product", "url" => "Yii::App()->createUrl( \"/Product/productShow\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return SecondDialog.show( \$(this), \"?idSubCamp=\"+\$(\"#idSub\").val() ); }"), "reflation" => array("label" => "Reflation", "url" => "Yii::App()->createUrl( \"/Product/subCampaignReflation\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return SecondDialog.show( \$(this), \"?idSubCamp=\"+\$(\"#idSub\").val() ); }", "imageUrl" => "../../businessCore/views/images/relance.png"), "gp" => array("label" => "Pricing Grid", "url" => "Yii::App()->createUrl( \"/Product/pricingGrid\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return SecondDialog.show( \$(this) ); }", "imageUrl" => "../../businessCore/views/images/euro.png"), "router" => array("label" => "Router EMV", "url" => "Yii::App()->createUrl( \"/Product/routerEMV\", array( \"id\" => \$data->primaryKey ) )", "click" => "function() { return SecondDialog.show( \$(this) ); }", "imageUrl" => "../../businessCore/views/images/emv.png"), "delete" => array("label" => "Delete", "url" => "Yii::App()->createUrl( \"/Product/subCampaignShow\", array( \"id\" => \$data->primaryKey, \"delete\" => true, \"idCamp\" => \$data->idCampaign ) )"))), 1 => array("header" => "Position", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Search"]) ? $context["Search"] : null), 1 => "position", 2 => array("placeholder" => "search on position")), "method"), "value" => "\$data->position"), 2 => array("name" => "Description", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Search"]) ? $context["Search"] : null), 1 => "prodDesc", 2 => array("placeholder" => "search on description")), "method"), "value" => "\$data->Product->description"), 3 => array("name" => "Reference", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Search"]) ? $context["Search"] : null), 1 => "prodRef", 2 => array("placeholder" => "search on reference")), "method"), "value" => "\$data->Product->ref")))), "method");
        // line 144
        echo "


<div style=\"text-align:center\">

\t";
        // line 149
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "MainDialog.close();")), "method");
        echo "

\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t";
        // line 153
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => (("SecondDialog.show( \"/Product/productShow?idCamp=" . $this->getAttribute((isset($context["Camp"]) ? $context["Camp"] : null), "id")) . "\" )"))), "method");
        echo "

</div>



";
        // line 159
        echo $this->env->getExtension('TwigEsoterExt')->insertRegisteredScript();
    }

    public function getTemplateName()
    {
        return "//product/subCampaign.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 159,  62 => 153,  55 => 149,  48 => 144,  46 => 15,  39 => 11,  32 => 7,  23 => 3,  19 => 1,);
    }
}
