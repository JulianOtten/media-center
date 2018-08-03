<?php 
class Logger {
    /**
     * This is the absolute path to the logging dirctory. The path
     * MUST ends with a slash(/) in order to work correctly!
     * 
     * @var String
     */
    protected $path;
    /**
     * Creates and prepares the logger to be used with the given path, if
     * no path is given, the current working directory will be used as
     * the root, and a log folder will be set as the logging endpoint.
     * 
     * @param String|null $path
     */
    public function __construct($path = null)
    {
        if ($path == null) {
            $path = __DIR__ . '/../../logs/';
        }
        $this->path = $path;
    }
    /**
     * Logs the given message with the given type.
     *
     * @param  String       $message
     * @param  String|nuill $type
     * @return void
     */
    public function log($message, $type = null)
    {
        if ($type == null) {
            return $this->info($message);
        }
        file_put_contents($this->generateLogFile(), $this->buildContent($message, $type), FILE_APPEND);
    }
    /**
     * Logs the given messae using the INFO tag, any extra arguments given to
     * the method will be formatted using sprintf onto the message given.
     * 
     * @param  String       $message
     * @param  String|null  $args
     * @return void
     */
    public function info($message, ... $args)
    {
        return $this->log(sprintf($message, ...$args), '[INFO]');
    }
    /**
     * Logs the given messae using the WARN tag, any extra arguments given to
     * the method will be formatted using sprintf onto the message given.
     * 
     * @param  String       $message
     * @param  String|null  $args
     * @return void
     */
    public function warning($message, ...$args)
    {
        return $this->log(sprintf($message, ...$args), '[WARN]');
    }
    /**
     * Logs the given messae using the ERROR tag, any extra arguments given to
     * the method will be formatted using sprintf onto the message given.
     * 
     * @param  String       $message
     * @param  String|null  $args
     * @return void
     */
    public function error($message, ...$args)
    {
        return $this->log(sprintf($message, ...$args), '[ERROR]');
    }
    /**
     * Logs the given messae using the DEBUG tag, any extra arguments given to
     * the method will be formatted using sprintf onto the message given.
     * 
     * @param  String       $message
     * @param  String|null  $args
     * @return void
     */
    public function debug($message, ...$args)
    {
        return $this->log(sprintf($message, ...$args), '[debug]');
    }
    /**
     * Generates the log file, this will generate an absolute path
     * to a file formatted by year, month, and day.
     * 
     * @return String
     */
    protected function generateLogFile()
    {
        return $this->path . date('Y-m-d') . '.log'; 
    }
    /**
     * Builds the logging content and prepares it
     * before it is stored in a log file.
     * 
     * @param  String $content
     * @param  String $type
     * @return String
     */
    protected function buildContent($content, $type)
    {
        return sprintf(
            "[%s] %s  %s\n",
            date('Y-m-d h:i:s'),
            $type, $content
        );
    }
}