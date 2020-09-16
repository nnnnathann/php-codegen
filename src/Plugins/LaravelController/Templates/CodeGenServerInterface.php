<?php echo "<?php"; ?>


namespace CodeGen\Laravel;

<?php foreach ($actions as $action): ?>
use CodeGen\Laravel\Data\<?php echo ucfirst($action->name); ?>Input;
use CodeGen\Laravel\Data\<?php echo ucfirst($action->name); ?>Result;
<?php endforeach; ?>


interface CodeGenServerInterface
{<?php foreach ($actions as $action): ?>

    public function <?php echo $action->name; ?>(<?php echo ucfirst($action->name); ?>Input $input) : <?php echo ucfirst($action->name); ?>Result;
<?php endforeach; ?>
}