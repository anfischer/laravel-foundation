<?php

namespace Anfischer\Foundation\Domains\Http\Jobs;

use Anfischer\Foundation\Job\Job;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class RespondWithViewJob extends Job
{
    protected $status;
    protected $data;
    protected $headers;
    protected $template;

    public function __construct(string $template, array $data = [], int $status = 200, array $headers = [])
    {
        $this->template = $template;
        $this->data = $data;
        $this->status = $status;
        $this->headers = $headers;
    }

    public function handle(ResponseFactory $factory) : Response
    {
        return $factory->view($this->template, $this->data, $this->status, $this->headers);
    }
}
