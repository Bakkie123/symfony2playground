<?php

/* SymfonyWebConfiguratorBundle::final.html.twig */
class __TwigTemplate_db2b4b13d2359336e788cd6b05048c73 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'content_class' => array($this, 'block_content_class'),
            'content' => array($this, 'block_content'),
        );
    }

    public function getParent(array $context)
    {
        if (null === $this->parent) {
            $this->parent = $this->env->loadTemplate("SymfonyWebConfiguratorBundle::layout.html.twig");
        }

        return $this->parent;
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content_class($context, array $blocks = array())
    {
        echo "config_done";
    }

    // line 4
    public function block_content($context, array $blocks = array())
    {
        // line 5
        echo "    <h1>Well done!</h1>
    <h2>Your distribution is configured!</h2>

    <h3>
        <span>
            ";
        // line 10
        if ($this->getContext($context, 'is_writable', '10')) {
            // line 11
            echo "                Your parameter.ini has been overwritten with these parameters:
            ";
        } else {
            // line 13
            echo "                Your parameters.ini file is not writeable ! Here are the parameters you can copy and paste.
            ";
        }
        // line 15
        echo "        </span>
    </h3>

    <textarea class=\"symfony-configuration\">";
        // line 18
        echo twig_escape_filter($this->env, $this->getContext($context, 'parameters', '18'), "html");
        echo "</textarea>

    <ul>
        <li><a href=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getPath("_welcome"), "html");
        echo "\">Go to the Welcome page</a></li>
    </ul>
";
    }

    public function getTemplateName()
    {
        return "SymfonyWebConfiguratorBundle::final.html.twig";
    }
}
