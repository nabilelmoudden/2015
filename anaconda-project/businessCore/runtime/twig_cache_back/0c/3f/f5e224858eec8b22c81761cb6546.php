<?php

/* //js/ldv_ascenseur.js */
class __TwigTemplate_0c3ff5e224858eec8b22c81761cb6546 extends Twig_Template
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
configvar float_speed\t\t\t\t= 1500;
millisecondsvar float_easing\t\t\t= 'easeOutQuint';var bouton_fade_speed\t\t= 500; 
millisecondsvar closed_bouton_opacity\t= 0.75;var posBDC\t\t\t\t\t= false;\$().ready(function(){\t
 Position du BDC :\tposBDC = ( document.getElementById('BDC') ) ? \$('#BDC').position().top : \$('body').height();\t \t\$(\".buttonHover\").bind(\t{\t\tmouseenter: function()\t\t{\t\t\tvar bckgrnd = \$(this).children().attr(\"src\");\t\t\tif (bckgrnd.search(\"_clicked\") < 0 && bckgrnd.search(\"_out\") > 0)\t\t\t{\t\t\t\tvar overbckgrnd = bckgrnd.replace(\"_out\",\"_over\");\t\t\t\t\$(this).children().attr(\"src\",overbckgrnd);\t\t\t}\t\t},\t\tmouseleave: function()\t\t{\t\t\tvar bckgrnd = \$(this).children().attr(\"src\");\t\t\tif (bckgrnd.search(\"_clicked\") < 0 && bckgrnd.search(\"_over\") > 0)\t\t\t{\t\t\t\tvar outbckgrnd = bckgrnd.replace(\"_over\",\"_out\");\t\t\t\t\$(this).children().attr(\"src\",outbckgrnd);\t\t\t}\t\t}\t});\t\$(\".choiceClick\").bind(\"click\",function()\t{\t\tvar bckgrnd2 = \$(\".choiceClick\").not(\$(this)).children().attr(\"src\");\t\tif (bckgrnd2.search(\"_clicked\") > 0)\t\t{\t\t\tvar outbckgrnd2 = bckgrnd2.replace(\"_clicked\",\"_out\");\t\t\t\$(\".choiceClick\").not(\$(this)).children().attr(\"src\",outbckgrnd2);\t\t}\t\tvar bckgrnd = \$(this).children().attr(\"src\");\t\tif (bckgrnd.search(\"_clicked\") < 0)\t\t{\t\t\tvar clickedbckgrnd = bckgrnd.replace(\"_out\",\"_over\");\t\t\tclickedbckgrnd = clickedbckgrnd.replace(\"_over\",\"_clicked\");\t\t\t\$(this).children().attr(\"src\",clickedbckgrnd);\t\t}\t});\t\$('#fl_bouton').hide();\t\$(document).ready(function(){\t\tboutonPosition=\$('#fl_bouton').position().top;\t\tFloatBouton();\t});\t\$(window).scroll(function () {\t\tFloatBouton();\t});});function FloatBouton(){\tvar scrollAmount=\$(document).scrollTop();\tif (scrollAmount >= 300 && scrollAmount <= posBDC)\t\t\$(\"#fl_bouton\").show();\telse\t\t\$(\"#fl_bouton\").hide();\tvar newPosition=boutonPosition+scrollAmount;\tif(\$(window).height()<\$(\"#fl_bouton\").height()+\$(\"#fl_bouton .bouton\").height())\t\t\$(\"#fl_bouton\").css(\"top\", boutonPosition);\telse\t\t\$(\"#fl_bouton\").stop().animate( {top: newPosition}, float_speed, float_easing );}";
    }

    public function getTemplateName()
    {
        return "//js/ldv_ascenseur.js";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,  80 => 21,  77 => 20,  73 => 12,  70 => 11,  65 => 5,  59 => 24,  56 => 23,  54 => 20,  48 => 17,  42 => 13,  40 => 11,  34 => 7,  32 => 5,  28 => 3,  26 => 2,  22 => 1,);
    }
}
