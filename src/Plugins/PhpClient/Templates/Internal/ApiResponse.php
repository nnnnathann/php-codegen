<?php echo '<?php'; ?>


namespace <?php echo $namespace; ?>\Internal;

use RuntimeException;

class ApiResponse
{
    private ?string $data;
    private ?RuntimeException $exception;

    public function __construct(string $data = null, RuntimeException $exception = null)
    {
        $this->data = $data;
        $this->exception = $exception;
    }

    public function isError()
    {
        return $this->exception !== null;
    }

    public function isOk()
    {
        return !$this->isError();
    }

    public function toArrayOrThrow()
    {
        if ($this->isError()) {
            throw new MyApiClientException('error in client request', 0, $this->exception);
        }
    }

    public function toArray()
    {
        if ($this->isError()) {
            return ['error' => $this->exception->getMessage()];
        }

        return json_decode($this->data);
    }
}
