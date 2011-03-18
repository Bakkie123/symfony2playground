<?php

/* AcmeDemoBundle::layout.html.twig */
class __TwigTemplate_504321982487b363550069b6eaac8390 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <link rel=\"stylesheet\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/acmedemo/css/demo.css"), "html");
        echo "\" type=\"text/css\" media=\"all\" />
        <title>";
        // line 6
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        <link rel=\"shortcut icon\" href=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("favicon.ico"), "html");
        echo "\" />
    </head>
    <body>
        <div id=\"symfony-wrapper\">
            <div id=\"symfony-header\">
                <a href=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getPath("_welcome"), "html");
        echo "\">
                    <img src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/acmedemo/images/logo.gif"), "html");
        echo "\" alt=\"Symfony\">
                </a>
                <form id=\"symfony-search\" method=\"GET\" action=\"http://symfony.com/search\">
                    <label for=\"symfony-search-field\"><span>Search on Symfony Website</span></label>
                    <input name=\"q\" id=\"symfony-search-field\" type=\"text\" onfocus=\"if(this.value=='Search on Symfony website'){this.value='';}\" onblur=\"if(this.value==''){this.value='Search on Symfony website';}\" value=\"Search on Symfony website\" class=\"medium_txt\">
                    <input type=\"submit\" class=\"symfony-button-grey\" value=\"OK\" />
                </form>
            </div>

            ";
        // line 22
        if ($this->getAttribute($this->getAttribute($this->getContext($context, 'app', '22'), "session", array(), "any", false, 22), "flash", array("notice", ), "method", false, 22)) {
            // line 23
            echo "                <div class=\"flash-message\">
                    <em>Notice</em>: ";
            // line 24
            echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, 'app', '24'), "session", array(), "any", false, 24), "flash", array("notice", ), "method", false, 24), "html");
            echo "
                </div>
            ";
        }
        // line 27
        echo "
            <div class=\"symfony-content\">
                ";
        // line 29
        $this->displayBlock('content', $context, $blocks);
        // line 31
        echo "            </div>

            ";
        // line 33
        if (twig_test_defined("code", $context)) {
            // line 34
            echo "                <h2>Code behind this pages</h2>
                <div class=\"symfony-content\">";
            // line 35
            echo $this->getContext($context, 'code', '35');
            echo "</div>
            ";
        }
        // line 37
        echo "        </div>
    </body>
</html>
";
    }

    // line 6
    public function block_title($context, array $blocks = array())
    {
        echo "Demo Bundle";
    }

    // line 29
    public function block_content($context, array $blocks = array())
    {
        // line 30
        echo "                ";
    }

    public function getTemplateName()
    {
        return "AcmeDemoBundle::layout.html.twig";
    }
}
