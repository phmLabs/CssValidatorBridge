<?php

namespace phmLabs\CssValidatorBridge;

class CssValidator
{
    public function validate($cssString)
    {
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . md5(microtime()) . '.tmp';
        file_put_contents($file, $cssString);

        $command = __DIR__ . '/../validator/node_modules/.bin/stylelint ' . $file . ' --config ' . __DIR__ . '/../validator/config.json -f json 2>&1';
        exec($command, $plainOutput, $return);
        unlink($file);

        $result = json_decode($plainOutput[0], true);

        if (is_array($result)) {
            $warnings = $result[0]['warnings'];
            return $warnings;
        } else {
            return [];
        }
    }
}