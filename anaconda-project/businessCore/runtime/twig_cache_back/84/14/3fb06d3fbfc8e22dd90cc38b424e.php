<?php

/* //product/subCampaignReflation.html */
class __TwigTemplate_84143fb06d3fbfc8e22dd90cc38b424e extends Twig_Template
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
        echo Yii::t("product", "txtSubCampaignRef");
        echo " \"";
        echo $this->getAttribute($this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "Product"), "ref");
        echo "\"</h1>



";
        // line 7
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "hiddenField", array(0 => "idSub", 1 => $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")), "method");
        echo "



";
        // line 11
        echo $this->env->getExtension('TwigEsoterExt')->printFlashes();
        echo "



";
        // line 15
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewReflation", "dataProvider" => $this->getAttribute((isset($context["Ref"]) ? $context["Ref"] : null), "search", array(0 => "number"), "method"), "filter" => (isset($context["Ref"]) ? $context["Ref"] : null), "columns" => array(0 => array("header" => "Action", "class" => "CButtonColumn", "template" => "{update}   {delete}  {edit}   {editTemp}", "htmlOptions" => array("width" => "80px"), "buttons" => array("update" => array("label" => "Update", "url" => "Yii::App()->createUrl( \"/Product/subCampaignReflationShow\", array( \"id\" => \$data->primaryKey ) )", "click" => (("function() { return ThirdDialog.show( \$(this), \"?idSubCamp=" . $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")) . "\" ); }")), "delete" => array("label" => "Delete", "url" => "Yii::App()->createUrl( \"/Product/subCampaignReflationShow\", array( \"id\" => \$data->primaryKey, \"delete\" => true, \"idSubCamp\" => \$data->idSubCampaign ) )"), "edit" => array("label" => "View Edition", "url" => "Yii::App()->createUrl( \"/Product/viewEdition\", array( \"id\" => \$data->primaryKey, \"type\" => \"view\" ) )", "click" => "function() { return ThirdDialog.show( \$(this) ); }", "imageUrl" => "../../businessCore/views/images/edition.png"), "editTemp" => array("label" => "template Product Edition", "url" => "Yii::App()->createUrl( \"/Product/viewEdition\", array( \"id\" => \$data->primaryKey, \"type\" => \"templateProd\" ) )", "click" => "function() { return ThirdDialog.show( \$(this) ); }", "imageUrl" => "../../businessCore/views/images/edit2.png"))), 1 => array("header" => "Number", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "number", 2 => array("placeholder" => "search on number")), "method"), "value" => "\$data->number"), 2 => array("header" => "Offset Price Step", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "offsetPriceStep", 2 => array("placeholder" => "search on offset price step")), "method"), "value" => "\$data->offsetPriceStep"), 3 => array("header" => "View", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "view", 2 => array("placeholder" => "search on view")), "method"), "value" => "\$data->view"), 4 => array("header" => "Template Product", "filter" => $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "activeTextField", array(0 => (isset($context["Ref"]) ? $context["Ref"] : null), 1 => "templateProd", 2 => array("placeholder" => "search on template product")), "method"), "value" => "\$data->templateProd")))), "method");
        // line 142
        echo "


<div style=\"text-align:center\">

\t";
        // line 147
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "cancel"), 1 => array("name" => "cancel", "onClick" => "SecondDialog.close();")), "method");
        echo "

\t&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

\t";
        // line 151
        echo $this->getAttribute((isset($context["CHtml"]) ? $context["CHtml"] : null), "button", array(0 => Yii::t("common", "create"), 1 => array("name" => "create", "onClick" => (("ThirdDialog.show( \"/Product/subCampaignReflationShow/\", \"?idSubCamp=" . $this->getAttribute((isset($context["Sub"]) ? $context["Sub"] : null), "id")) . "\" )"))), "method");
        echo "

</div>



";
        // line 157
        echo $this->env->getExtension('TwigEsoterExt')->insertRegisteredScript();
    }

    public function getTemplateName()
    {
        return "//product/subCampaignReflation.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  71 => 157,  62 => 151,  55 => 147,  48 => 142,  46 => 15,  39 => 11,  32 => 7,  23 => 3,  19 => 1,);
    }
}
