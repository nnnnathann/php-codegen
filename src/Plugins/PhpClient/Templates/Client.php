<?php echo '<?php'; ?>


namespace <?php echo $namespace; ?>;

use <?php echo $namespace; ?>\Internal\ApiRequest;
use <?php echo $namespace; ?>\Internal\ApiResponse;
use <?php echo $namespace; ?>\Internal\DefaultTransport;
<?php foreach ($actions as $action): ?>
use <?php echo $namespace; ?>\Data\<?php echo ucfirst($action->name); ?>Input;
use <?php echo $namespace; ?>\Data\<?php echo ucfirst($action->name); ?>Result;
<?php endforeach; ?>
use TypeError;

final class Client
{
    private array $options = [
        'transport' => null,
        'curlopts' => [],
    ];
    private string $endpoint;

    public function __construct(string $endpoint, $options = [])
    {
        $this->endpoint = $endpoint;
        $this->options['transport'] = $options['transport'] ?: new DefaultTransport($options['curlopts'] ?? []);
    }

<?php foreach ($actions as $action): ?>
    /**
     * @param <?php echo ucfirst($action->name); ?>Input $input
     * @return <?php echo ucfirst($action->name); ?>Result
     * @throws ClientException
     */
    public function <?php echo lcfirst($action->name); ?>(<?php echo ucfirst($action->name); ?>Input $input) : <?php echo ucfirst($action->name); ?>Result
    {
        return $this->run($input, <?php echo ucfirst($action->name); ?>Result::class);
    }

<?php endforeach; ?>
    private function run($input, $resultClass)
    {
        try {
            $response = $this->options['transport']->sendHttpJson(new ApiRequest());

            return $this->hydrateResult($response->toArrayOrThrow(), new $resultClass);
        } catch (TypeError $error) {
            throw new ClientException('error decoding value', 500, $error);
        }
    }

    private function hydrateResult($data, $receiver)
    {
        foreach ($data as $key => $value) {
            $receiver[$key] = $value;
        }

        return $receiver;
    }
}