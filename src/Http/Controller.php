<?php

namespace Anfischer\Foundation\Http;

use Anfischer\Foundation\Feature\Concerns\ServesFeatures;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    use ValidatesRequests;
    use ServesFeatures;
}
