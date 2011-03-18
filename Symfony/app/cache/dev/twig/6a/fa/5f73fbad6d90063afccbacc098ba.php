<?php

/* AcmeDemoBundle:Welcome:index.html.twig */
class __TwigTemplate_6afa5f73fbad6d90063afccbacc098ba extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'content' => array($this, 'block_content'),
        );
    }

    public function getParent(array $context)
    {
        if (null === $this->parent) {
            $this->parent = $this->env->loadTemplate("AcmeDemoBundle::layout.html.twig");
        }

        return $this->parent;
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_title($context, array $blocks = array())
    {
        echo "Symfony - Welcome";
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<h1>Welcome!</h1>
    <p>Congratulations! You have successfully installed a new Symfony application.</p>

    <div class=\"symfony-blocks-welcome\">
        <div class=\"block-quick-tour\">
            <div class=\"illustration\">
                <img src=\"";
        // line 13
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/acmedemo/images/welcome-quick-tour.gif"), "html");
        echo "\" alt=\"Quick tour\" />
            </div>
            <a class=\"symfony-button-green\" href=\"http://symfony.com/doc/2.0/quick_tour/index.html\">Read the Quick Tour</a>
        </div>
        <div class=\"block-configure\">
            <div class=\"illustration\">
                <img src=\"";
        // line 19
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/acmedemo/images/welcome-configure.gif"), "html");
        echo "\" alt=\"Configure your appication\" />
            </div>
            <a class=\"symfony-button-green\" href=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getPath("_configurator_home"), "html");
        echo "\">Configure</a>
        </div>
        <div class=\"block-demo\">
            <div class=\"illustration\">
                <img src=\"";
        // line 25
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/acmedemo/images/welcome-demo.gif"), "html");
        echo "\" alt=\"Demo\" />
            </div>
            <a class=\"symfony-button-green\" href=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getPath("_demo"), "html");
        echo "\">Run The Demo</a>
        </div>
    </div>

    <div class=\"symfony-blocks-help\">
        <div class=\"block-documentation\">
            <ul>
                <li><strong>Documentation</strong></li>
                <li><a href=\"http://symfony.com/doc/2.0/book/index.html\">The book</a></li>
                <li><a href=\"http://symfony.com/doc/2.0/reference/index.html\">The cookbook</a></li>
                <li><a href=\"http://symfony.com/doc/2.0/glossary/index.html\">Glossary</a></li>
            </ul>
        </div>
        <div class=\"block-documentation-more\">
            <ul>
                <li>&nbsp;</li>
                <li><a href=\"http://symfony.com/doc/2.0/contributing/index.html\">Contributing</a></li>
                <li><a href=\"http://trainings.sensiolabs.com\">Trainings</a></li>
                <li><a href=\"http://books.sensiolabs.com\">Books</a></li>
            </ul>
        </div>
        <div class=\"block-community\">
            <ul>
                <li><strong>Community</strong></li>
                <li><a href=\"http://symfony.com/irc\">IRC channel</a>
                <li><a href=\"http://symfony.com/mailing-lists\">Mailing lists</a></li>
                <li><a href=\"http://forum.symfony-project.org\">Forum</a></li>
                <li><a href=\"http://symfony.com/doc/2.0/contributing/index.html\">How to be involved</a></li>
                <li><a href=\"http://symfony.com/doc/2.0/contributing/index.html\">Contributing</a></li>
            </ul>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "AcmeDemoBundle:Welcome:index.html.twig";
    }
}
