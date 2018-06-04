<?php

namespace Anfischer\Foundation\Domains\Http\Jobs;

use Anfischer\Foundation\Job\Job;
use Illuminate\Routing\ResponseFactory;

class RespondWithJsonErrorJob extends Job
{
    protected $content;
    protected $status;
    protected $headers;
    protected $options;

    public function __construct(
        string $message = 'An error occurred',
        int $code = 400,
        int $status = 400,
        array $headers = [],
        int $options = 0
    ) {
        $this->content = [
            'status' => $status,
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
        ];

        $this->status = $status;
        $this->headers = $headers;
        $this->options = $options;
    }

    public function handle(ResponseFactory $response)
    {
        return $response->json($this->content, $this->status, $this->headers, $this->options);
    }
}
