<?php

namespace TemplateEditor;

use Nette\Caching\Storages\PhpFileStorage,
        Nette\Templating\FileTemplate,
        Nette\Latte\Engine,
        Nette\DI\Container;

class Panel extends \Nette\Object implements \Nette\Diagnostics\IBarPanel
{
	const XHR_HEADER = 'TemplateEditorPanel';

	/** @var Container */
	protected $container;

        /** @var string */
        protected $file;


        public function __construct(Container $container)
	{
		$this->container = $container;
                $this->handleRequest();
	}


	/**
	 * Returns the code for the panel tab.
	 * @return string
	 */
	public function getTab()
	{
		ob_start();
		require __DIR__ . '/tab.latte';
		return ob_get_clean();
	}


	/**
	 * Returns the code for the panel itself.
	 * @return string
	 */
	public function getPanel()
	{
		$container = $this->container;

                $template = new FileTemplate;
                $template->setFile(__DIR__ . '/panel.latte');
                $template->onPrepareFilters[] = function($template) {
                    $template->registerFilter(new Engine);
                };
                $template->registerHelperLoader('\Nette\Templating\Helpers::loader');
                $template->setCacheStorage(new PhpFileStorage($container->parameters["tempDir"] . "/cache"));

                if ($container->application->presenter) {

                        $file = $container->application->presenter->template->getFile();
                        if (!empty($file)) {

                                $template->templatePath = $file;
                                $template->templateCode = $this->printCode($file);
                                $template->presenterLink = $container->application->getPresenter()->link('this');
                                $template->xhr_header = self::XHR_HEADER;
                        }
                }

		ob_start();
                echo $template->render();

                return ob_get_clean();
	}


        /**
         * Process AJAX requests 
         */
	private function handleRequest()
	{
                $container = $this->container;

		$request = $container->httpRequest;

		if ($request->isPost() && $request->isAjax() && $request->getHeader(self::XHR_HEADER)) {

			$data = json_decode(file_get_contents("php://input"));
			if (isset($data->{self::XHR_HEADER})) {

                                $data = $data->{self::XHR_HEADER};                            
                                if ($data->force == false) {
                                        if ($this->isModificated($data->file, $data->loadtime)) {
                                                echo "needconfirm";
                                                die();
                                        }
                                }
                                $this->save($data->data, $data->file);
			}

                        die();
		}
	}


        /**
         * Save code to file
         * @param string $data
         * @param string $filePath
         */
        private function save($data, $filePath)
        {
                if (file_exists($filePath)) {
                        file_put_contents("safe://$filePath", $data);
                } else
                        throw new \Nette\FileNotFoundException("File '$filePath' already does not exist!");
        }


        /**
         * Checks if file was modificated after opening
         * @param string $filePath
         * @param int $loadtime
         * @return bool
         */
        private function isModificated($filePath, $loadtime)
        {
                if (filemtime($filePath) > $loadtime)
                        return true;
                else
                        return false;
        }


        private function printCode($file)
        {
                $filter = new Filter;
                return $this->nl2br($filter->applyFilters(file_get_contents($file)));
        }


        /**
         * It helps to prevent double lines in <pre> tag
         * @param string $string
         * @return string
         */
        private function nl2br($string)
        {
                return str_replace("\n", "<br>", $string);
        }
}