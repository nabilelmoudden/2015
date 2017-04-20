<?php

/* //js/jquery_easing_scrol_hid.php */
class __TwigTemplate_6ad0e683db23f872d06f8d8eca283290 extends Twig_Template
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
<!--<script type=\"text/javascript\" src=\"/lib/libjs/jquery_easing.js\"></script>\t-->

<script type=\"text/javascript\">
\$('#fl_bouton').hide();

//config

\$float_speed=1500; //milliseconds
\$float_easing=\"easeOutQuint\";
\$bouton_fade_speed=500; //milliseconds
\$closed_bouton_opacity=0.75;

//cache vars
\$fl_bouton=\$(\"#fl_bouton\");
\$fl_bouton_bouton=\$(\"#fl_bouton .bouton\");

\$(document).ready(function(){
\tboutonPosition=\$('#fl_bouton').position().top;
\tFloatBouton();
});
\$(window).scroll(function () { 
\tFloatBouton();
});

function FloatBouton(){
\tvar scrollAmount=\$(document).scrollTop();
\tif (scrollAmount >= <?php echo isset(\$scrollTopShow)?\$scrollTopShow:1700;?> && scrollAmount <= <?php echo isset(\$scrollTopShow)?\$scrollBottomShow:7500;?>){
\t\t\$fl_bouton.show();
\t}else{
\t\t\$fl_bouton.hide();
\t}
\tvar newPosition=boutonPosition+scrollAmount;
\tif(\$(window).height()<\$fl_bouton.height()+\$fl_bouton_bouton.height()){
\t\t\$fl_bouton.css(\"top\",boutonPosition);
\t} else {
\t\t\$fl_bouton.stop().animate({top: newPosition}, \$float_speed, \$float_easing);
\t}
};
</script>


";
    }

    public function getTemplateName()
    {
        return "//js/jquery_easing_scrol_hid.php";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
