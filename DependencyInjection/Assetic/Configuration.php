<?php
/**
 * User  : Nikita.Makarov
 * Date  : 2/19/15
 * Time  : 4:03 AM
 * E-Mail: nikita.makarov@effective-soft.com
 */

namespace Akuma\Bundle\BootswatchBundle\DependencyInjection\Assetic;


class Configuration
{
    /**
     * Builds the assetic configuration.
     *
     * @param array $config
     *
     * @return array
     */
    public function build(array $config)
    {
        // Fix path in output dir
        $config['output_dir'] = $this->normalizePath($config['output_dir']);
        $config['output_dir'] = trim($config['output_dir'], DIRECTORY_SEPARATOR);
        if (!strlen($config['output_dir'])) {
            unset($config['output_dir']);
        }
        $output = array();
        if (in_array($config['less_filter'], array('sass', 'scssphp'))) {
            $output += $this->buildCssWithSass($config);
        } elseif ('none' !== $config['less_filter']) {
            $output += $this->buildCssWithLess($config);
        } else {
            $output += $this->buildCss($config);
        }

        $output['bootstrap_css']['output'] = $this->normalizePath(isset($config['output_dir']) ? array($config['output_dir'], 'css/bootstrap.css') : 'css/bootstrap.css');
        if ($config['font_awesome']) {
            $output['font_awesome_css']['output'] = $this->normalizePath(isset($config['output_dir']) ? array($config['output_dir'], 'css/font-awesome.css') : 'css/font-awesome.css');
        }
        $output += $this->buildJs($config);
        return $output;
    }

    /**
     * @param string|array
     *
     * @return mixed
     */
    protected function normalizePath()
    {
        if (func_num_args() <= 0) {
            return null;
        }

        if (func_num_args() > 1) {
            return call_user_func_array(array($this, __FUNCTION__), array(implode(DIRECTORY_SEPARATOR, func_get_args())));
        }
        if ((func_num_args() == 1) && (is_array(func_get_arg(0)))) {
            return call_user_func_array(array($this, __FUNCTION__), array(implode(DIRECTORY_SEPARATOR, func_get_arg(0))));
        }
        $_in = func_get_arg(0);
        $_out = str_replace(array('\\', '/'), DIRECTORY_SEPARATOR, $_in);
        $_out = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $_out);
        $_out = str_replace(DIRECTORY_SEPARATOR, '/', $_out); //??
        return $_out;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function buildCssWithLess(array $config)
    {
        $less_outfile = $this->normalizePath(dirname(dirname(dirname(__FILE__))), 'Resources', 'build', 'bootswatch.less');
        $inputs = array(
            $less_outfile
        );

        $output = array();
        $output['bootstrap_css'] = array(
            'inputs' => $inputs,
            'filters' => array(
                "exposed",
                $config['less_filter']
            ),
        );
        $output['font_awesome_css'] = array(
            'inputs' => array(
                $this->normalizePath($config['bootswatch']['path'], 'bower_components/font-awesome/less/font-awesome.less'),
            ),
            'filters' => array(
                "exposed",
                $config['less_filter']
            ),
        );
        return $output;
    }

    protected function buildCssWithSass($config)
    {
        $less_outfile = $this->normalizePath(dirname(dirname(dirname(__FILE__))), 'Resources', 'build', 'bootswatch.scss');
        $inputs = array(
            $less_outfile
        );

        $output = array();
        $output['bootstrap_css'] = array(
            'inputs' => $inputs,
            'filters' => array(
                "exposed",
                $config['less_filter']
            ),
        );
        $output['font_awesome_css'] = array(
            'inputs' => array(
                $this->normalizePath($config['bootswatch']['path'], 'bower_components/font-awesome/scss/font-awesome.scss'),
            ),
            'filters' => array(
                "exposed",
                $config['less_filter']
            ),
        );
        return $output;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function buildCss(array $config)
    {
        $output = array();
        $output['bootstrap_css'] = array(
            'inputs' => array(
                $this->normalizePath(implode(DIRECTORY_SEPARATOR, array(
                    $config['bootswatch']['path'],
                    $config['bootswatch']['theme'],
                    'bootstrap.css'
                )))
            ),
            'filters' => array('cssrewrite'),
        );
        if ($config['font_awesome']) {
            $output['font_awesome_css'] = array(
                'inputs' => array(
                    $this->normalizePath(implode(DIRECTORY_SEPARATOR, array(
                        $config['bootswatch']['path'],
                        'bower_components/font-awesome/css/font-awesome.css'
                    )))
                ),
                'filters' => array('cssrewrite'),
            );
        }
        return $output;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function buildJs(array $config)
    {
        $output = array();

        $output['bootstrap_js'] = $this->buildBootstrapJs($config);
        $output['jquery_js'] = $this->buildJquery($config);

        return $output;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function buildBootstrapJs(array $config)
    {
        $path = !in_array($config['less_filter'], array('sass', 'scssphp')) ? "bower_components/bootstrap/js" : "bower_components/bootstrap-sass-official/assets/javascripts/bootstrap";

        return array(
            'inputs' => array(
                $this->normalizePath($config['bootswatch']['path'], $path, '/transition.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/alert.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/button.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/carousel.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/collapse.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/dropdown.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/modal.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/tooltip.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/popover.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/scrollspy.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/tab.js'),
                $this->normalizePath($config['bootswatch']['path'], $path, '/affix.js'),
            ),
            'output' => $this->normalizePath(isset($config['output_dir']) ? array($config['output_dir'], 'js/bootstrap.js') : 'js/bootstrap.js')
        );
    }

    /**
     * @param array $config
     *
     * @return array
     */
    protected function buildJquery(array $config)
    {
        if (isset($config['jquery_path'])) {
            return array(
                'inputs' => array($config['jquery_path']),
                'output' => $this->normalizePath(isset($config['output_dir']) ? array($config['output_dir'], 'js/jquery.js') : 'js/jquery.js')
            );
        } else {
            return array(
                'inputs' => array(
                    $this->normalizePath($config['bootswatch']['path'], 'bower_components/jquery/dist/jquery.js'),
                ),
                'output' => $this->normalizePath(isset($config['output_dir']) ? array($config['output_dir'], 'js/jquery.js') : 'js/jquery.js')
            );
        }
    }
}