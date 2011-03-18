<?php

/**
 * appdevUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appdevUrlMatcher extends Symfony\Component\Routing\Matcher\UrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(array $context = array(), array $defaults = array())
    {
        $this->context = $context;
        $this->defaults = $defaults;
    }

    public function match($url)
    {
        $url = $this->normalizeUrl($url);

        if (rtrim($url, '/') === '') {
            if (substr($url, -1) !== '/') {
                return array('_controller' => 'Symfony\Bundle\FrameworkBundle\Controller\RedirectController::urlRedirectAction', 'url' => $this->context['base_url'].$url.'/', 'permanent' => true, '_route' => '_welcome');
            }
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Acme\\DemoBundle\\Controller\\WelcomeController::indexAction',)), array('_route' => '_welcome'));
        }

        if ($url === '/_demo/demo') {
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::indexAction',)), array('_route' => '_demo'));
        }

        if (0 === strpos($url, '/_demo/hello') && preg_match('#^/_demo/hello/(?P<name>[^/\.]+?)$#x', $url, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::helloAction',)), array('_route' => '_demo_hello'));
        }

        if ($url === '/_demo/contact') {
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Acme\\DemoBundle\\Controller\\DemoController::contactAction',)), array('_route' => '_demo_contact'));
        }

        if (0 === strpos($url, '/_wdt') && preg_match('#^/_wdt/(?P<token>[^/\.]+?)$#x', $url, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::toolbarAction',)), array('_route' => '_wdt'));
        }

        if ($url === '/_profiler/search') {
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::searchAction',)), array('_route' => '_profiler_search'));
        }

        if ($url === '/_profiler/purge') {
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::purgeAction',)), array('_route' => '_profiler_purge'));
        }

        if ($url === '/_profiler/import') {
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::importAction',)), array('_route' => '_profiler_import'));
        }

        if (0 === strpos($url, '/_profiler/export') && preg_match('#^/_profiler/export/(?P<token>[^/\.]+?)\.txt$#x', $url, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::exportAction',)), array('_route' => '_profiler_export'));
        }

        if (0 === strpos($url, '/_profiler') && preg_match('#^/_profiler/(?P<token>[^/\.]+?)/search/results$#x', $url, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::searchResultsAction',)), array('_route' => '_profiler_search_results'));
        }

        if (0 === strpos($url, '/_profiler') && preg_match('#^/_profiler/(?P<token>[^/\.]+?)$#x', $url, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::panelAction',)), array('_route' => '_profiler'));
        }

        if (0 === strpos($url, '/_profiler') && preg_match('#^/_profiler/(?P<token>[^/\.]+?)/(?P<panel>[^/\.]+?)$#x', $url, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebProfilerBundle\\Controller\\ProfilerController::panelAction',)), array('_route' => '_profiler_panel'));
        }

        if (rtrim($url, '/') === '/_configurator') {
            if (substr($url, -1) !== '/') {
                return array('_controller' => 'Symfony\Bundle\FrameworkBundle\Controller\RedirectController::urlRedirectAction', 'url' => $this->context['base_url'].$url.'/', 'permanent' => true, '_route' => '_configurator_home');
            }
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Symfony\\Bundle\\WebConfiguratorBundle\\Controller\\ConfiguratorController::checkAction',)), array('_route' => '_configurator_home'));
        }

        if (0 === strpos($url, '/_configurator/step') && preg_match('#^/_configurator/step/(?P<index>[^/\.]+?)$#x', $url, $matches)) {
            return array_merge($this->mergeDefaults($matches, array (  '_controller' => 'Symfony\\Bundle\\WebConfiguratorBundle\\Controller\\ConfiguratorController::stepAction',)), array('_route' => '_configurator_step'));
        }

        if ($url === '/_configurator/final') {
            return array_merge($this->mergeDefaults(array(), array (  '_controller' => 'Symfony\\Bundle\\WebConfiguratorBundle\\Controller\\ConfiguratorController::finalAction',)), array('_route' => '_configurator_final'));
        }

        return false;
    }
}
