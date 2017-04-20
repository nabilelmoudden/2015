<?php

/* //fr_rinalda/nml/nml/nmlldv.html */
class __TwigTemplate_00e9e617a398eaffa3efd76336d59489 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'arr' => array($this, 'block_arr'),
            'assen' => array($this, 'block_assen'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->env->getExtension('TwigEsoterExt')->getProductTemplate());
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 7
    public function block_arr($context, array $blocks = array())
    {
        // line 8
        echo "
    ";
        // line 9
        echo (("body { background: #000 url( " . $this->env->getExtension('TwigEsoterExt')->productDir()) . "/images/fond_web.jpg) center 0 repeat-y; }");
        echo "

";
    }

    // line 15
    public function block_assen($context, array $blocks = array())
    {
        // line 16
        echo "
        <img src=\"";
        // line 17
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/bouton_ascenseur.png\"  alt=\"\">

      ";
    }

    // line 21
    public function block_content($context, array $blocks = array())
    {
        // line 22
        echo "


        <p>

            

        ";
        // line 29
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "creationDate");
        echo "

        <br />

        <br />

        <p>

        \t";
        // line 37
        echo Yii::t("site", "Redirect");
        echo "

            ";
        // line 39
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "email");
        echo "

                

        </p>

          <p style=\"color:blue; font-weight:bold;\">

       

        <br>

        

        ";
        // line 53
        echo Yii::t((isset($context["site"]) ? $context["site"] : null), "Redirect");
        echo "

    </p>

        \t";
        // line 57
        echo $this->env->getExtension('TwigEsoterExt')->mois($this->env->getExtension('TwigEsoterExt')->getSD(), 2);
        echo " After giving the winning lottery numbers 

            67 times in a row...

            ";
        // line 61
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "firstName");
        echo ", you are the last person that I have decided to help win for the month of  before I stop forever...

        </p>

        <p>

        \tDear ";
        // line 67
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "firstName");
        echo ", who is in need of money,

        </p>

        

        <p>

        \tIn a few days I will, for you, question the future in order to see the numbers that should be picked for one of the biggest lottery drawings of the month of . Then I will send them to you.

        </p>

        

        <p>

        \tI will be performing this psychic prediction of numbers to play for the last time. Then, I will stop forever.

        </p>

        

        <p>

        \tI am going to tell you why. But first, I will tell you why I have selected you personally.

        </p>

        

        <p>

        \tI have conducted some research. I have seen thousands of applications. People who work hard just to earn enough to get by. Families in desperate financial situations. Single women forced to fight insurmountable worries and problems.

        </p>

        

        <p>

        \tI can tell you, the choice was not easy. But I chose you ";
        // line 107
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "firstName");
        echo ", to receive my latest numbers to play for the month of , with no commitment necessary from you.

        </p>

        

        <p>

        \tI can help you start a new, much better life. I have the power to do it. And I will prove it to you.

        </p>

        

        <p>

        \tBut before I do, you must know who I am and how I am going to to do it, when no one has managed to help you win before now. 

        </p>

        

        <p>

        \tFirst of all, here are the \"headlines\" about me in recent months:

        </p>

        

        <p>

        \tI have been seen and heard by thousands of people on TV and the radio, where I had very precise \"visions\" of people I had never met before.

        </p>

        

        <p>

        \tI have precisely described how they looked, what their lives were like, what they did for a living, their type of difficulties, the hardships they were experiencing and what was going to happen to them...

        </p>

        

        <p>

        \tBut that's not all. Over many years, I have traveled to the four corners of the world to demonstrate what could be done with the powers of the mind (predicting the future of someone using a personal object, making objects move, intervening in the course of events, predicting numbers before they come out...).

        </p>

        

        <p>

        \tSpecialists have studied my powers very seriously in order to verify if my strange \"flashes\" really allowed me to predict winning lottery numbers. They were literally stunned by the results.

        </p>

        

        <p>

        \tEveryone talks more and more about my powers and the numbers to play that I am able to predict, as well as the winnings I have helped people obtain.

        </p>

        

        <p>

        \tSince then, more and more requests have come to me from people who have serious money problems.

        </p>

        

        <p>

        \tBut! Predicting numbers requires intense concentration from me. It is so challenging that I am literally exhausted after a psychic prediction. So much so that it takes me over a week of rest to get back to normal after my efforts.

        </p>

        

        <p>

        \tThe same goes for my health. That's why I have decided to stop.

        </p>

        

        <p>

        \tBut before I do, I wanted to use my clairvoyant gifts to better understand you and to be sure of the worthy person that I sensed in you.

        </p>

        

        <p>

        \t<u><b>What I \"saw\" moved me deeply</b></u>:

        </p>



    <img src=\"";
        // line 217
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/aasha_signature.png\"  /> <br />

    <br />

    <br />

    <p>P.S. : ";
        // line 223
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "firstName");
        echo ",respond quickly, before it's too late. Because I know that winning a lot of money quickly would be very helpful for you. </p>

            <p align=\"center\"> 

            <a href=\"#BDC\" onClick=\"document.getElementById('BDC').style.display = 'block'\"> 

            <img src=\"";
        // line 229
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/bouton_valide.gif\" border=\"0\"  alt=\"0\" onMouseOver=\"javascript:this.src = '";
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/bouton_valide.gif';\" onmouseout= \"javascript:this.src = '";
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/bouton_valide.gif';\" style=\" cursor:pointer;\"> </a>

        </p>

 \t<table  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tabbdc\" id=\"BDC\" >

            <tr>

                <td width=\"279\" ><img src=\"";
        // line 237
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/img_bdc_haut.gif\" width=\"500\" alt=\"\"  /></td>

            </tr>

            <tr>

                <td  class=\"bdc_pad\">\t

                <br>

                        <p class=\"center\">

                            strictly reserved for: ";
        // line 249
        echo $this->getAttribute($this->getAttribute((isset($context["this"]) ? $context["this"] : null), "getUser", array(), "method"), "firstName");
        echo "

                        </p>

                        <br>

                        <p> 

                           <b class=\"txt20\">Yes, Aasha,</b> I have noted that after having given the lottery numbers 67 times in a row, you want to help me win one of the biggest lottery drawings for the month of  before you stop forever.

                            Also, I would like to thank you for choosing me personally to receive your latest numbers to play.

                        </p>

                        <br>

                        <p>

                           So here is my personal voucher with the information you requested, i.e., my birthdate and the amount I would like to win sometime in the month of . 

                        </p>

                        <br>

                        <p>

                        \tI would like to win <input type=\"text\" name=\"somme\" style=\"width:80px; margin-left:0px;\"> inr sometime in the month of .

                        </p>

                        <br>

                        <p class=\"cadre_bdc_p1\"> 

                        I will validate my voucher right away so that you can email me my Personal Numbers to Play for the month of  as soon as possible. I will attach my flat-rate participation fee of <?php echo formatPriceDevise(\$txt_tarif_apres_reduction,\$site,l); ?> by clicking on this link:

                        </p>

                    </td>

                    <br>

                    </tr>

                    <tr>

                \t<td>

                    <br>

                     ";
        // line 299
        echo (isset($context["BDC"]) ? $context["BDC"] : null);
        echo "

                    

                </td>

            </tr>

            <tr>

                <td>

                <br>

                <p align=\"center\">Your Total Guarantee</p>

         \t\t <p class=\"txt12\">

                   In a few days, you will receive your <u>Personal Numbers to Play</u> that I am giving to someone for the last time, in this case, you, <?php echo \$prenom;?>. 

                    Use them immediately and you can win a significant amount of money sometime in the month of . If you feel what you have won is not enough (which has never happened), 

                    just let me know by email in order to receive a full refund. This refund will be without a single question and without any conditions.  

                </p>

                </td>

            </tr>

            <tr>

                <td>

                    <img src=\"";
        // line 333
        echo $this->env->getExtension('TwigEsoterExt')->productDir();
        echo "/images/img_bdc_bas_ct.gif\"  alt=\"\"  width=\"500\">

                </td>

            </tr>

        </table>

   

";
    }

    public function getTemplateName()
    {
        return "//fr_rinalda/nml/nml/nmlldv.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  416 => 333,  379 => 299,  326 => 249,  311 => 237,  296 => 229,  287 => 223,  278 => 217,  165 => 107,  122 => 67,  113 => 61,  106 => 57,  99 => 53,  82 => 39,  77 => 37,  66 => 29,  57 => 22,  54 => 21,  47 => 17,  44 => 16,  41 => 15,  34 => 9,  31 => 8,  28 => 7,);
    }
}
