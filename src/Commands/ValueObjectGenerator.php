<?php

namespace Chalcedonyt\ValueObject\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem as File;
use Illuminate\View\Factory as View;

class ValueObjectGenerator extends Command
{
    const NO_CLASS_SPECIFIED = 'mixed';
    const NO_PARAMETER_SPECIFIED = '(no_param)';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:valueobject {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a value object';
    /**
     * @var
     */
    private $view;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var File
     */
    private $file;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(View $view, File $file)
    {
        parent::__construct();
        $this -> view = $view;
        $this -> file = $file;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            // replace all space after ucwords
            $classname = preg_replace('/\s+/', '', ucwords($this->argument('classname')));


            //retrieves store directory configuration
            $directory = app_path('ValueObjects/');

            //retrieves namespace configuration
            $namespace = 'App\ValueObjects';

            is_dir($directory) ?: $this->file->makeDirectory($directory, 0755, true);

            $create = true;
            $parameters = collect([]);
            $parameter_string = '';

            /**
             * if we are entering paramters
             */
            $i = 0;
            while($parameter = $this -> ask("Enter the class or variable name for parameter ".($i++)." (Examples: \App\User or \$user) [Blank to stop entering parameters]", self::NO_PARAMETER_SPECIFIED)){
                if( $parameter == self::NO_PARAMETER_SPECIFIED )
                    break;

                //if class starts with $, don't type hint
                if( strpos($parameter, '$') === 0 ){
                    $parameter_class = null;
                    $parameter_name = str_replace('$','',$parameter);
                } else{
                    /**
                     * Extract the last element of the class after "\", e.g. App\User -> $user
                     */
                    $derive_variable_name = function() use ($parameter){
                        $parts = explode("\\", $parameter);
                        return end( $parts );
                    };
                    $parameter_class = $parameter;
                    $parameter_name = strtolower( $derive_variable_name() );
                }
                $parameters -> push(['class' => $parameter_class, 'name' => $parameter_name]);
            }

            if( $parameters -> count())
            {
                $parameter_string_array = [];
                $parameters -> each(function( $p ) use( &$parameter_string_array){
                    if( $p['class'])
                        $parameter_string_array[]=$p['class'].' $'.$p['name'];
                    else
                        $parameter_string_array[]='$'.$p['name'];
                });
                $parameter_string = implode(', ', $parameter_string_array);
            }

            if ($this->file->exists("{$directory}/{$classname}.php")) {
                if ($usrResponse = strtolower($this->ask("The file ['{$classname}'] already exists, overwrite? [y/n]",
                    null))
                ) {
                    switch ($usrResponse) {
                        case 'y' :
                            $tempFileName = "{$directory}/{$classname}.php";

                            $prefix = '_';
                            while ($this->file->exists($tempFileName)) {
                                $prefix .= '_';
                                $tempFileName = "{$directory}/{$prefix}{$classname}.php";
                            }
                            rename("{$directory}/{$classname}.php", $tempFileName);
                            break;
                        default:
                            $this->info('No file has been created.');
                            $create = false;
                    }
                }

            }
            $args = ['namespace' => $namespace,
            'classname' => $classname,
            'parameter_string' => $parameter_string,
            'parameters' => $parameters -> all()];

            // loading template from views
            $view = $this->view->make('valueobject::valueobject',$args);


            if ($create) {
                $this->file->put("{$directory}/{$classname}.php", $view->render());
                $this->info("The class {$classname} generated successfully.");
            }


        } catch (\Exception $e) {
            $this->error('Value Object creation failed: '.$e -> getMessage());
        }
    }
}
