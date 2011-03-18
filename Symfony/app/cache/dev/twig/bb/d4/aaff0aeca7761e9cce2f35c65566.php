<?php

/* DoctrineBundle:Collector:db.html.twig */
class __TwigTemplate_bbd4aaff0aeca7761e9cce2f35c65566 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
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
    public function block_toolbar($context, array $blocks = array())
    {
        // line 4
        echo "<span style=\"white-space:nowrap; color:#2f2f2f; display:inline-block; min-height:24px; border-right:1px solid #cdcdcd; padding:5px 10px 5px 6px; \">
    <img width=\"20\" height=\"28\" alt=\"Database\" style=\"vertical-align: middle; margin-right: 10px;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAcCAYAAABh2p9gAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAQRJREFUeNpi/P//PwM1ARMDlcGogZQDlpMnT7pxc3NbA9nhQKxOpL5rQLwJiPeBsI6Ozl+YBOOOHTv+AOllQNwtLS39F2owKYZ/gRq8G4i3ggxEToggWzvc3d2Pk+1lNL4fFAs6ODi8JzdS7mMRVyDVoAMHDsANdAPiOCC+jCQvQKqBQB/BDbwBxK5AHA3E/kB8nKJkA8TMQBwLxaBIKQbi70AvTADSBiSadwFXpCikpKQU8PDwkGTaly9fHFigkaKIJid4584dkiMFFI6jkTJII0WVmpHCAixZQEXWYhDeuXMnyLsVlEQKI45qFBQZ8eRECi4DBaAlDqle/8A48ip6gAADANdQY88Uc0oGAAAAAElFTkSuQmCC\"/>
    <span style=\"color: ";
        // line 6
        echo ((($this->getAttribute($this->getContext($context, 'collector', '6'), "querycount", array(), "any", false, 6) < 10)) ? ("#000") : ("#d22"));
        echo "\" title=\"";
        echo twig_escape_filter($this->env, sprintf("%0.2f", ($this->getAttribute($this->getContext($context, 'collector', '6'), "time", array(), "any", false, 6) * 1000)), "html");
        echo " ms\">";
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '6'), "querycount", array(), "any", false, 6), "html");
        echo "</span>
</span>
";
    }

    // line 10
    public function block_menu($context, array $blocks = array())
    {
        // line 11
        echo "<span class=\"label\">
    <span class=\"icon\"><img src=\"";
        // line 12
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/doctrine/images/profiler-menu.png"), "html");
        echo "\" alt=\"\" /></span>
    <strong>Doctrine</strong>
    <span class=\"count\">
        <span>";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '15'), "querycount", array(), "any", false, 15), "html");
        echo "</span>
        <span>";
        // line 16
        echo twig_escape_filter($this->env, sprintf("%0.0f", ($this->getAttribute($this->getContext($context, 'collector', '16'), "time", array(), "any", false, 16) * 1000)), "html");
        echo " ms</span>
    </span>
</span>
";
    }

    // line 21
    public function block_panel($context, array $blocks = array())
    {
        // line 22
        echo "    <h2>Queries</h2>

    ";
        // line 24
        if ((!$this->getAttribute($this->getContext($context, 'collector', '24'), "querycount", array(), "any", false, 24))) {
            // line 25
            echo "        <p>
            <em>No queries.</em>
        </p>
    ";
        } else {
            // line 29
            echo "        <ul class=\"alt\">
            ";
            // line 30
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'collector', '30'), "queries", array(), "any", false, 30));
            foreach ($context['_seq'] as $context['i'] => $context['query']) {
                // line 31
                echo "                <li class=\"";
                echo ((twig_test_odd($this->getContext($context, 'i', '31'))) ? ("odd") : ("even"));
                echo "\">
                    <div>
                        <code>";
                // line 33
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'query', '33'), "sql", array(), "any", false, 33), "html");
                echo "</code>
                    </div>
                    <small>
                        <strong>Parameters</strong>: ";
                // line 36
                echo twig_escape_filter($this->env, $this->env->getExtension('templating')->yamlEncode($this->getAttribute($this->getContext($context, 'query', '36'), "params", array(), "any", false, 36)), "html");
                echo "<br />
                        <strong>Time</strong>: ";
                // line 37
                echo twig_escape_filter($this->env, sprintf("%0.2f", ($this->getAttribute($this->getContext($context, 'query', '37'), "executionMS", array(), "any", false, 37) * 1000)), "html");
                echo " ms
                    </small>
                </li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['i'], $context['query'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 41
            echo "        </ul>
    ";
        }
    }

    public function getTemplateName()
    {
        return "DoctrineBundle:Collector:db.html.twig";
    }
}
