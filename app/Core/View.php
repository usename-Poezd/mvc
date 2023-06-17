<?php
namespace App\Core;

use App\Routing\Response;

class View extends Response
{
    private const VIEWS_PATH = "/assets/views";

    public function __construct(
        protected string $view,
        protected mixed $data = [],
        protected int $status = 200,
        protected array $options = [
            "Content-Type" => "text/html;charset=utf-8"
        ],
    )
    {
        parent::__construct($this->data, $this->status, $this->options);
    }

    protected function getViewFile(): string
    {
        $separated = explode(".", $this->view);


        $file = $separated[count($separated) - 1] . ".php";
        array_splice($separated, count($separated) - 1, 1);
        $folders = $separated;

        $path = $_SERVER["APP_DIR"] . self::VIEWS_PATH . "/". join("/", $folders) . "/" . $file;



        return $path;
    }

    public function getBody(): string
    {
        $path = $this->getViewFile();

        ob_start();
        $data = $this->data;
        extract($data);
        include $path;
        $content = ob_get_clean();
        return $content;
    }
}