<?php namespace Way\Generators\Commands;

use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ViewGeneratorCommand extends GeneratorCommand {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a view';

    /**
     * Create directory tree for views,
     * and fire generator
     */
    public function fire()
    {
        $directoryPath = dirname($this->getFileGenerationPath());

        if ( ! File::exists($directoryPath))
        {
            File::makeDirectory($directoryPath, 0777, true);
        }

        parent::fire();
    }

    /**
     * The path where the file will be created
     *
     * @return mixed
     */
    protected function getFileGenerationPath()
    {
        $path = $this->getPathByOptionOrConfig('path', 'view_target_path');
        $viewName = str_replace('.', '/', $this->argument('viewName'));

        return sprintf('%s/%s.blade.php', $path, $viewName);
    }

    /**
     * Fetch the template data
     *
     * @return array
     */
    protected function getTemplateData()
    {
        return [
            'PATH' => $this->getFileGenerationPath()
        ];
    }

    /**
     * Get path to the template for the generator
     *
     * @return mixed
     */
    protected function getTemplatePath()
    {
        $names = explode(".", $this->argument('viewName'));
        $viewName = end($names);
        switch($viewName){
            case "create": $path = "view_create_template_path"; break;
            case "index": $path = "view_index_template_path"; break;
            case "edit": $path = "view_edit_template_path"; break;
            case "show": $path = "view_show_template_path"; break;
            default: $path = "view_template_path";
        }
        return $this->getPathByOptionOrConfig('templatePath', $path);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['viewName', InputArgument::REQUIRED, 'The name of the desired view']
        ];
    }

}
