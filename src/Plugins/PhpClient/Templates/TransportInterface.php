<?php echo '<?php'; ?>


namespace <?php echo $namespace; ?>;

interface TransportInterface
{
    public function sendHttpJson(ApiRequest $request) : ApiResponse;
}
