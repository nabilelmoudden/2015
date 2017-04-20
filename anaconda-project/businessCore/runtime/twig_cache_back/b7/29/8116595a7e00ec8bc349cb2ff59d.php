<?php

/* //sav/customerProfile.html */
class __TwigTemplate_b7298116595a7e00ec8bc349cb2ff59d extends Twig_Template
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
        echo $this->env->getExtension('TwigEsoterExt')->pageTitle("SAV - Customer Profile");
        echo "

";
        // line 3
        echo $this->env->getExtension('TwigEsoterExt')->loadJS(($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "baseUrl") . "/js/SAV/customerProfile.js"));
        echo "

";
        // line 5
        if ((isset($context["monitor"]) ? $context["monitor"] : null)) {
            // line 6
            echo "\t<h1>Customer Profile - To monitor</h1>
";
        } else {
            // line 8
            echo "\t<h1>Customer Profile - All</h1>
";
        }
        // line 10
        echo " 
";
        // line 11
        $this->getAttribute((isset($context["this"]) ? $context["this"] : null), "widget", array(0 => "zii.widgets.grid.CGridView", 1 => array("id" => "gridViewUser", "dataProvider" => $this->getAttribute((isset($context["User"]) ? $context["User"] : null), "search", array(), "method"), "filter" => (isset($context["User"]) ? $context["User"] : null), "columns" => array(0 => "id", 1 => "email", 2 => "civility", 3 => "firstName", 4 => "lastName", 5 => array("name" => "creationDate", "value" => "( new DateTime( \$data->creationDate ) )->format( \"d-m-Y H:i\" )"), 6 => "savToMonitor", 7 => array("class" => "CButtonColumn", "template" => "{view}{update}{desinscrire}{inscrire}", "buttons" => array("view" => array("url" => "Yii::App()->createUrl( \"/SAV/customerProfileShow\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return customerProfileShow( \$(this) ); }"), "update" => array("label" => "Mettre a jour", "imageUrl" => ($this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "request"), "baseUrl") . "/images/update.png"), "url" => "Yii::App()->createUrl( \"/SAV/customerProfileUpdate\", array( \"id\" => \$data->primaryKey, \"partialRender\" => true ) )", "click" => "function() { return customerProfileUpdate( \$(this) ); }"), "desinscrire" => array("label" => "Desinscrire", "imageUrl" => ($this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "request"), "baseUrl") . "/images/delete.png"), "visible" => "\$data->visibleDesinscrire <= 0", "url" => "Yii::App()->createUrl( \"/SAV/customerProfile\", array( \"id\" => \$data->primaryKey, \"status\" => 1 ) )", "click" => "function() { return toggleInscription( \$(this), 1 ); }"), "inscrire" => array("label" => "Reinscrire", "imageUrl" => ($this->getAttribute($this->getAttribute((isset($context["App"]) ? $context["App"] : null), "request"), "baseUrl") . "/images/delete.png"), "visible" => "\$data->visibleDesinscrire > 0", "url" => "Yii::App()->createUrl( \"/SAV/customerProfile\", array( \"id\" => \$data->primaryKey, \"status\" => 0 ) )", "click" => "function() { return toggleInscription( \$(this), 0 ); }")))))), "method");
    }

    public function getTemplateName()
    {
        return "//sav/customerProfile.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 11,  39 => 10,  35 => 8,  31 => 6,  29 => 5,  24 => 3,  19 => 1,);
    }
}
