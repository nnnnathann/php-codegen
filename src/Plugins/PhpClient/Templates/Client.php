<?php echo '<?php'; ?>


namespace <?php echo $namespace; ?>;

use <?php echo $namespace; ?>\Internal\ApiRequest;
use <?php echo $namespace; ?>\Internal\ApiResponse;
use <?php echo $namespace; ?>\Internal\DefaultTransport;
<?php foreach ($actions as $action): ?>
use <?php echo $namespace; ?>\Data\<?php echo ucfirst($action->name); ?>Input;
use <?php echo $namespace; ?>\Data\<?php echo ucfirst($action->name); ?>Result;
<?php endforeach; ?>
use JsonException;
use TypeError;
use Exception;

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
        $this->options['transport'] = $options['transport'] ?? new DefaultTransport($options['curlopts'] ?? []);
    }

<?php foreach ($actions as $action): ?>
    /**
     * @param <?php echo ucfirst($action->name); ?>Input $input
     * @return <?php echo ucfirst($action->name); ?>Result
     * @throws ClientException
     */
    public function <?php echo lcfirst($action->name); ?>(<?php echo ucfirst($action->name); ?>Input $input) : <?php echo ucfirst($action->name); ?>Result
    {
        return $this->run("<?php echo lcfirst($action->name); ?>", $input, <?php echo ucfirst($action->name); ?>Result::class);
    }

<?php endforeach; ?>
    private function run($commandName, $input, $resultClass)
    {
        try {
            $request = new ApiRequest($this->endpoint, $commandName, $input);
            $response = $this->options['transport']->sendHttpJson($request);

            return $resultClass::fromArray($response->toArrayOrThrow());
        } catch (TypeError $error) {
            throw new ClientException('error decoding value', 500, $error);
        } catch (JsonException $error) {
            throw new ClientException('error encoding value', 500, $error);
        } catch (Exception $error) {
            throw new ClientException('unspecified client error', 500, $error);
        }
    }
}