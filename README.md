# PHPDocxTemplates
A PHP Library For Using Docx Files As Fillable Templates

## Installation
Currently you will need to download the classes and register the "SNicholson" folder in your autoloader. I will be moving this onto packagist for installation via composer.

## Usage
Using PHPDocxTemplates is simple, to run a merge on a document, you first need to define a rule collection. This is done in the following fashion, rules are a target/data combination. Target is the string to be replaced, and data is the value to replace it. Data is currently supported as either a string or a closure, for example - <br>
``` php
$ruleCollection = new RuleCollection();
$ruleTarget = '#texttoreplace#';
$ruleData = function(){
    return 'a test value from a closure';
};
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule('#someMoreTextToReplace#','Some text that needs replacing!');
```
You can perform a SimpleMerge using the below class (in namepsace SNicholson/PHPDocXTemplate), you need to provide it with the filepath to your template, and a filepath for the output file, it will save it in place for you.
``` php
SimpleMerge::perform('input.docx','output.docx',$ruleCollection);
```
