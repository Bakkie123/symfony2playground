<?php

/* WebProfilerBundle:Collector:timer.html.twig */
class __TwigTemplate_825e60a300cb91e0333d794fcc6803e5 extends Twig_Template
{
    protected $parent;

    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'toolbar' => array($this, 'block_toolbar'),
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
    <img width=\"16\" height=\"28\" alt=\"Timers\" style=\"vertical-align: middle; margin-right: 10px;\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAcCAYAAABoMT8aAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAiNJREFUeNpi/P//PwMlgImBQjDwBrCcO3cOq0RRUdF3ZH5fXx8nTVzAePbsWcq8gMwxMjJiSUlJcXv9+nXm169fbf78+SMAVsTC8paXl3ePmJjYjJkzZx4GevsviheAGhmBguL+/v4779y5s/Xjx48+MM0gAGQLv3//PvzmzZv7AwMD19y+fVsEpAfsBWBCYly8eLHcsmXLjnz//l2GGGcDXXM1IyPD2dvb+xXIBTwbN25chU3zgQMHwBgdfP78WXvp0qVzgUwuprq6utg3b96YkRp4z549854wYYI7071791LJjYFLly7lM7148UKHXAOALtdnAYYwCyGFyOHg4OAAZ3/69ImfopTIzMz8j4WVlfXf79+/sRqEbBs2wMfH94tJXV39DbkuUFFReclkb29/jlwDPD09jzGFhoZu0NTU/EKqZktLyzdOTk7bQX4/U1tbu1pcXPwvsZoVFBR+lZeXLwUyz4MMuCMlJbWmv79/o56e3k9Cms3MzL5PmjRphYCAwCYg9wE4MwEZwkBsDsReO3fudN+zZ4/shQsX2ICxA9bEzs7OYGBg8NPHx+eBra3tdqDQVpDLgfgjuEABZk2QS3hBAQvExkBsAHIpMAsLAOP6PzC63gP590FOBmJQCXQPiL8Ai4D/KCUS0CBWIAUqB8SAWAiIQeUgqOIAlY/vgPgVEH8AavyDtUQCSoDc/BqEoQUGLIH9A9mGtUwc8JoJIMAAS9XemfR7crQAAAAASUVORK5CYII=\"/>
    ";
        // line 6
        echo twig_escape_filter($this->env, sprintf("%.0f", ($this->getAttribute($this->getContext($context, 'collector', '6'), "time", array(), "any", false, 6) * 1000)), "html");
        echo " ms
</span>
";
    }

    public function getTemplateName()
    {
        return "WebProfilerBundle:Collector:timer.html.twig";
    }
}
