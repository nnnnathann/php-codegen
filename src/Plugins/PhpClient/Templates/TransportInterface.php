<?php echo '<?php'; ?>


namespace <?php echo $namespace; ?>;

use <?php echo $namespace; ?>\Internal\ApiRequest;
use <?php echo $namespace; ?>\Internal\ApiResponse;

interface TransportInterface
{
    public function sendHttpJson(ApiRequest $request) : ApiResponse;
}
