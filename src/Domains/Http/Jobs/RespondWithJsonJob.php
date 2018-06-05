<?php

namespace Anfischer\Foundation\Domains\Http\Jobs;

use Anfischer\Foundation\Job\Job;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class RespondWithJsonJob extends Job
{
    protected $status;
    protected $content;
    protected $headers;
    protected $options;

    public function __construct($content = null, int $status = 200, array $headers = [], int $options = 0)
    {
        $this->content = $content;
        $this->status = $status;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function handle(ResponseFactory $factory) : JsonResponse
    {
        $response = [
            'data' => $this->content,
            'status' => $this->status,
        ];

        return $factory->json($response, $this->status, $this->headers, $this->options);
    }
}
