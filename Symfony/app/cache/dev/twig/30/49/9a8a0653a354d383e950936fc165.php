<?php

/* AcmeDemoBundle:Demo:hello.html.twig */
class __TwigTemplate_30499a8a0653a354d383e950936fc165 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
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

        // line 17
        $context['code'] = $this->env->getExtension('demo')->getCode($this);
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_content($context, array $blocks = array())
    {
        // line 3
        echo "    ";
        echo twig_escape_filter($this->env, $this->renderParentBlock("content", $context, $blocks), "html");
        echo "

    <p>
        Hello Big ";
        // line 6
        echo twig_escape_filter($this->env, $this->getContext($context, 'name', '6'), "html");
        echo "!
    </p>

    ";
        // line 10
        echo "    ";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'user', '10'), "name", array(), "any", false, 10), "html");
        echo "




";
    }

    public function getTemplateName()
    {
        return "AcmeDemoBundle:Demo:hello.html.twig";
    }
}
