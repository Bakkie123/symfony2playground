<?php

/* WebProfilerBundle:Collector:logger.html.twig */
class __TwigTemplate_04b8e758cdc9238c849ddfa1f5d892a0 extends Twig_Template
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
    <img width=\"15\" height=\"28\" alt=\"Logs\" style=\"vertical-align: middle; margin-right: 10px;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA8AAAAcCAYAAAC+lOV/AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAQVJREFUeNpi/P//PwO5gImBAjBwmlm8vLyOf/v2zYJYDVxcXCe2bdvmeu7cuS+M9vb2ZIWYoKDgUrKcvWLFipWfP38OYcEmeeDAgQtA6gMQCzg4OBigy0tISHxhYmJiYMFh+EIgBhlgAMXo4DEwffzH5ewLwAA5ADUAG/g7lBMJNkFgCO8fGJsJxTNezUTEM15nE4rnAQ4wkjVraWm9BlK/wc62tLR8fOXKFZmmpqYHhoaGT4Fif/Do/Q7Ep/bt28fz+/dvDkZgKdI4ZcqUmMOHD0t8+vSJi1gXKCgoPGQE5ixTIBuExUjwKsiSpyDNnECGOBCLAjEnkeEA8vMbxqFZ6AMEGADoe2NON2x5yQAAAABJRU5ErkJggg==\"/>
    <span>";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '6'), "counterrors", array(), "any", false, 6), "html");
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
        echo twig_escape_filter($this->env, $this->env->getExtension('templating')->getAssetUrl("bundles/webprofiler/images/profiler/logger.png"), "html");
        echo "\" alt=\"\" /></span>
    <strong>Logs</strong>
    <span class=\"count\">
        <span>";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'collector', '15'), "counterrors", array(), "any", false, 15), "html");
        echo "</span>
    </span>
</span>
";
    }

    // line 20
    public function block_panel($context, array $blocks = array())
    {
        // line 21
        echo "    <h2>Logs</h2>

    ";
        // line 23
        if ($this->getAttribute($this->getContext($context, 'collector', '23'), "logs", array(), "any", false, 23)) {
            // line 24
            echo "        <ul class=\"alt\">
            ";
            // line 25
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, 'collector', '25'), "logs", array(), "any", false, 25));
            foreach ($context['_seq'] as $context['i'] => $context['log']) {
                // line 26
                echo "                <li class=\"";
                echo ((twig_test_odd($this->getContext($context, 'i', '26'))) ? ("odd") : ("even"));
                if (("ERR" == $this->getAttribute($this->getContext($context, 'log', '26'), "priorityName", array(), "any", false, 26))) {
                    echo " error";
                }
                echo "\">
                    ";
                // line 27
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'log', '27'), "priorityName", array(), "any", false, 27), "html");
                echo "
                    ";
                // line 28
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, 'log', '28'), "message", array(), "any", false, 28), "html");
                echo "
                </li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['i'], $context['log'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 31
            echo "        </ul>
    ";
        } else {
            // line 33
            echo "        <em>No logs available.</em>
    ";
        }
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:logger.html.twig";
    }
}
