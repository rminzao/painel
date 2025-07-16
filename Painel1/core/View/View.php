<?php

namespace Core\View;

class View
{
    /** @var array */
    private array $data = [];

    /**
     * @param array $data
     * @return void
     */
    public function init($data = [])
    {
        $this->data = $data;
    }

    /**
     * Set directives to blade template
     *
     * @param Blade $blade
     * @return void
     */
    protected function directives(Blade $blade)
    {
        $blade->directive('lang', function ($expression) {
            return "<?php echo __({$expression}); ?>";
        });

        $blade->directive('dump', function ($expression) {
            return "<?php echo dd({$expression}); ?>";
        });
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function render($view, $data = [])
    {
        //blade instance
        $blade = new Blade(
            realpath(__DIR__ . '/../../resources/views'),
            realpath(__DIR__ . '/../../storage/framework/views')
        );
        
        //get custom directives
        $this->directives($blade);

        //return the rendered view
        return $blade->make($view, $data, $this->data)->render();
    }
}
