<?php

namespace Proengsoft\JsValidation\Tests\Javascript;

use PHPUnit\Framework\TestCase;
use Proengsoft\JsValidation\Javascript\MessageParser;

class MessageParserTest extends TestCase
{
    public function testGetMessage()
    {
        $attribute = 'field';
        $rule = 'Required';
        $params=[];
        $data = [];
        $files = [];

        $delegated = $this->getMockBuilder(\Proengsoft\JsValidation\Support\DelegatedValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $delegated->expects($this->once())
            ->method('getData')
            ->willReturn($data);

        $delegated->expects($this->once())
            ->method('setData')
            ->with($data);

        $delegated->expects($this->once())
            ->method('getMessage')
            ->with($attribute,$rule)
            ->willReturn("$attribute $rule");

        $delegated->expects($this->once())
            ->method('makeReplacements')
            ->with("$attribute $rule",$attribute,$rule, $params)
            ->willReturn("$attribute $rule");

        $parser = new MessageParser($delegated);

        $message = $parser->getMessage($attribute,$rule,$params);

        $this->assertEquals("$attribute $rule", $message);
    }

    public function testGetMessageRequiredIf()
    {

        $attribute = 'field';
        $rule = 'RequiredIf';
        $params=['field2','value2'];
        $data = [];
        $files = [];

        $delegated = $this->getMockBuilder(\Proengsoft\JsValidation\Support\DelegatedValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $delegated->expects($this->once())
            ->method('getData')
            ->willReturn($data);

        $delegated->expects($this->exactly(2))
            ->method('setData')
            ->with($this->logicalOr(
                $this->equalTo([$params[0]=>$params[1]]),
                $this->equalTo($data)
            ));


        $delegated->expects($this->once())
            ->method('getMessage')
            ->with($attribute,$rule)
            ->willReturn("$attribute $rule");

        $delegated->expects($this->once())
            ->method('makeReplacements')
            ->with("$attribute $rule",$attribute,$rule, $params)
            ->willReturn("$attribute $rule");

        $parser = new MessageParser($delegated);

        $message = $parser->getMessage($attribute,$rule,$params);

        $this->assertEquals("$attribute $rule", $message);
    }

    public function testGetMessageFiles()
    {

        $attribute = 'field';
        $rule = 'Image';
        $params=[];
        $data = [];
        $files = [];

        $delegated = $this->getMockBuilder(\Proengsoft\JsValidation\Support\DelegatedValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $delegated->expects($this->once())
            ->method('getData')
            ->willReturn($data);


        $delegated->expects($this->once())
            ->method('hasRule')
            ->with($attribute, ['Mimes', 'Image'])
            ->willReturn(true);


        $delegated->expects($this->once())
            ->method('getMessage')
            ->with($attribute,$rule)
            ->willReturn("$attribute $rule");

        $delegated->expects($this->once())
            ->method('makeReplacements')
            ->with("$attribute $rule",$attribute,$rule, $params)
            ->willReturn("$attribute $rule");

        $parser = new MessageParser($delegated);

        $message = $parser->getMessage($attribute,$rule,$params);

        $this->assertEquals("$attribute $rule", $message);
    }

    public function testEscape()
    {
        $attribute = 'field';
        $rule = 'Image';
        $return = "<html>";
        
        $delegated = $this->getMockBuilder(\Proengsoft\JsValidation\Support\DelegatedValidator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $delegated->expects($this->once())
            ->method('getData')
            ->willReturn([]);
        
        $delegated->expects($this->once())
            ->method('hasRule')
            ->with($attribute, ['Mimes', 'Image'])
            ->willReturn(true);
        
        $delegated->expects($this->once())
            ->method('getMessage')
            ->with($attribute, $rule)
            ->willReturn($return);

        $delegated->expects($this->once())
            ->method('makeReplacements')
            ->with($return, $attribute, $rule, [])
            ->willReturn($return);

        $parser = new MessageParser($delegated, true);

        $message = $parser->getMessage($attribute, $rule, []);

        $this->assertEquals("&lt;html&gt;", $message);
    }
}
