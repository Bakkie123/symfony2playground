<?php

/* WebProfilerBundle:Collector:exception.html.twig */
class __TwigTemplate_810d52f0590cc6e26d2fb8092e6180eb extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'menu' => array($this, 'block_menu'),
            'panel' => array($this, 'block_panel'),
        );
    }

    public function getParent(array $context)
    {
        if (null === $this->parent) {
            $this->parent = $this->env->loadTemplate("WebProfilerBundle:Profiler:layout.html.twig");
        }

        return $this->parent;
    }

    public function display(array $context, array $blocks = array())
    {
        $context = array_merge($this->env->getGlobals(), $context);

        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        // line 4
        echo "    <link href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/framework/css/exception.css"), "html");
        echo "\" rel=\"stylesheet\" type=\"text/css\" media=\"screen\" />
    ";
        // line 5
        echo twig_escape_filter($this->env, $this->renderParentBlock("head", $context, $blocks), "html");
        echo "    
";
    }

    // line 8
    public function block_menu($context, array $blocks = array())
    {
        // line 9
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 10
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/webprofiler/images/profiler/exception.png"), "html");
        echo "\" alt=\"\" /></span>
    <strong>Exception</strong>
    <span class=\"count\">
        ";
        // line 13
        if ($this->getAttribute($this->getContext($context, 'collector', '13'), "hasexception", array(), "any", false, 13)) {
            // line 14
            echo "            <span>1</span>
        ";
        }
        // line 16
        echo "    </span>
</span>
";
    }

    // line 20
    public function block_panel($context, array $blocks = array())
    {
        // line 21
        echo "    <h2>Exception</h2>

    ";
        // line 23
        if ((!$this->getAttribute($this->getContext($context, 'collector', '23'), "hasexception", array(), "any", false, 23))) {
            // line 24
            echo "        <em>No exception was thrown and uncaught during the request.</em>
    ";
        } else {
            // line 26
            echo "        ";
            echo $this->env->getExtension('templating')->renderAction("WebProfilerBundle:Exception:show", array("exception" => $this->getAttribute($this->getContext($context, 'collector', '26'), "exception", array(), "any", false, 26), "format" => "html"), array());
            // line 27
            echo "    ";
        }
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:exception.html.twig";
    }
}
