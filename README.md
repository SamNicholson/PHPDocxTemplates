# PHPDocxTemplates
A PHP Library For Using Docx Files As Fillable Templates

## Installation
Can be installed via composer, simply require the following - 

``` javascript
require: "snicholson/docxtemplates": "dev-master"
```

## Usage
Using PHPDocxTemplates is simple, to run a merge on a document, you first need to define a rule collection. This is done
 in the following fashion, rules are a target/data combination.
Target is the string to be replaced, and data is the value to replace it.
You can perform a SimpleMerge using the below class (in namepsace SNicholson/PHPDocXTemplate), you need to provide it with
the filepath to your template, and a filepath for the output file, it will save it in place for you.
The below is an example of Simple merge using only simple rules - <br>
``` php
$ruleCollection = new RuleCollection();
$ruleTarget = '#texttoreplace#';
$ruleData = function(){
    return 'a test value from a closure';
};
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule('#someMoreTextToReplace#','Some text that needs replacing!');
DocXTemplate::merge('input.docx','output.docx',$ruleCollection);
```

### Simple Rules
A simple rule is a simple string replace, the target is the string to be replaced, and the data is either a string or 
closure to replace it with, SimpleRules are added to Rule Collections as in the example below

``` php

$ruleCollection = new RuleCollection();
$ruleTarget = '#texttoreplace#';
$ruleData = function(){
    return 'a test value from a closure';
};
$ruleCollection->addSimpleRule($ruleTarget,$ruleData);
$ruleCollection->addSimpleRule(
 '#someMoreTextToReplace#',
 'Some text that needs replacing!'
);

```

### Regexp Rules
Regular Expression rules are slightly more advanced, they allow you to specify a regular expression to match, receive matches
in a closure and act on their values accordingly - <br>

``` php
$ruleCollection = new RuleCollection();
$ruleTarget = '/ARegularExpression/';
$ruleData = function($match){
    //I can perform logic on the $match value in here
    return substr($match,0,3);
};o
$ruleCollection->addRegexpRule($ruleTarget,$ruleData);
```

### Merging RuleCollections into Word Documents
Merging can be done simply using the Static simpleMerge class, it is intended to add more advanced merge Classes in the future.
 You must provide an inbound filepath and an outbound filepath, along with a RuleCollection. The outbound filepath needs to be
 writable to PHP.
 
``` php
DocXTemplate::merge('input.docx','output.docx',$ruleCollection);
```

## TODO
Outstanding<br>
 - Be able to replace text with images<br>
 - Be able to generate an insert tables in place of text<br>
 - Support Basic formatting (bold, italic, line returns) from HTML input to the XML format
