<?php echo "<?php"; ?>

namespace CodeGen\Laravel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class CodeGenController extends Controller
{
    /** @var CodeGenServerInterface **/
    private $server;
    public function __construct(CodeGenServerInterface $server)
    {
        $this->server = $server;
    }
    public function __invoke(Request $request)
    {
        $commandName = $request->json('commandName');
        $input = $request->json('inputData');
        if (!method_exists($this->server, $commandName)) {
            throw new NotFoundHttpException('command not found: ' . $commandName);
        }
        $inputTypeName = sprintf("\CodeGen\Laravel\Data\\%sInput", ucfirst($commandName));
        return response()->json($this->server->$commandName($inputTypeName::fromArray($input)));
    }
}